<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index()
    {
        // Fetch all admins excluding superadmins themselves
        $admins = User::whereIn('role', ['admin', 'admin_bengkel', 'admin_perpus'])
            ->orderBy('role')
            ->get();

        return view('superadmin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('superadmin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email'    => 'nullable|email|max:255|unique:users,email',
            'role'     => 'required|in:admin_bengkel,admin_perpus',
            'password' => ['required', 'string', Password::min(6), 'confirmed'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('superadmin.admins.index')->with('success', "Akun Admin {$validated['name']} berhasil dibuat.");
    }

    public function edit(User $admin)
    {
        if (!in_array($admin->role, ['admin', 'admin_bengkel', 'admin_perpus'])) {
            return redirect()->route('superadmin.admins.index')->with('error', 'Hanya dapat mengedit akun Admin.');
        }

        return view('superadmin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if (!in_array($admin->role, ['admin', 'admin_bengkel', 'admin_perpus'])) {
            return redirect()->route('superadmin.admins.index')->with('error', 'Hanya dapat mengedit akun Admin.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $admin->id,
            'email'    => 'nullable|email|max:255|unique:users,email,' . $admin->id,
            'role'     => 'required|in:admin_bengkel,admin_perpus',
        ]);

        $admin->update([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'string', Password::min(6), 'confirmed'],
            ]);
            $admin->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('superadmin.admins.index')->with('success', "Akun Admin {$validated['name']} berhasil diperbarui.");
    }

    public function destroy(User $admin)
    {
        if (!in_array($admin->role, ['admin', 'admin_bengkel', 'admin_perpus'])) {
            return redirect()->route('superadmin.admins.index')->with('error', 'Hanya dapat menghapus akun Admin.');
        }

        $nama = $admin->name;
        $admin->delete();

        return redirect()->route('superadmin.admins.index')->with('success', "Akun Admin {$nama} berhasil dihapus.");
    }

    public function changePassword(Request $request, User $user)
    {
        // Prevent modifying non-admin users
        if (!in_array($user->role, ['admin', 'admin_bengkel', 'admin_perpus'])) {
            return back()->with('error', 'Aksi ditolak. Hanya dapat mengubah password akun Admin.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', Password::min(6), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        $roleLabel = $user->role === 'admin_perpus' ? 'Admin Perpustakaan' : 'Admin Bengkel';

        return back()->with('success', "Password untuk {$roleLabel} (\"{$user->name}\") berhasil diperbarui.");
    }
}
