<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";
    protected $fillable = [
        'customer_code', 'name', 'email', 'address', 'date_of_birth', 'zalo',
         'gender','note', 'type_of_customer_id', 'user_id', 'contact_id', 'deleted',
    ];


    public function CustomerPhone()
    {
        return $this->hasMany('App\Models\CustomerPhone', 'customer_id', 'id');
    }

    public function Contacts()
    {
        return $this->belongsTo('App\User', 'contact_id', 'id');
    }

    public function CustomerNotes()
    {
        return $this->hasMany('App\Models\CustomerNotes', 'customer_id', 'id')->latest();
    }

    public function TypeCustomer(){
        return $this->belongsTo('App\Models\TypeCustomer', 'type_of_customer_id', 'id');
    }

    public function Interest(){
        return $this->hasMany('App\Models\Interest', 'customer_id', 'id');
    }

    public function Tasks(){
        return $this->hasMany('App\Models\Task', 'customer_id', 'id');
    }

    public function User(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function SendMailHistories(){
        return $this->hasMany('App\Models\SendMailHistory', 'customer_id', 'id')->latest();
    }

    public function CallHistories(){
        return $this->hasMany('App\Models\CustomerCallHistory', 'customer_id', 'id')->latest();
    }
    public function CustomerReports(){
        return $this->hasMany('App\Models\CustomerReports', 'customer_id', 'id')->latest();
    }
}
