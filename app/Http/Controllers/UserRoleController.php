<?php

namespace App\Http\Controllers;

use App\Mail\UserRoleChanged;
use App\Mail\YourRoleChanged;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('do-everything');

        if ($request->user()->is($user)) {
            return to_route('users.show', $user)
                ->with('status', __('users.cannot_toggle_your_role'))
                ->with('color', 'red');
        }

        $user->admin = $user->admin ? false : true;
        $user->update();

        Mail::send(new UserRoleChanged($user));
        Mail::send(new YourRoleChanged($user));

        $role = $user->admin ? 'admined' : 'deadmined';
        $color = $user->admin ? 'indigo' : 'yellow';

        return to_route('users.show', $user)
            ->with('status', __("users.{$role}", ['name' => $user->name]))
            ->with('color', $color);
    }
}
