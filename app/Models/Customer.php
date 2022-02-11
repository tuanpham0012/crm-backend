<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerPhone;
use App\Models\typeCustomer;

class Customer extends Model
{
    protected $table = "customers";
    protected $fillable = [
        'customer_code', 'name', 'email', 'address', 'date_of_birth', 'gender', 'status','note', 'type_of_customer_id', 'deleted',
    ];


    public function CustomerPhone()
    {
        return $this->hasMany(CustomerPhone::class, 'customer_id', 'id');
    }

    public function TypeCustomer(){
        return $this->belongsTo(typeCustomer::Class, 'type_of_customer_id', 'id');
    }
}
