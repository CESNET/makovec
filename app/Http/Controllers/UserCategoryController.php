<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserCategoryController extends Controller
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
                ->with('status', __('users.cannot_tweak_your_roles'))
                ->with('color', 'red');
        }

        $user->categories()->sync($request->categories);

        return to_route('users.show', $user)
            ->with('status', __('users.roles_updated'));
    }
}
