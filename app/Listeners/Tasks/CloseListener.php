<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\onCloseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;

class CloseListener
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
     * @param  onCloseEvent  $event
     * @return void
     */
    public function handle(onCloseEvent $event)
    {
        Log::info('Close task', [
                                            'TaskNum' => $event->task,
                                            'LastSeq' => $event->task_sequence,
                                            'Title' => $event->title,
                                            'Description' => $event->description,
                                            'ClosingUserId' => $event->user_id,
                                            'ClosingUserName' => $event->user_name,
                                            'LastProcessId' => $event->process_id,
                                            'LastProcessName' => $event->process_name,
                                            'Deadline' => $event->deadline
                                            ]);
    }
}
