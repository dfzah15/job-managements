<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // ============================================
    // INDEX - Menampilkan daftar user
    // ============================================
    public function index(Request $request)
    {
        // Cek akses di awal method
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = User::query();

        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%");
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // ============================================
    // CREATE - Form tambah user
    // ============================================
    public function create()
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('users.create');
    }

    // ============================================
    // STORE - Simpan user baru
    // ============================================
    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,manajer,teknisi,user',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_telepon' => $request->no_telepon,
            'is_active' => true,
        ]);

        // Jika user adalah admin, assign semua jobdesk otomatis
        if ($request->role == 'admin') {
            $allJobdesks = Jobdesk::where('is_active', true)->get();
            $syncData = [];
            foreach ($allJobdesks as $jobdesk) {
                $syncData[$jobdesk->id] = [
                    'can_view' => true,
                    'can_create' => true,
                    'can_edit' => true,
                    'can_delete' => true,
                    'can_export' => true,
                    'can_approve' => true,
                ];
            }
            $user->jobdesks()->sync($syncData);
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('users/foto', 'public');
            $user->update(['foto' => $path]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    // ============================================
    // SHOW - Detail user
    // ============================================
    public function show(User $user)
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        $user->load('jobdesks');
        return view('users.show', compact('user'));
    }

    // ============================================
    // EDIT - Form edit user
    // ============================================
    public function edit(User $user)
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $jobdesks = Jobdesk::where('is_active', true)->get();
        $user->load('jobdesks');
        
        return view('users.edit', compact('user', 'jobdesks'));
    }

    // ============================================
    // UPDATE - Update user
    // ============================================
    public function update(Request $request, User $user)
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manajer,teknisi,user',
            'no_telepon' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $oldRole = $user->role;
        $user->update($request->only(['name', 'email', 'role', 'no_telepon', 'is_active']));

        // Jika role diubah menjadi admin
        if ($request->role == 'admin' && $oldRole != 'admin') {
            $allJobdesks = Jobdesk::where('is_active', true)->get();
            $syncData = [];
            foreach ($allJobdesks as $jobdesk) {
                $syncData[$jobdesk->id] = [
                    'can_view' => true,
                    'can_create' => true,
                    'can_edit' => true,
                    'can_delete' => true,
                    'can_export' => true,
                    'can_approve' => true,
                ];
            }
            $user->jobdesks()->sync($syncData);
        }

        // Jika role diubah dari admin ke non-admin
        if ($oldRole == 'admin' && $request->role != 'admin') {
            $user->jobdesks()->detach();
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('users/foto', 'public');
            $user->update(['foto' => $path]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    // ============================================
    // DESTROY - Hapus user
    // ============================================
    public function destroy(User $user)
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $user->jobdesks()->detach();

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }

    // ============================================
    // TOGGLE STATUS - Aktif/Nonaktif user
    // ============================================
    public function toggleStatus($id)
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Status user berhasil diubah');
    }

    // ============================================
    // ASSIGN JOBDESK - Assign jobdesk ke user
    // ============================================
    public function assignJobdesk(Request $request, User $user)
    {
        // Cek akses admin
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Jika user adalah admin, tidak bisa diubah jobdesk-nya
        if ($user->role == 'admin') {
            return redirect()->route('users.edit', $user)
                ->with('error', 'Admin memiliki akses ke semua jobdesk secara otomatis.');
        }

        // Validasi input
        $request->validate([
            'jobdesks' => 'nullable|array',
            'jobdesks.*' => 'exists:jobdesks,id',
            'permissions' => 'nullable|array',
        ]);

        // Sync jobdesks
        $jobdeskIds = $request->jobdesks ?? [];
        $permissions = $request->permissions ?? [];

        // Siapkan data pivot
        $syncData = [];
        foreach ($jobdeskIds as $jobdeskId) {
            $syncData[$jobdeskId] = [
                'can_view' => isset($permissions[$jobdeskId]['can_view']) ? true : false,
                'can_create' => isset($permissions[$jobdeskId]['can_create']) ? true : false,
                'can_edit' => isset($permissions[$jobdeskId]['can_edit']) ? true : false,
                'can_delete' => isset($permissions[$jobdeskId]['can_delete']) ? true : false,
                'can_export' => isset($permissions[$jobdeskId]['can_export']) ? true : false,
                'can_approve' => isset($permissions[$jobdeskId]['can_approve']) ? true : false,
            ];
        }

        $user->jobdesks()->sync($syncData);

        return redirect()->route('users.edit', $user)
            ->with('success', 'Jobdesk berhasil diassign ke user.');
    }
}