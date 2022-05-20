<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectParticipant extends Model
{
    protected $table = 'project_participants';
    protected $fillable = ['project_id', 'user_id', 'accept'];

    /**
     * Get the user that owns the ProjectParticipant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Project(): BelongsTo
    {
        return $this->belongsTo('App\Models\Projects', 'project_id', 'id');
    }

    /**
     * Get the user that owns the ProjectParticipant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User(): BelongsTo
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
