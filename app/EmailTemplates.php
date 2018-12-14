<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{
    protected $table = 'email_templates';

    protected $fillable = ['title','template_subject', 'template_code', 'template_body', 'template_special_variables'];

}
