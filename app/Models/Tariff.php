<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;
use App\Models\Price;

class Tariff extends Model
{
    protected $table = 'tariffs';
    protected $fillable = [
        'title', 'description', 'sku', 'price', 'is_active'
    ];
    
    public function customers() {
      return $this->hasMany(Customer::class, 'tariff_id', 'id');
    }
}
