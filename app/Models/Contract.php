<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Task;
use App\Models\Customer;
use App\Models\Price;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $fillable = [
        /*'iteration', 'production', */'contract_num', 'customer_id', 'price_id', 'is_final'
    ];
    
    public function task() {
      return $this->hasMany(Task::class, 'task_id', 'id');
    }
    
    public function customer() {
        //return $this->belongsTo(Customer::class, 'id');
        return $this->belongsTo(Customer::class);
    }
    
    public function price() {
        //return $this->belongsTo(Price::class, 'id');
        return $this->belongsTo(Price::class);
    }
}
