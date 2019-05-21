<?php

namespace App\Mail;

use App\EmailTemplates;
use App\ContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class ContactUsQueryFrom extends Mailable
{
    use Queueable, SerializesModels;

    public $contactus;
    public $html = '';
        public $email_template_code = 'CONTACT_US ';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ContactUs $contactus)
    {
        $this->contactus = $contactus;
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
            URL::to('/'),
        	$this->contactus->first_name,
        	$this->contactus->last_name,
        	$this->contactus->email,
        	$this->contactus->phone,
        	$this->contactus->no_of_realestate,
        	$this->contactus->comments,
        	env('APP_NAME'),
            env('COMPANY_NAME')
        ];

        $template = str_replace($to_bo_replaced_strings, $to_be_replaced_by_string, $template_body);

        $this->html = $template;

        //need to format the html here for the ContactUs Form that needs to be send to the user and admin.
        return $this->view('emails._blank');
    }
}
