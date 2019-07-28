<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordToken;
use Illuminate\Auth\Notifications\ResetPassword;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
   use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type','email','phone','username', 'password', 'remember_token','created_at', 'updated_at',
        'authorised_person_name', 'authorised_person_designation','authorised_person_dob','legal_entity_name',
        'applicable_gaap','country','state','gender',
        'email_verification_code','is_verified','parent_id', 'account_id', 'raw_password', 'gstin', 'credit_balance', 'address', 'certificates',
        'date_of_incorporation'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send a password reset email to the user
     */
    public function sendPasswordResetNotification($token)
    {
        if(request()->segment('1') == "admin") {
            $this->notify(new MailResetPasswordToken($token));
        } else {
            $this->notify(new ResetPassword($token));
        }
    }

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function childrens(){
        return $this->hasMany(self::class, 'parent_id');
    }

    public function userSubscription(){
        return $this->hasMany('App\UserSubscription', 'user_id', 'id');
    }

    /**
     * This function returns the actual base date from the accounting standard
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function accountingStandard(){
        return $this->hasOne('App\AccountingStandards', 'id', 'applicable_gaap');
    }

    /**
     * this function will return the complete settings so that the base date can be fetched
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function baseDate(){
        return $this->hasOne('App\GeneralSettings', 'business_account_id', 'id');
    }
}
