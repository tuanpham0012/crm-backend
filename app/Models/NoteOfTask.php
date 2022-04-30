<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteOfTask extends Model
{
    protected $table = 'note_of_tasks';
    protected $fillable = [
        'task_id', 'user_id', 'content'
    ];

    public function Tasks(){
        return $this->belongsTo('App\Models\Task');
    }
    public function User(){
        return $this->belongsTo('App\User');
    }
    
}
