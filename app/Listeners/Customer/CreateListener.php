<?php

namespace App\Listeners\Customer;

use App\Events\Customer\onCreateEvent;
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
