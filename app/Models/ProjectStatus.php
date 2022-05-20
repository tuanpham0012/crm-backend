<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    protected $table = "project_statuses";
    protected $fillable = ['id', 'status',];

    public $timestamps = false;

    /**
     * Get all of the User for the role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function User()
    {
        return $this->hasOne('App\User', 'project_status_id', 'id');
    }
}
