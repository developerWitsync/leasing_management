<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 17/12/18
 * Time: 10:35 AM
 */
namespace App\Observers;
use App\User;
use App\LeasePaymentsBasis;
use App\Mail\RegistrationConfirmation;
use Mail;

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
            return true;
        } catch (\Exception $e){
            return false;
        }
    }
}