<?php

namespace App\Http\Controllers;

use App\Mail\UserSubroleChanged;
use App\Mail\YourSubroleChanged;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserSubroleController extends Controller
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

        $user->manager = $user->manager ? false : true;
        $user->update();

        Mail::send(new UserSubroleChanged($user));
        Mail::send(new YourSubroleChanged($user));

        $role = $user->manager ? 'managered' : 'demanagered';
        $color = $user->manager ? 'indigo' : 'yellow';

        return to_route('users.show', $user)
            ->with('status', __("users.{$role}", ['name' => $user->name]))
            ->with('color', $color);
    }
}
