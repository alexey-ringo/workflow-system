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
class DelnumCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'delnum';

    /**
     * @var string Command Description
     */
    protected $description = 'Отвязка аккаунта пользователя Telegram 
                            от учетной записи системы WorkFlow и удаление пользователя Telegram из системы';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        //Команда боту отображать печатание сообщения
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        
        $user;
        $tlgrmUser;
        
        $telegramMessage = TelegramBot::getWebhookUpdates()['message'];
        $telegramUser = TelegramUser::where('id', $telegramMessage['from']['id'])->first();
        
        //Log::info('пользователь TelegramUser привязанный к user', ['$user->telegramUser' => $user]);
        if($telegramUser) {
            if($telegramUser->user) {
                $user = $telegramUser->user;
                $tlgrmUser = $telegramUser;
                $telegramUser->delete();
                $this->replyWithMessage(['text' => 'Уважаемый пользователь Telegram: ' . 
                $tlgrmUser->first_name . ' Ваш аккаунт Telegram был успешно отвязан от настроек пользователя системы WorkFlow с телефонным номером: ' . 
                $user->phone . ' и удален из системы. При необходимости повторной привязки используйте комманду /regnum 7xxxxxxxxxx']);
            }
            else {
                $this->replyWithMessage(['text' => 'Уважаемый пользователь Telegram ' . $telegramMessage['from']['first_name'] . 
                                                    ' Ваш аккаунт в Telegram не был ранее привязан к учетной записи системы WorkFlow. 
                                                    Соответственно - что бы от чего то отвязаться - нужно сперва к этому чему то сначала привязаться!']);
            }
        }
        else {
            $this->replyWithMessage(['text' => 'Уважаемый пользователь Telegram ' . $telegramMessage['from']['first_name'] . 
                                                ' Ваш аккаунт в Telegram не был ранее привязан к учетной записи системы WorkFlow. 
                                                Соответственно - что бы от чего то отвязаться - нужно сперва к этому чему то привязаться! 
                                                Зарегистрируйтесь в системе с помощью команды /regnum 7xxxxxxxxxx, а потом уже можете и отвязываться ))']);
        }
    }
}
