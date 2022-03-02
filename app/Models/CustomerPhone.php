<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class CustomerPhone extends Model
{
    protected $table = "customer_phone";
    protected $fillable = [
        'customer_id', 'phone'
    ];

    public $timestamps = false;

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
