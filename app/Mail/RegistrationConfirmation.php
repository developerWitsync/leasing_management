<?php

namespace App\Mail;

use App\EmailTemplates;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class RegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $html = '';
        public $email_template_code = 'EMAIL_VERIFICATION';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
            URL::to('/'),
            ucwords($this->user->authorised_person_name),
            route('email.confirmation', ['verification_code' => $this->user->email_verification_code]),
            env('COMPANY_NAME')
        ];

        $template = str_replace($to_bo_replaced_strings, $to_be_replaced_by_string, $template_body);

        $this->html = $template;

        //need to format the html here for the registration email that needs to be send to the user.
        return $this->view('emails._blank');
    }
}
