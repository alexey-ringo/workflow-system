<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Group;
use App\Models\User;
use App\Models\Task;

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
    
    public function tasks() {
        return $this->hasMany(Task::class,'mission_id');
    }
    
    /*
    public static function scopeMissionsForUser($query, User $user) {
        return self::with('groups.users')
                        ->whereHas('groups.users', function($query) use($user) {
                                $query->where('groups.users.id', $user->id);
                        })->get();
    }
    */
    
    //----------------------------------------------------------//
    
    
    public function scopePrevMissionName(Builder $query): string
    {
        $mission =  $query->where('sequence', $this->sequence - 1)->first();
        return $mission ? $mission->name : $this->name;
    }
    
    public function scopeNextMissionName(Builder $query): string
    {
        $mission =  $query->where('sequence', $this->sequence + 1)->first();
        return $mission ? $mission->name : $this->name;
    }
    
    public function scopePrevMissionId(Builder $query): int
    {
        $mission =  $query->where('sequence', $this->sequence)->first();
        return $mission ? $mission->id : $this->id;
    }
    
    public function scopeNextMissionId(Builder $query): int
    {
        $mission =  $query->where('sequence', $this->sequence + 1)->first();
        return $mission ? $mission->id : $this->id;
    }
    
    public function scopeIsSequenceFirst(Builder $query): bool
    {
        if($this->sequence == 2) {
            return true;
        }
        if(!$query->where('sequence', $this->sequence - 1)->first()) {
            return true;
        }
        return false;
    }
    
    public function scopeIsSequenceLast(Builder $query): bool
    {
        if(!$query->where('sequence', $this->sequence + 1)->first()) {
            return true;
        }
        return false;
    }
    
}
