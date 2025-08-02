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
        try {
            $request->authenticate();

            $request->session()->regenerate();

            // Log successful login
            Log::info('User logged in successfully', [
                'user_id' => auth()->id(),
                'email' => auth()->user()->email,
                'ip' => $request->ip()
            ]);

            // Redirect based on user role
            if (auth()->user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } else {
                return redirect()->intended(route('employee.dashboard', absolute: false));
            }
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $user = auth()->user();

            // Log logout attempt
            if ($user) {
                Log::info('User logged out', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip()
                ]);
            }

            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'Başarıyla çıkış yaptınız.');
        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);

            // Force logout even if there's an error
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'Çıkış yapıldı.');
        }
    }
}
