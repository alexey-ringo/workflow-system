<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telegram extends Model
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
