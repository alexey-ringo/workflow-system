<?php

namespace App\Listeners\Customer;

use App\Events\Customer\onUpdateEvent;
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
     * @param  onUpdateEvent  $event
     * @return void
     */
    public function handle(onUpdateEvent $event)
    {
        //
    }
}
