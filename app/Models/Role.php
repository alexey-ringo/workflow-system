<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use App\Models\User;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
