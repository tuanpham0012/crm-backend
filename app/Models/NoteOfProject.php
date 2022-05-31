<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteOfProject extends Model
{
    protected $table = 'note_of_projects';
    protected $fillable = [
        'project_id', 'user_id', 'content'
    ];

    public function Project(){
        return $this->belongsTo('App\Models\Projects');
    }
    public function User(){
        return $this->belongsTo('App\User');
    }
    
}
