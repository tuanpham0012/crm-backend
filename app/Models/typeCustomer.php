<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeCustomer extends Model
{
    protected $table = "type_of_customer";
    protected $fillable = [
        'id', 'type',
    ];
    public $timestamps = false;

    public function Customer(){
        return $this->hasMany( 'App\Models\Customer', 'type_of_customer_id');
    }
}
