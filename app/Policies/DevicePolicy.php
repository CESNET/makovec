<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;

class DevicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->admin || $user->manager || $user->categories()->pluck('type')->count();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Device $device): bool
    {
        return $user->admin || $user->manager || $user->categories()->pluck('type')->contains($device->category->type);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->admin || $user->categories()->pluck('type')->count();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Device $device): bool
    {
        return $user->admin || $user->categories()->pluck('type')->contains($device->category->type);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Device $device): bool
    {
        return $user->admin || $user->categories()->pluck('type')->contains($device->category->type);
    }
}
