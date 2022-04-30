<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_code', 'name', 'email', 'phone', 'date_of_birth', 'gender', 'ethnic', 'cmnd', 'status', 'avatar', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Role(){
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    public function StaffOfDepartment()
    {
        return $this->hasOne('App\Models\StaffOfDepartment', 'user_id', 'id');
    }

    public function Contacts()
    {
        return $this->hasMany('App\Models\Contacts', 'user_id', 'id');
    }

    public function CustomerNotes()
    {
        return $this->hasMany('App\Models\CustomerNotes', 'user_id', 'id');
    }
}
