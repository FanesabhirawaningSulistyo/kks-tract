<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

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

            // Redirect ke dashboard setelah login berhasil
            $user = Auth::user();

            $redirect = match ($user->role) {
                'PM'       => route('dashboard.pm'),
                'karyawan' => route('dashboard.pegawai'),
                'klien'    => route('dashboard.klien'),
                default    => route('dashboard'),   // admin
            };

            return redirect()->intended($redirect);
        } catch (ValidationException $e) {
            // Jika autentikasi gagal, redirect kembali dengan pesan error
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput($request->only('email'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke login dengan script untuk clear localStorage jika tidak remember
        return redirect('/')->with('clear_storage', true);
    }
}
