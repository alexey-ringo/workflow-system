<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Phone;

class Customer extends Model
{
    //
    public function phones() {
      return $this->hasMany(Phone::class, 'customer_id');
    }
}
