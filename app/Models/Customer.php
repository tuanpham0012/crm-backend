<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";
    protected $fillable = [
        'customer_code', 'name', 'email', 'address', 'date_of_birth', 'gender', 'status','note', 'type_of_customer_id', 'deleted',
    ];


    public function CustomerPhone()
    {
        return $this->hasMany('App\Models\CustomerPhone', 'customer_id', 'id');
    }

    public function Contacts()
    {
        return $this->hasOne('App\Models\Contacts', 'customer_id', 'id');
    }

    public function CustomerNotes()
    {
        return $this->hasMany('App\Models\CustomerNotes', 'customer_id', 'id')->latest();
    }

    public function TypeCustomer(){
        return $this->belongsTo('App\Models\TypeCustomer', 'type_of_customer_id', 'id');
    }
}
