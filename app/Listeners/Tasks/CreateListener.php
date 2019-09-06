<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\onCreateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;
use TelegramBot;
use Exception;
use ErrorException;
use App\Exceptions\WorkflowException;
use Log;

class CreateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  onCreateEvent  $event
     * @return void
     */
    public function handle(onCreateEvent $event)
    {
        $sendText = 'В Вашу очередь "' . $event->task->process_name . 
                    '" поступила вновь созданная заявка № ' . $event->task->task . 
                    ' "' . $event->task->title . '" ' . 
	                ' "' . $event->task->description . '". Заявка была создана пользователем ' . 
	                $event->task->creating_user_name;
	    
	    
	    $noticedUsers = User::getUsersByProcess($event->task->process_id);
	                   
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
	                if ($exception instanceof ErrorException) {
                        $e = new WorkflowException;
                        $e->report($exception);
                    }
                    else {
	                    report($exception);
                    }
	                return false;
	            }
	        }
	    }
        
        /*
        Log::info('Create new task!', [
                                            'TaskNum' => $event->task,
                                            'NextSeq' => $event->sequence,
                                            'Title' => $event->title,
                                            'Description' => $event->description,
                                            'UserId' => $event->user_id,
                                            'UserName' => $event->user_name,
                                            'MissionId' => $event->mission_id,
                                            'MissionName' => $event->mission_name,
                                            'Deadline' => $event->deadline
                                            ]);
        */
    }
}
