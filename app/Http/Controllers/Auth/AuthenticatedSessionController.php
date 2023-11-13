<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use Illuminate\Support\Facades\Cache;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        $credentials = $this->validate(request(),
            [
                'email' => 'required',
                'password' => 'required',
            ]
        );

        // A felhasználó jogosultságának ellenőrzése Spatie-vel
        if ($user->hasRole('adminTest')) {
            $request->session()->regenerate();
            $token = auth('api')->attempt($credentials);

            // Token mentése cache-be
            Cache::put('token', $token);
            
            return redirect()->intended(RouteServiceProvider::HOME)
                   ->with(['success' => 'Van adminTest jogosúltsága!']);
        }

        return redirect()->intended(RouteServiceProvider::HOME)
               ->with(['danger_message' =>'Nincs adminTest jogosúltsága!']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        // Token törlése cache-ből
        Cache::forget('token');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}