<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "departments";
    protected $fillable = [
        'code_department', 'name'
    ];
    public $timestamps = false;

    public function StaffOfDepartment()
    {
        return $this->hasMany('App\Models\StaffOfDepartment', 'department_id', 'id');
    }
}
