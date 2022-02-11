<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class typeCustomer extends Model
{
    protected $table = "type_of_customer";
    protected $fillable = [
        'id', 'type',
    ];

    public function Customer(){
        return $this->hasMany( 'App\Models\Customer', 'type_of_customer_id');
    }
}
