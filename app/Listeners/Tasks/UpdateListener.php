<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\onUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        //
    }

    /**
     * Handle the event.
     *
     * @param  onUpdateEvent  $event
     * @return void
     */
    public function handle(onUpdateEvent $event)
    {
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
    }
}
