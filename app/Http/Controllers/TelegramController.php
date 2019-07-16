<?php

namespace App\Http\Controllers;

use App\Models\Telegram;
use Illuminate\Http\Request;
use App\Http\Resources\TelegramCollection;
use App\Http\Resources\TelegramResource;
use App\Http\Resources\TelegramCustomResource;

class TelegramController extends Controller
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
        return new TelegramCollection(Telegram::all());
    }
    
    public function store(Request $request) {
        Telegram::where('key', '!=', NULL)->delete();
        
        foreach($request->all() as $key => $value) {
            $setting = new Telegram;
            $setting->key = $key;
            $setting->value = $value;
            $setting->save();
            return new TelegramCollection(Telegram::all()); 
            
        }
        
    }
    
    public function setWebhook(Request $request)
    {
        $result = $this->sendTelegramData('setwebhook', [
                'query' => ['url' => $request->url . '/' . \TelegramBot::getAccessToken()]
            ]);
            
        return response()->json(['setwebhook' => $result]);
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
