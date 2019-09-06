<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\Tasks\onCreateEvent' => [
            'App\Listeners\Tasks\CreateListener'
            ],
        'App\Events\Tasks\onUpdateEvent' => [
            'App\Listeners\Tasks\UpdateListener'
            ],
        'App\Events\Tasks\onCloseEvent' => [
            'App\Listeners\Tasks\CloseListener'
            ],
        'App\Events\Customer\onCreateEvent' => [
            'App\Listeners\Customer\CreateListener'
            ],
        'App\Events\Customer\onUpdateEvent' => [
            'App\Listeners\Customer\UpdateListener'
            ],
        'App\Events\Contract\onCreateEvent' => [
            'App\Listeners\Contract\CreateListener'
            ],
        'App\Events\Contract\onUpdateEvent' => [
            'App\Listeners\Contract\UpdateListener'
            ],
        'App\Events\Contract\onCloseEvent' => [
            'App\Listeners\Contract\UpdateListener'
            ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
