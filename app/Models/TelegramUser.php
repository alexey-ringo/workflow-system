<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TelegramUser extends Model
{
    protected $table = 'telegram_users';
    protected $primaryKey = 'tlgrmuser';
    public $incrementing = 'tlgrmuser';
    
    //Нет запрещенных для автозаполнения полей
    protected $guarded = [];
    
    public function user() {
      return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
