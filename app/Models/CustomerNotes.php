<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNotes extends Model
{
    protected $table ="customer_notes";
    protected $fillable = [
        'user_id', 'customer_id', 'content'
    ];

    public function User(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function Customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
