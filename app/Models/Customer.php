<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Phone;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'name', 'second_name', 'surname', 'region', 'city', 'street', 'building', 'flat', 'email', 'description'
    ];
    
    public function phones() {
      return $this->hasMany(Phone::class, 'customer_id');
    }
}
