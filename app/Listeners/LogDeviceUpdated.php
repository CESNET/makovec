<?php

namespace App\Listeners;

use App\Events\DeviceUpdated;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogDeviceUpdated
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
    public function handle(DeviceUpdated $event): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'device_id' => $event->device->id,
            'action' => 'update',
            'mac' => $event->device->mac,
            'name' => $event->device->name,
            'description' => $event->device->description,
        ]);
    }
}
