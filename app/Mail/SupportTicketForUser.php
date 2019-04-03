<?php

namespace App\Mail;

use App\EmailTemplates;
use App\SupportTickets;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use URL;

class SupportTicketForUser extends Mailable
{
    use Queueable, SerializesModels;
    public $ticket;
    public $user;
    public $html = '';
    public $email_template_code = 'SUPPORT_TICKET_USER';

    /**
     * Create a new message instance.
     * SupportTicketForUser constructor.
     * @param SupportTickets $ticket
     */
    public function __construct(SupportTickets $ticket)
    {
        $this->ticket = $ticket;
        $this->user = $ticket->user;
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
        $created_at = Carbon::parse($this->ticket->created_at)->format(config('settings.date_format'));

        $to_be_replaced_by_string   = [
            URL::to('/'),
            env('APP_NAME'),
            ucwords($this->user->authorised_person_name),
            $this->ticket->ticket_number,
            $this->ticket->subject,
            $this->ticket->message,
            $created_at,
            env('COMPANY_NAME')
        ];

        $template = str_replace($to_bo_replaced_strings, $to_be_replaced_by_string, $template_body);
        $this->html = $template;
        $view = $this->view('emails._blank');
        if(!is_null($this->ticket->attachment)){
            $view->attach(public_path()."/uploads/".$this->ticket->attachment);
        }
        return $view;
    }
}
