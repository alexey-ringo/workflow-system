<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Group;

class Mission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'queue', 'is_super, is_final'
    ];
    
    public function groups() {
        return $this->belongsToMany(Group::class,'group_mission');
    }
}
