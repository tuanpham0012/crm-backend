<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    protected $table = 'user_education';
    protected $fillable = ['user_id', 'university_name', 'level', 'majors', 'graduate_time'];
}
