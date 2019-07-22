<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Traits\PermissionsTrait;

use App\Models\Group;
use App\Models\Role;
use App\Models\Task;
use App\Models\TelegramUser;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, PermissionsTrait;
    
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'is_admin'
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
    
    public function isAdmin()
    {
        return $this->is_admin;
    }
    
    public function roles() {
      return $this->belongsToMany(Role::class,'role_user');
    }
   
    public function groups() {
      return $this->belongsToMany(Group::class,'group_user');
    }
    
    public function Tasks() {
      return $this->hasMany(Task::class, 'user_id');
    }
    
    public function telegramUser() {
      return $this->hasOne(TelegramUser::class, 'user_id', 'id');
    }
    
    //-----------------------------------------------------//
    
    
    public function scopeCurrentSequence() {
        
    }
    
    public function scopeNextSequence() {
        
    }
    
    
}
