<?php

namespace App\Listeners;

use App\Events\DeviceCreated;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogDeviceCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DeviceCreated $event): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'device_id' => $event->device->id,
            'action' => 'create',
            'mac' => $event->device->mac,
            'name' => $event->device->name,
            'description' => $event->device->description,
        ]);
    }
}
