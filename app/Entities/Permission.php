<?php

namespace App\Entities;

class Permission extends AbstractEntity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'permissions';
    protected $fillable = [
        'name', 'slug',
    ];
    
    public function roles() {
        return $this->belongsToMany(Role::class,'permission_role');
    }
}
