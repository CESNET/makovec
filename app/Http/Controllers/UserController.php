<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        $emails = explode(';', $user->emails);
        $categories = Category::all();

        return view('users.show', compact('user', 'emails', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        if (in_array($request->email, explode(';', $user->emails))) {
            $user->update(['email' => $request->email]);
        }

        if ($user->wasChanged()) {
            return to_route('users.show', $user)
                ->with('status', __('users.email_changed'));
        }

        return to_route('users.show', $user);
    }
}
