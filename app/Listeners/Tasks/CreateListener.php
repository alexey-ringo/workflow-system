<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\onCreateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
    }
}
