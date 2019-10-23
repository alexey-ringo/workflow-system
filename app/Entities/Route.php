<?php

namespace App\Entities;

class Route extends AbstractEntity
{
    protected $table = 'routes';
    protected $fillable = [
        'name', 'value', 'description', 'in_use'
    ];
    
    public function processes() {
      return $this->hasMany(Process::class, 'route_id', 'id');
    }
}