<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Group;
use App\Models\User;
use App\Models\Task;
use App\Models\Route;

use Exception;
use ErrorException;
use App\Exceptions\WorkflowException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class Process extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'processes';
    protected $fillable = [
        'route_id', 'title', 'deadline', 'sequence', 'is_super', 'is_final', 'is_active'
    ];
    
    public function groups() {
        return $this->belongsToMany(Group::class,'group_process');
    }
    
    public function tasks() {
        return $this->hasMany(Task::class,'process_id')->where('status', 1);
    }

    public function alltasks() {
        return $this->hasMany(Task::class,'process_id');
    }
    
    public function route() {
        //return $this->belongsTo(Route::class, 'id');
        return $this->belongsTo(Route::class);
    }
    
    /*
    public static function scopeProcessForUser($query, User $user) {
        return self::with('groups.users')
                        ->whereHas('groups.users', function($query) use($user) {
                                $query->where('groups.users.id', $user->id);
                        })->get();
    }
    */
    
    //----------------------------------------------------------//
    
    public static function getProcess(Route $route, int $processSequence): ?self
    {
        return static::where(['route_id' => $route->id, 'sequence' => $processSequence])->first();
    }
    
    
    public function getPrevProcess(int $routeId): ?self
    {
        $processesForRoute = static::where('route_id', $routeId)->get();
        if(!$processesForRoute) {
            return null;
        }
        return $processesForRoute->where('sequence', $this->sequence - 1)->first();
    }
    
    
    public function getNextProcess(int $routeId): ?self
    {
        $processesForRoute = static::where('route_id', $routeId)->get();
        
        if(!$processesForRoute) {
            return null;
        }
        return $processesForRoute->where('sequence', $this->sequence + 1)->first();
    }
    
    
    public function isSequenceFirst(int $routeId): bool
    {
        $processesForRoute = static::where('route_id', $routeId)->get();
        if($this->sequence == 2) {
            return true;
        }
        if(!$processesForRoute->where('sequence', $this->sequence - 1)->first()) {
            return true;
        }
        return false;
    }
    
    
    public function isSequenceLast(int $routeId): bool
    {
        $processesForRoute = static::where('route_id', $routeId)->get();
        if(!$processesForRoute->where('sequence', $this->sequence + 1)->first()) {
            return true;
        }
        return false;
    }
}
