<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mission;
use App\Models\User;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];
    
    public function missions() {
        return $this->belongsToMany(Mission::class,'group_mission');
    }
    
    public function users() {
        return $this->belongsToMany(User::class,'role_user');
    }
}
