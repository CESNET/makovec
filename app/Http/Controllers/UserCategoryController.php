<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserCategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('do-everything');

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
