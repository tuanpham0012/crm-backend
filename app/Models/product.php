<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['code_product','name','type_of_product_id','origin','unit','describe','VAT'];

}
