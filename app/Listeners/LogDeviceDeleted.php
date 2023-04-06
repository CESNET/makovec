<?php

namespace App\Listeners;

use App\Events\DeviceDeleted;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogDeviceDeleted
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
    public function handle(DeviceDeleted $event): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'device_id' => $event->device->id,
            'action' => 'delete',
            'mac' => $event->device->mac,
            'name' => $event->device->name,
            'description' => $event->device->description,
        ]);
    }
}
