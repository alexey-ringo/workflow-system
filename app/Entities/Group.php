<?php

namespace App\Entities;

class Group extends AbstractEntity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'groups';
    protected $fillable = [
        'name', 'slug',
    ];
    
    public function processes() {
        return $this->belongsToMany(Process::class,'group_process');
    }
    
    public function users() {
        return $this->belongsToMany(User::class,'group_user');
    }
}
