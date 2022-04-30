<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeProduct extends Model
{
    protected $table = "type_of_product";
    protected $fillable = ['id', 'type',];

    public $timestamps = false;
}
