<?php

namespace App\Events\Tasks;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\Task;
use App\Models\User;
use App\Models\Mission;

class onUpdateEvent/* implements ShouldBroadcast*/
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $task;
    public $newTask;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task, Task $newTask)
    {
        $this->task = $task;
        $this->newTask = $newTask;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
        //return ['new-action'];
    }
}
