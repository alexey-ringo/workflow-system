<?php

namespace App\Entities;

class Task extends AbstractEntity
{
  protected $table = 'tasks';
  
  protected $fillable = [
      'task', 'task_sequence', 'route', 'process_sequence', 'title', 'description', 
      'status', 'process_id', 'contract_id', 'creating_user_id', 'closing_user_id',
      'process_name', 'process_slug',
      'creating_user_name', 'creating_user_email', 'closing_user_name', 'closing_user_email', 
      'deadline' 
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