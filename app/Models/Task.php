<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = [
        'name', 'start', 'end', 'content', 'type_of_task_id', 'user_id'
        ,'customer_id'
        ,'task_status_id'
    ];

    public function TypeOfTask(){
        return $this->belongsTo('App\Models\TypeOfTask', 'type_of_task_id', 'id');
    }
    public function TaskStatus(){
        return $this->belongsTo('App\Models\TaskStatus', 'task_status_id', 'id');
    }
    public function User(){
        return $this->belongsTo('App\User');
    }
    public function Customer(){
        return $this->belongsTo('App\Models\Customer');
    }
    public function TaskUser(){
        return $this->hasMany('App\Models\TaskUser');
    }
    public function NoteOfTask(){
        return $this->hasMany('App\Models\NoteOfTask')->latest();
    }
}
