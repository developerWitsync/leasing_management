<?php

namespace App\Mail;

use App\UserSubscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\EmailTemplates;
use PDF;
use App;

class SubscriptionInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $user;
    public $html = '';
    public $email_template_code = 'SUBSCRIPTION_INVOICE';

    /**
     * Create a new message instance.
     * SubscriptionInvoice constructor.
     * @param UserSubscription $subscription
     */
    public function __construct(UserSubscription $subscription)
    {
        $this->subscription = $subscription;
        $this->user = $subscription->user;
    }

    /**
     * Build the message.
     * @return SubscriptionInvoice
     * @throws \Throwable
     */
    public function build()
    {
        $template = EmailTemplates::query()->where('template_code', '=',$this->email_template_code)->first();
        $template_body              = $template->template_body;
        $to_bo_replaced_strings     = explode(',', $template->template_special_variables);

        $plan_period = Carbon::parse($this->subscription->created_at)->format(config('settings.date_format'))." - ".Carbon::parse($this->subscription->subscription_expire_at)->format(config('settings.date_format'));

        $to_be_replaced_by_string   = [
            env('APP_NAME'),
            ucwords($this->user->authorised_person_name),
            $this->subscription->id,
            $this->subscription->subscriptionPackage->title,
            $plan_period,
            env('COMPANY_NAME')
        ];

        $template = str_replace($to_bo_replaced_strings, $to_be_replaced_by_string, $template_body);
        $this->html = $template;

        $subscription = $this->subscription;
        $user = $this->user;
        $invoice_number = env('SOFTWARE_ID')."/".str_pad($subscription->id,3,0,STR_PAD_LEFT)."/".Carbon::parse($subscription->created_at)->format('Y');
        $invoice_name = "{$this->subscription->id}_invoice.pdf";
        if(file_exists(public_path()."/uploads/".$invoice_name)){
            unlink(public_path()."/uploads/".$invoice_name);
        }
        $html = view('invoice.index', compact(
            'subscription',
            'user',
            'invoice_number'
        ))->render();
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->generateFromHtml($html, public_path()."/uploads/".$invoice_name);

        return $this->view('emails._blank')->attach(public_path()."/uploads/".$invoice_name);
    }
}
