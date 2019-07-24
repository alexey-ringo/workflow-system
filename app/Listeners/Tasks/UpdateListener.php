<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\onUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;
use TelegramBot;
use Exception;
use App\Exceptions\WorkflowException;
use Log;

class UpdateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  onUpdateEvent  $event
     * @return void
     */
    public function handle(onUpdateEvent $event)
    {
        $sendText;
        
        if($event->newTask->sequence > $event->task->sequence) {
            $sendText = 'В Вашу очередь "' . $event->newTask->mission_name . 
                    '" поступила новая заявка № ' . $event->newTask->task . 
                    ' "' . $event->newTask->title . '" ' . 
	                ' "' . $event->newTask->description . '". Заявка была передана из очереди "' .
	                $event->task->mission_name . '" пользователем ' . $event->task->closing_user_name;
        }
        else {
            $sendText = 'В Вашу очередь "' . $event->newTask->mission_name . 
                    '" была возвращена обратно заявка № ' . $event->newTask->task . 
                    ' "' . $event->newTask->title . '" ' . 
	                ' "' . $event->newTask->description . '". Заявка была возвращена из очереди "' .
	                $event->task->mission_name . '" пользователем ' . $event->task->closing_user_name;
        }
	    
	    
	    $noticedUsers = User::getUsersByMission($event->newTask->mission_id);
	                   
	    foreach($noticedUsers as $noticedUser) {
	        if($noticedUser->telegramUser) {
	            try {
                    TelegramBot::sendMessage([
	                    'chat_id' => $noticedUser->telegramUser->id, 
	                    'text' => $sendText, 
	                    //'reply_markup' => $reply_markup
                    ]);
	            }
	            catch(Exception $exception) {
	                report($exception);
	                return false;
	            }
	        }
	    }
        /*
        Log::info('Update task!', [
                                            'TaskNum' => $event->newTask->task,
                                            'Title' => $event->newTask->title,
                                            'Description' => $event->newTask->description,
                                            'PrevSeq' => $event->task->sequence,
                                            'NextSeq' => $event->newTask->sequence,
                                            'ClosingUserId' => $event->task->user_id,
                                            'ClosingUserName' => $event->task->user_name,
                                            'MissionId' => $event->task->mission_id,
                                            'MissionName' => $event->task->mission_name,
                                            'NewUserId' => $event->newTask->user_id,
                                            'NewUserName' => $event->newTask->user_name,
                                            'NewMissionId' => $event->newTask->mission_id,
                                            'NewMissionName' => $event->newTask->mission_name,
                                            'Deadline' => $event->newTask->deadline
                                            ]);
        */
    }
}
