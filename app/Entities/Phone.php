<?php

namespace App\Entities;

class Phone extends AbstractEntity
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
