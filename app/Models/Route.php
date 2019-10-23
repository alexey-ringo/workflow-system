<?php

namespace App\Models;

use App\Models\Route;
use App\Models\Process;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'routes';
    protected $fillable = [
        'name', 'value', 'description', 'is_active'
    ];
    
    public function processes() {
      return $this->hasMany(Process::class, 'route_id', 'id');
    }
}
