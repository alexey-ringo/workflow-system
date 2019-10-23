<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Task;
use App\Models\Customer;
use App\Models\Tariff;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $fillable = [
        'contract_num', 'customer_id', 'tariff_id'
    ];
    
    public function tasks() {
      return $this->hasMany(Task::class, 'contract_id', 'id');
    }
    
    public function customer() {
        //return $this->belongsTo(Customer::class, 'id');
        return $this->belongsTo(Customer::class);
    }
    
    public function tariff() {
        //return $this->belongsTo(Tariff::class, 'id');
        return $this->belongsTo(Tariff::class);
    }
}
