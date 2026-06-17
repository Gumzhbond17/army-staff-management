<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user    = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        return view('profiles.index', compact('user', 'profile'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'     => ['nullable', 'string', 'max:20'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'bio'       => ['nullable', 'string', 'max:1000'],
            'photo'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $profileData = $request->only('phone', 'job_title', 'bio');

        if ($request->hasFile('photo')) {
            $existing = $user->profile?->photo;
            if ($existing) {
                Storage::disk('public')->delete($existing);
            }
            $profileData['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return back()->with('profile_success', 'ອັບເດດໂປຣຟາຍສຳເລັດ');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'ລະຫັດຜ່ານປັດຈຸບັນບໍ່ຖືກຕ້ອງ'])
                ->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('password_success', 'ປ່ຽນລະຫັດຜ່ານສຳເລັດ');
    }
}
