<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $profile = StudentProfile::where('user_id', $user->id)->first();

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'nisn'      => ['nullable', 'string', 'max:50'],
            'kelas'     => ['nullable', 'string', 'max:50'],
            'tgl_lahir' => ['nullable', 'date'],
            'telp'      => ['nullable', 'string', 'max:20'],
            'alamat'    => ['nullable', 'string'],
            'wali'      => ['nullable', 'string', 'max:255'],
            'telp_wali' => ['nullable', 'string', 'max:20'],
        ]);

        $profile = StudentProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            $profile = new StudentProfile();
            $profile->user_id = $user->id;
        }

        $profile->nisn = $data['nisn'] ?? null;
        $profile->kelas = $data['kelas'] ?? null;
        $profile->tgl_lahir = $data['tgl_lahir'] ?? null;
        $profile->telp = $data['telp'] ?? null;
        $profile->alamat = $data['alamat'] ?? null;
        $profile->wali = $data['wali'] ?? null;
        $profile->telp_wali = $data['telp_wali'] ?? null;

        $profile->save();

        return Redirect::route('profile.edit')
            ->with('status', 'Profil berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}