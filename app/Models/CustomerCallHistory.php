<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCallHistory extends Model
{
    protected $table = 'customer_call_histories';
    protected $fillable = ['user_id', 'customer_id', 'phone_contacts', 'time', 'content', 'link_record', 'note', 'call_status_id'];

    public function User(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
