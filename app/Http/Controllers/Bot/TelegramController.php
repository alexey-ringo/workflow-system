<?php

namespace App\Http\Controllers\Bot;

use App\Models\TelegramUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Http\Resources\Bot\TelegramCollection;
//use App\Http\Resources\Bot\TelegramResource;
//use App\Http\Resources\Bot\TelegramCustomResource;
use TelegramBot;

class TelegramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function webHook()
    {
        //Метод для получения обновлений от бота
        //$telegramUser = \TelegramBot::getWebhookUpdates()['message'];
        //if(!TelegramUser::find($telegramUser['from']['id'])) {
        //    TelegramUser::create(json_decode($telegramUser['from'], true));
        //}
        TelegramBot::commandsHandler(true);
    }
}
