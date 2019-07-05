<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Task extends Model
{
    protected $fillable = [
        'task', 'sequence', 'title', 'description', 'status', 'mission_id', 'creating_user_id', 'mission_name', 'creating_user_name', 'creating_user_email', 'closing_user_name', 'closing_user_email', 'deadline' 
    ];
    
    public function user() {
      return $this->belongsTo(User::class, 'id');
    }
    
    public function mission() {
      return $this->belongsTo(Mission::class, 'id');
    }
    
    //---------------------------------------------------------//
    
}
