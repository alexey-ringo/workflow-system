<?php

namespace App\Entities;

class Role extends AbstractEntity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $table = 'roles'; 
    protected $fillable = [
        'name', 'slug',
    ];
    
    public function permissions() {
        return $this->belongsToMany(Permission::class,'permission_role');
    }
    
    public function users() {
        return $this->belongsToMany(User::class,'role_user');
    }
}
