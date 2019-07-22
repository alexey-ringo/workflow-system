<?php

namespace App\Http\Controllers\Bot;

use App\Models\TelegramSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Bot\TelegramSettingCollection;
use App\Http\Resources\Bot\TelegramSettingResource;
use App\Http\Resources\Bot\TelegramSettingCustomResource;

class TelegramSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(Telegram::getTelegramSettings());
        //return new TelegramCollection(Telegram::getTelegramSettings());
        //return TelegramCustomResource::collection(Telegram::getTelegramSettings());
        return new TelegramSettingCollection(TelegramSetting::all());
    }
    
    public function store(Request $request) {
        TelegramSetting::where('key', '!=', NULL)->delete();
        
        foreach($request->all() as $key => $value) {
            $setting = new TelegramSetting;
            $setting->key = $key;
            $setting->value = $value;
            $setting->save();
            return new TelegramSettingCollection(TelegramSetting::all()); 
            
        }
        
    }
    
    public function setWebhook(Request $request)
    {
        $result = $this->sendTelegramData('setwebhook', [
                'query' => ['url' => $request->url . '/' . \TelegramBot::getAccessToken()]
            ]);
            
        return response()->json(['setwebhook' => $result]);
    }
    
    public function suspendWebhook(Request $request)
    {
        $result = $this->sendTelegramData('setwebhook', [
                'query' => ['url' => $request->url . '/' . '']
            ]);
            
        return response()->json(['suspendwebhook' => $result]);
    }
    
    public function getwebhookinfo(Request $result)
    {
        $result = $this->sendTelegramData('getWebhookInfo');
        
        return response()->json(['getwebhookinfo' => $result]);
    }
    
    
    public function sendTelegramData($route = '', $params = [], $method = 'POST') 
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.telegram.org/bot' . \TelegramBot::getAccessToken() . '/']);
        $result = $client->request($method, $route, $params);
        
        return (string)$result->getBody();
    }
}
