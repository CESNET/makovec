<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class FakeController extends Controller
{
    public function store(Request $request): RedirectResponse|View
    {
        if (app()->environment(['local', 'testing'])) {
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

    public function destroy(): RedirectResponse
    {
        if (app()->environment(['local', 'testing'])) {
            Auth::logout();
            Session::flush();

            return redirect('/');
        }
    }
}
