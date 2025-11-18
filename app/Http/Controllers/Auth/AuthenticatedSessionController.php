<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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

        $request->session()->regenerate();

        // Log diagnostic info to help debug intermittent redirect issues
        try {
            Log::info('Login redirect check', [
                'user_id' => Auth::user()?->id ?? null,
                'is_admin' => Auth::user()?->is_admin ?? null,
                'url_intended' => $request->session()->get('url.intended'),
            ]);
        } catch (\Throwable $e) {
            // ignore logging failures
        }

        // If the authenticated user is admin, send to admin dashboard (priority)
        if (Auth::user() && Auth::user()->is_admin) {
            return redirect()->route('admin.index');
        }

        // If there was an intended URL (user tried to access a protected page), honor it for regular users.
        if ($request->session()->has('url.intended')) {
            return redirect()->intended();
        }

        // Default redirect for regular users
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
