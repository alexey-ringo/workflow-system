<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\User;

class Mission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'sequence', 'is_super, is_final'
    ];
    
    public function groups() {
        return $this->belongsToMany(Group::class,'group_mission');
    }
    
    /*
    public static function scopeMissionsForUser($query, User $user) {
        return self::with('groups.users')
                        ->whereHas('groups.users', function($query) use($user) {
                                $query->where('groups.users.id', $user->id);
                        })->get();
    }
    */
}
