<?php

namespace App\Providers;

use App\Events\DeviceCreated;
use App\Events\DeviceDeleted;
use App\Events\DeviceUpdated;
use App\Listeners\LogDeviceCreated;
use App\Listeners\LogDeviceDeleted;
use App\Listeners\LogDeviceUpdated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        DeviceCreated::class => [
            LogDeviceCreated::class,
        ],
        DeviceUpdated::class => [
            LogDeviceUpdated::class,
        ],
        DeviceDeleted::class => [
            LogDeviceDeleted::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
