<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Process;
use App\Models\User;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'groups';
    protected $fillable = [
        'title',
    ];
    
    public function processes() {
        return $this->belongsToMany(Process::class,'group_process');
    }
    
    public function users() {
        return $this->belongsToMany(User::class,'group_user');
    }
}
