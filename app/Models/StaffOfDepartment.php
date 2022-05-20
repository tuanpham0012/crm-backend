<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffOfDepartment extends Model
{
    protected $table = "staff_department";
    protected $fillable = [
        'department_id', 'user_id', 'position_id'
    ];

    public function Departments()
    {
        return $this->belongsTo('App\Models\Department', 'department_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function Position()
    {
        return $this->belongsTo('App\Models\Position', 'position_id', 'id');
    }

}
