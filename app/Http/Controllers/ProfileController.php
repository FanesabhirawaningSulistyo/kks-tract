<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the profile page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'job_role' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2000'
        ]);

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $validated['foto'] = $request->file('foto')->store('users', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Profile berhasil diupdate!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('profile.index')
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Password berhasil diubah!');
    }

    /**
     * Deactivate user account.
     */
    public function deactivate(Request $request)
    {
        $request->validate([
            'accountActivation' => 'required|accepted'
        ], [
            'accountActivation.accepted' => 'Anda harus mencentang konfirmasi'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Delete user photo if exists
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        // Logout user
        Auth::logout();

        // Delete user account
        $user->delete();

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dihapus');
    }
}
