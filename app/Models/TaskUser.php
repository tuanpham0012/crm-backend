<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    protected $table = 'task_users';
    protected $fillable = ['task_id',
                            'user_id',
                            'accept'];
    public function Task(){
        return $this->belongsTo('App\Models\Task');
    }
    public function User(){
        return $this->belongsTo('App\User');
    }
}
