<?php

namespace App\Listeners\Contract;

use App\Events\Contract\onCreateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        //
    }
}
