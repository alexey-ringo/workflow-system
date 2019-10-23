<?php

namespace App\Entities;

class TelegramUser extends AbstractEntity
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
