<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class FakeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|View
    {
        if (App::environment('local', 'testing')) {
            $user = User::findOrFail($request->id);
            $user->update(['login_at' => now()]);

            if (! $user->active) {
                return view('blocked');
            }

            Auth::login($user);
            Session::regenerate();

            return redirect()->intended('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): RedirectResponse
    {
        if (App::environment('local', 'testing')) {
            Auth::logout();
            Session::flush();

            return redirect('/');
        }
    }
}
