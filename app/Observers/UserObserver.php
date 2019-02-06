<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 17/12/18
 * Time: 10:35 AM
 */
namespace App\Observers;
use App\EscalationPercentageSettings;
use App\ExpectedLifeOfAsset;
use App\LeasePaymentsNumber;
use App\Permission;
use App\Role;
use App\User;
use App\LeasePaymentsBasis;
use App\Mail\RegistrationConfirmation;
use Mail;
use App\LeaseAssetsNumberSettings;
use App\LeaseAssetSimilarCharacteristicSettings;
use App\CategoriesLeaseAssetExcluded;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //send the confirm acount email to the user from here
        Mail::to($user)->queue(new RegistrationConfirmation($user));

        if($user->parent_id == '0') {
            //generate the default settings for the registered user
            $this->generateSettings($user);
        }
    }

    /**
     * generates the default settings for the user who have registered
     * @param User $user
     * @return bool
     */
    protected function generateSettings(User $user){
        try{
            $lease_payments_basis = config('settings.lease_payments_basis');
            foreach ($lease_payments_basis as $basis){
                LeasePaymentsBasis::create([
                    'business_account_id' => $user->id,
                    'title' => $basis,
                    'status'    => '1'
                ]);
            }

            $number_of_underlying_assets_settings = config('settings.lease_assets_number');
            foreach ($number_of_underlying_assets_settings as $number){
                LeaseAssetsNumberSettings::create([
                    'business_account_id' => $user->id,
                    'number' => $number,
                    'status'    => '1'
                ]);
            }

            $number_of_la_similar_charac_number = config('settings.la_similar_charac_number');
            foreach ($number_of_la_similar_charac_number as $number){
                LeaseAssetSimilarCharacteristicSettings::create([
                    'business_account_id' => $user->id,
                    'number' => $number,
                    'status'    => '1'
                ]);
            }

            $number_of_lease_payments = config('settings.no_of_lease_payments');
            foreach ($number_of_lease_payments as $number){
                LeasePaymentsNumber::create([
                    'business_account_id' => $user->id,
                    'number' => $number
                ]);
            }


            $escalation_percentage_settings = config('settings.escalation_percentage_settings');
            foreach ($escalation_percentage_settings as $number){
                EscalationPercentageSettings::create([
                    'business_account_id' => $user->id,
                    'number' => $number,
                    'status'    => '1'
                ]);
            }

            $expected_useful_life_of_asset = config('settings.expected_useful_life_of_asset');
            foreach ($expected_useful_life_of_asset as $year){
                ExpectedLifeOfAsset::create([
                    'business_account_id' => $user->id,
                    'years' => $year
                ]);
            }

            //have to create a super admin role for the business account and will have to assign the role to the created user if the user is a parent user
            $role = Role::create([
                'name' => 'super_admin',
                'business_account_id'   => $user->id,
                'display_name' => 'Super Admin',
                'description'   => 'Super Admin is the main business account and the super admin will be assigned with all the permissions.'
            ]);

            //assign role to the user
            $user->attachRole($role);

            //assign permissions to the role
            //fetch all the permissions and assign all the permissions to the super admin role created for the current user
            $permissions = Permission::query()->select('id')->get()->pluck('id')->toArray();
            $role->perms()->sync($permissions);

            //category excluded
            CategoriesLeaseAssetExcluded::create([
                'category_id' => '8',
                'business_account_id'   => $user->id,
            ]);

            CategoriesLeaseAssetExcluded::create([
                'category_id' => '5',
                'business_account_id'   => $user->id,
            ]);

            return true;
        } catch (\Exception $e){
            return false;
        }
    }
}