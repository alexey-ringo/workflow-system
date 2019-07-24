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
        $receivedPhoneNum = $this->getArguments();
        $receivedPhoneNum = (int) $receivedPhoneNum;
        $user = User::where('phone', $receivedPhoneNum)->first();
        //Log::info('пользователь TelegramUser привязанный к user', ['$user->telegramUser' => $user]);
        if($user) {
            if(!$user->telegramUser) {
                $receivedUser = json_decode($telegramMessage['from'], true);
                $userId['user_id'] = $user->id;
                //$telegramUser = TelegramUser::create(json_decode($telegramMessage['from'], true));
                $telegramUser = TelegramUser::create(array_merge($receivedUser, $userId));
                if($telegramUser) {
                    $this->replyWithMessage(['text' => 'Телефон: ' . $user->phone . ' успешно привязан к настройкам пользователя системы WorkFlow: ' . $user->email]);
                }
                else {
                    $this->replyWithMessage(['text' => 'Извините, но в настоящее время сервис регистрации недоступен']);
                }
            }
            else {
                $this->replyWithMessage(['text' => 'К пользователю с телефонным номером ' . $receivedPhoneNum . ' . уже привязан аккаунт Telegram: ' . 
                                                    $user->telegramUser->first_name . ' ' . $user->telegramUser->last_name]);
            }
        }
        else {
            $this->replyWithMessage(['text' => 'Вот телефонный номер, который Вы ввели: ' . $receivedPhoneNum . 
                                                ' . Пользователь с таким телефонным номером отсутствует в базе нашей системы WorkFlow. Попробуйте еще раз ввести номер повнимательней']);
        }
    }
}
