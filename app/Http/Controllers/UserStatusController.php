<?php

namespace App\Http\Controllers;

use App\Mail\UserStatusChanged;
use App\Mail\YourStatusChanged;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class UserStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('do-everything');

        if ($request->user()->is($user)) {
            return to_route('users.show', $user)
                ->with('status', __('users.cannot_toggle_your_status'))
                ->with('color', 'red');
        }

        $user->active = $user->active ? false : true;
        $user->update();

        Mail::send(new UserStatusChanged($user));
        Mail::send(new YourStatusChanged($user));

        $status = $user->active ? 'active' : 'inactive';
        $color = $user->active ? 'green' : 'red';

        return to_route('users.show', $user)
            ->with('status', __("users.{$status}", ['name' => $user->name]))
            ->with('color', $color);
    }
}
