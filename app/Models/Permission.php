<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'permissions';
    protected $fillable = [
        'title', 'name',
    ];
    
    public function roles() {
        return $this->belongsToMany(Role::class,'permission_role');
    }
}
