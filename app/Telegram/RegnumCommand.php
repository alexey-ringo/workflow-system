<?php

namespace App\Telegram;

use TelegramBot;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Models\User;
use App\Models\TelegramUser;

use Log;

/**
 * Class HelpCommand.
 */
class RegnumCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'regnum';

    /**
     * @var string Command Description
     */
    protected $description = 'Привязка телефонного номера получателя сообщений Telegram к учетной записи системы WorkFlow';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        //Команда боту отображать печатание сообщения
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        
        $telegramMessage = TelegramBot::getWebhookUpdates()['message'];
        //Log::info('telegramMessage[text]', ['telegramMessage' => $telegramMessage['text']]);
        $receivedPhoneNum = $this->getArguments();
        $user = User::where('phone', $receivedPhoneNum)->first();
        
        if($user) {
            $telegramUser = TelegramUser::create(json_decode($telegramMessage['from'], true));
            $telegramUser->user_id = $user->id;
            $telegramUser->save();
            $this->replyWithMessage(['text' => 'Телефон: ' . $user->phone . ' успешно привязан к настройкам пользователя ' . $user->email]);
        }
        else {
            $this->replyWithMessage(['text' => 'Вот телефонный номер, который Вы ввели: ' . $receivedPhoneNum . ' . Пользователь с таким телефонным номером отсутствует в базе нашего CRM. Попробуйте еще раз ввести номер повнимательней']);
        }
        
        
        
        //Метод для получения обновлений от бота
        //$telegramUser = \TelegramBot::getWebhookUpdates()['message'];
        //$text = sprintf('/%s - %s'.PHP_EOL, 'Ваш номер чата', $telegramUser['from']['id']);
        
        
        //$text .= sprintf('/%s - %s'.PHP_EOL, 'Ваше имя пользователя в Телеграм', $telegramUser['from']['username']);
        
        
        
        
        
        

        //$this->replyWithMessage(compact('text'));
    }
}
