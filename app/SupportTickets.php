<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportTickets extends Model
{
    protected $table = 'support_tickets';

    protected $fillable = [
        'business_account_id',
        'ticket_number',
        'subject',
        'message',
        'attachment',
        'contact_no',
        'severity',
        'created_at',
        'updated_at'
    ];

    public function user(){
        return $this->hasOne('App\User', 'id', 'business_account_id');
    }
}
