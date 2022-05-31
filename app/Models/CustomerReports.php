<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReports extends Model
{
    protected $table = 'customer_reports';
    protected $fillable = ['customer_id', 'content', 'note', 'status', 'user_id'];

    public function User(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
