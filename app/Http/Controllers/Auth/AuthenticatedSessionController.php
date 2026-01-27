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

        $user = auth()->user();

        // Peserta Magang
        if ($user->id_role == 4) {
            return redirect('/peserta/dashboard');
        }

        // Mentor
        if ($user->id_role == 3) {
            return redirect('/mentor/bimbingan');
        }

        // Admin Bidang
        if ($user->id_role == 2) {
            return redirect('/admin-bidang/mentor');
        }

        // Admin Kepegawaian
        if ($user->id_role == 1) {
            return redirect('/admin/verifikasi-berkas');
        }

        return redirect('/');
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
