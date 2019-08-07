<?php

namespace App\Models;

use App\Models\Route;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'routes';
    protected $fillable = [
        'name', 'value', 'description', 'in_use'
    ];
    
    public function mission() {
      return $this->hasOne(Route::class, 'route_id', 'id');
    }
}
