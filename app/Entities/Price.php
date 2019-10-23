<?php

namespace App\Entities;

class Price extends AbstractEntity
{
    protected $table = 'prices';
    protected $fillable = [
        'is_material', 'name', 'description', 'sku', 'price', 'status'
    ];
    
    public function contract() {
      return $this->hasOne(Contract::class, 'contract_id', 'id');
    }
}
