<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendMailHistory extends Model
{
    protected $table = 'send_mail_histories';
    protected $fillable = ['user_id', 'title', 'content', 'customer_id', 'to_email'];

    public function User(){
        return $this->belongsTo( 'App\User', 'user_id', 'id');
    }
    public function Customer(){
        return $this->belongsTo( 'App\Models\Customer', 'customer_id', 'id');
    }
}
