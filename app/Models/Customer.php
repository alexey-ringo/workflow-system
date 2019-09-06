<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Phone;
use App\Models\Contract;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'name', 'second_name', 'surname', 'region', 'city', 'street', 'building', 'flat', 'email', 'description'
    ];
    
    public function phones() {
      return $this->hasMany(Phone::class, 'customer_id', 'id');
    }
    
    public function contracts() {
      return $this->hasMany(Contract::class, 'customer_id', 'id');
    }
}
