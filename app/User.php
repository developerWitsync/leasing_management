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
        'industry_type','applicable_gaap','legal_status','country','currency','gender','annual_reporting_period',
        'email_verification_code','is_verified','parent_id'
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
}
