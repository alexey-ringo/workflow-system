<?php

namespace App\Entities;

class Telegram extends AbstractEntity
{
    protected $table = 'telegrams';
    public $timestamps = false;
    
    public static function getTelegramSettings($key = null) {
        $settings = $key ? self::where('key', $key)->first() : self::get();
        
        $collect = collect();
        foreach($settings as $setting) {
            $collect->put($setting->key, $setting->value);
        }
        return $collect;
    }
}
