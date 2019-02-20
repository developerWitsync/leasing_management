<?php

namespace App\Mail;

use App\User;
use App\SubscriptionPlans;
use App\UserSubscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\EmailTemplates;
class RegistrationCredentials extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $package;
    public $userSubscription;
    public $html = '';
    public $email_template_code = 'REGISTRATION_INVOICE';

    /**
     * create instance of RegistrationCredentials mail.
     * RegistrationCredentials constructor.
     * @param User $user
     * @param SubscriptionPlans $package
     * @param UserSubscription $userSubscription
     */
    public function __construct(User $user, SubscriptionPlans $package, UserSubscription $userSubscription)
    {
        $this->user = $user;
        $this->package = $package;
        $this->userSubscription = $userSubscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = EmailTemplates::query()->where('template_code', '=',$this->email_template_code)->first();
        $template_body              = $template->template_body;
        $to_bo_replaced_strings     = explode(',', $template->template_special_variables);

        $to_be_replaced_by_string   = [
            env('APP_NAME'),
            ucwords($this->user->authorised_person_name),
            ucwords($this->package->title),
            Carbon::parse($this->userSubscription->created_at)->format(config('settings.date_format')),
            Carbon::parse($this->userSubscription->subscription_expire_at)->format(config('settings.date_format')),
            $this->user->email,
            $this->user->raw_password,
            $this->user->account_id,
            env('COMPANY_NAME')
        ];

        $this->user->raw_password = null;
        $this->user->save();

        $template = str_replace($to_bo_replaced_strings, $to_be_replaced_by_string, $template_body);

        $this->html = $template;

        //need to format the html here for the registration email that needs to be send to the user.
        return $this->view('emails._blank');
    }
}
