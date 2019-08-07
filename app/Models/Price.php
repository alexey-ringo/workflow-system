<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Contract;

class Price extends Model
{
    protected $table = 'prices';
    protected $fillable = [
        'is_material', 'name', 'description', 'sku', 'price', 'status'
    ];
    
    public function contract() {
      return $this->hasOne(Contract::class, 'contract_id', 'id');
    }
}
