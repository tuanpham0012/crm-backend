<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
   protected $table = 'interrest';
   protected $fillable = [ 'customer_id', 'type_of_product_id'];

   public $timestamps = false;

   public function TypeOfProduct(){
       return $this->belongsTo('App\Models\TypeProduct', 'type_of_product_id', 'id');
   }
}
