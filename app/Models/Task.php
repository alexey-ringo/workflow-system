<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Mission;
use App\Models\Comment;
use App\Models\Contract;

class Task extends Model
{
  protected $table = 'tasks';
  
  protected $fillable = [
      'task', 'task_seq', 'sequence', 'title', 'description', 'status', 'mission_id', 'customer_id', 'creating_user_id', 'mission_name', 'creating_user_name', 'creating_user_email', 'closing_user_name', 'closing_user_email', 'deadline' 
  ];
    
  public function user() {
    //return $this->belongsTo(User::class, 'id');
    return $this->belongsTo(User::class);
  }
    
  public function mission() {
    //return $this->belongsTo(Mission::class, 'id');
    return $this->belongsTo(Mission::class);
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
