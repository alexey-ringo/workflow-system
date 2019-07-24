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
class StatusCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'status';

    /**
     * @var string Command Description
     */
    protected $description = 'Статус привязки пользователя Telegram к системе авторизации WorkFlow';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        //Бот набирает (печатает) сообщение
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        
        $telegramMessage = TelegramBot::getWebhookUpdates()['message'];
        $telegramUser = TelegramUser::where('id', $telegramMessage['from']['id'])->first();
        //Log::info('созданный пользователь TelegramUser->user', ['telegramUser->user' => $telegramUser->user]);
        if($telegramUser) {
            if($telegramUser->user) {
                $this->replyWithMessage(['text' => 'Ваш аккаунт в Telegram уже привязан к учетной записи пользователя WorkFlow: ' . 
                                                    $telegramUser->user->email . ' с телефонным номером: ' . $telegramUser->user->phone]);
            }
        }
        else {
            $this->replyWithMessage(['text' => 'Ваш аккаунт в Telegram не привязан ни к одной учетной записи пользователя WorkFlow.']);
        }
    }
}
