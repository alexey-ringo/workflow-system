<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;

class Phone extends Model
{
    protected $table = 'phones';
    protected $fillable = [
        'customer_id', 'phone'
    ];
    
    public function customer() {
        //return $this->belongsTo(Customer::class, 'id');
        return $this->belongsTo(Customer::class);
    }
}
