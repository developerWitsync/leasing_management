<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 17/12/18
 * Time: 10:35 AM
 */
namespace App\Observers;
use App\LeasePaymentsNumber;
use App\User;
use App\LeasePaymentsBasis;
use App\Mail\RegistrationConfirmation;
use Mail;
use App\LeaseAssetsNumberSettings;
use App\LeaseAssetSimilarCharacteristicSettings;

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

        //generate the default settings for the registered user
        $this->generateSettings($user);
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

            return true;
        } catch (\Exception $e){
            return false;
        }
    }
}