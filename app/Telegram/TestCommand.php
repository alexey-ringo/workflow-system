<?php

namespace App\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

/**
 * Class HelpCommand.
 */
class TestCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'test';

    /**
     * @var string Command Description
     */
    protected $description = 'Test command, Get a list of commands';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        //Бот набирает (печатает) сообщение
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        
        $user = \App\Models\User::find(1);
        
        $this->replyWithMessage(['text' => 'Почта администратора системы документооборота: ' . $user->email]);
        
        //Метод для получения обновлений от бота
        $telegramUser = \TelegramBot::getWebhookUpdates()['message'];
        $text = sprintf('/%s - %s'.PHP_EOL, 'Ваш номер чата', $telegramUser['from']['id']);
        
        
        //$text .= sprintf('/%s - %s'.PHP_EOL, 'Ваше имя пользователя в Телеграм', $telegramUser['from']['username']);
        
        
        
        
        $keyboard = [
            ['7', '8', '9'],
            ['4', '5', '6'],
            ['1', '2', '3'],
                ['0']
        ];

        $reply_markup = \TelegramBot::replyKeyboardMarkup([
	        'keyboard' => $keyboard, 
	        'resize_keyboard' => true, 
	        'one_time_keyboard' => true
        ]);

        $response = \TelegramBot::sendMessage([
	        'chat_id' => $telegramUser['from']['id'], 
	        'text' => 'Hello World', 
	        'reply_markup' => $reply_markup
        ]);
        
        //id последнего отправленного сообщения
        $messageId = $response->getMessageId();
        

        $this->replyWithMessage(compact('text'));
    }
}
