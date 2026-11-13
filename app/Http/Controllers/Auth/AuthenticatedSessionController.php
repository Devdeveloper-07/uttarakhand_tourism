<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $pendingBooking = $request->session()->get('booking.pending');

        if (auth()->user()->role === 'admin') {
            $request->session()->forget(['booking.pending', 'url.intended']);

            return redirect()->route('admin.dashboard');
        }

        if (($pendingBooking['type'] ?? null) === 'hotel' && is_numeric($pendingBooking['id'] ?? null)) {
            return redirect()->route('hotel.details', (int) $pendingBooking['id']);
        }

        if (($pendingBooking['type'] ?? null) === 'package' && is_numeric($pendingBooking['id'] ?? null)) {
            return redirect()->route('package.details', (int) $pendingBooking['id']);
        }

        return redirect()->route('user.dashboard');
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
