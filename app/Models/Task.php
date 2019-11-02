<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Process;
use App\Models\Comment;
use App\Models\Contract;

class Task extends Model
{
  protected $table = 'tasks';
  
  protected $fillable = [
      'task', 'task_sequence', 'route', 'process_sequence', 'is_contractable', 'title', 
      'status', 'process_id', 'contract_id', 'tariff_id', 'creating_user_id', 'closing_user_id',      
      'creating_user_name', 'creating_user_email', 'closing_user_name', 'closing_user_email', 
      'is_expiried' 
       
  ];
    
  public function user() {
    //return $this->belongsTo(User::class, 'id');
    return $this->belongsTo(User::class);
  }
    
  public function process() {
    //return $this->belongsTo(Process::class, 'id');
    return $this->belongsTo(Process::class);
  }
  
  public function contract() {
    //return $this->belongsTo(Contract::class, 'id');
    return $this->belongsTo(Contract::class);
  }
  
  public function comments() {
    return $this->hasMany(Comment::class);
  }
    
  //---------------------------------------------------------//
    
}
