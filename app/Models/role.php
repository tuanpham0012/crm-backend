<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    protected $table = "role";
    protected $fillable = ['id', 'name',];

    public $timestamps = false;

    /**
     * Get all of the User for the role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function User()
    {
        return $this->hasMany('App\User', 'role_id', 'id');
    }
}
