<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $table = "projects";
    protected $fillable = ['project_code', 'name', 'start_at', 'end_at', 'content', 'user_id', 'project_status_id'];

    public function User(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function Tasks(){
        return $this->hasMany('App\Models\Task', 'project_id', 'id');
    }

    public function ProjectStatus(){
        return $this->belongsTo('App\Models\ProjectStatus', 'project_status_id', 'id');
    }

    public function ProjectParticipants(){
        return $this->hasMany('App\Models\ProjectParticipant', 'project_id', 'id');
    }

    public function NoteOfProject(){
        return $this->hasMany('App\Models\NoteOfProject', 'project_id', 'id')->latest();
    }
}
