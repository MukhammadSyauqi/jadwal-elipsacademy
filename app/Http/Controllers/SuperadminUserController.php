<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuperadminUserController extends Controller
{
    /**
     * Display a listing of user accounts with metrics and selected user inspector.
     */
    public function index(Request $request): View
    {
        $currentUser = $request->user();
        $search = trim((string) $request->query('q', ''));
        $roleFilter = $request->query('role', 'all');

        $query = User::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (in_array($roleFilter, ['admin', 'superadmin'], true)) {
            $query->where('role', $roleFilter);
        }

        $users = $query->orderBy('nama')->get();

        // Metrics calculations
        $totalUsers = User::count();
        $totalSuperadmin = User::where('role', 'superadmin')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        // Selected user for inspector panel
        $selectedUserId = $request->query('selected');
        $selectedUser = null;
        if ($selectedUserId) {
            $selectedUser = $users->firstWhere('id', (int) $selectedUserId);
        }
        if (!$selectedUser && $users->isNotEmpty()) {
            $selectedUser = $users->first();
        }

        return view('superadmin.user.index', [
            'currentUser' => $currentUser,
            'users' => $users,
            'selectedUser' => $selectedUser,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'totalUsers' => $totalUsers,
            'totalSuperadmin' => $totalSuperadmin,
            'totalAdmin' => $totalAdmin,
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,superadmin',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar pada sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role harus berupa admin atau superadmin.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return redirect()
            ->route('superadmin.user.index', ['selected' => $user->id])
            ->with('success', "Akun pengguna '{$user->nama}' berhasil ditambahkan.");
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => 'required|in:admin,superadmin',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role harus berupa admin atau superadmin.',
        ]);

        // Guard: prevent demoting self if sole superadmin
        if ($user->id === $request->user()->id && $validated['role'] !== 'superadmin') {
            $otherSuperadmins = User::where('role', 'superadmin')->where('id', '!=', $user->id)->count();
            if ($otherSuperadmins === 0) {
                return back()->with('error', 'Anda tidak dapat mengubah role diri sendiri menjadi admin karena Anda adalah satu-satunya Superadmin di sistem.');
            }
        }

        $user->update($validated);

        return redirect()
            ->route('superadmin.user.index', ['selected' => $user->id])
            ->with('success', "Data akun pengguna '{$user->nama}' berhasil diperbarui.");
    }

    /**
     * Reset password for the specified user.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('superadmin.user.index', ['selected' => $user->id])
            ->with('success', "Password untuk akun '{$user->nama}' berhasil diperbarui.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        // Guard: cannot delete own account
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri demi keamanan sistem.');
        }

        // Guard: cannot delete the last superadmin
        if ($user->role === 'superadmin') {
            $otherSuperadmins = User::where('role', 'superadmin')->where('id', '!=', $user->id)->count();
            if ($otherSuperadmins === 0) {
                return back()->with('error', 'Tidak dapat menghapus akun ini karena merupakan satu-satunya Superadmin di sistem.');
            }
        }

        $nama = $user->nama;
        $user->delete();

        return redirect()
            ->route('superadmin.user.index')
            ->with('success', "Akun pengguna '{$nama}' berhasil dihapus.");
    }
}
