<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Traits\RolePermissionsTrait;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Group;
use App\Models\Role;
use App\Models\Task;
use App\Models\TelegramUser;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, RolePermissionsTrait;
    
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
    
    public function tasks() {
      return $this->hasMany(Task::class, 'user_id');
    }
    
    public function telegramUser() {
      return $this->hasOne(TelegramUser::class, 'user_id', 'id');
    }
    
    //-----------------------------------------------------//
        
    
    public function canFirstCreate(): bool 
    {
        $hasFirstProcess = $this->with('groups.processes')
                        ->whereHas('groups.processes', function($q) {
                                $q->where('sequence', 1);
                        })->get();
        
        return $hasFirstProcess->isNotEmpty();
    }
    
    public static function getUsersByProcess(int $processId): Collection 
    {
        $usersByProcess = self::with('groups.processes')
                        ->whereHas('groups.processes', function($q) use($processId) {
                                $q->where('processes.id', $processId);
                        })->get();
        
        return $usersByProcess;
    }
    
    public function canThisProcessDo(int $processId): bool 
    {
        $hasThisProcess = $this->with('groups.processes')
                        ->whereHas('groups.processes', function($q) use($processId) {
                                $q->where('processes.id', $processId);
                        })->get();
        
        return $hasThisProcess->isNotEmpty();
    }
}
