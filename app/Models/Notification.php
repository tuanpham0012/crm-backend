<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['title', 'content', 'user_id', 'read', 'relation', 'relation_id'];
}
