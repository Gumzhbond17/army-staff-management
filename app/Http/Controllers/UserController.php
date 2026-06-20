<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

#[Middleware('auth')]
#[Middleware('role:admin')]
class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'role'      => ['required', 'in:admin,normal'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'ເພີ່ມຂໍ້ມູນຜູ້ໃຊ້ສຳເລັດແລ້ວ');
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email,' . $user->id],
            'password'  => ['nullable', 'string', 'min:8'],
            'role'      => ['required', 'in:admin,normal'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Only update password if a new one was provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້ສຳເລັດແລ້ວ');
    }

    /**
     * Reset a user's password (admin action).
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update(['password' => $request->input('new_password')]);

        return redirect()->route('users.index')
            ->with('success', 'ຣີເຊັດລະຫັດຜ່ານຂອງ ' . $user->name . ' ສຳເລັດແລ້ວ');
    }

    /**
     * Remove a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'ທ່ານບໍ່ສາມາດລຶບບັນຊີຂອງຕົນເອງໄດ້']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'ລຶບຂໍ້ມູນຜູ້ໃຊ້ສຳເລັດແລ້ວ');
    }
}
