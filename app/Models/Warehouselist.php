<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouselist extends Model
{
    protected $table = 'warehouse_list';
    protected $fillable = ['code_warehouse','latitude','longitude','user_id'];
}
