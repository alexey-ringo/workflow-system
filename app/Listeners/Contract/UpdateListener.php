<?php

namespace App\Listeners\Contract;

use App\Events\Contract\onCloseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param  onCloseEvent  $event
     * @return void
     */
    public function handle(onCloseEvent $event)
    {
        //
    }
}
