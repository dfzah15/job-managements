<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    // HAPUS __construct() dengan middleware

    public function index(Request $request)
    {
        // Cek akses di awal method
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = ActivityLog::with('user')->latest();

        if ($request->search) {
            $query->where('modul', 'LIKE', "%{$request->search}%")
                  ->orWhere('aksi', 'LIKE', "%{$request->search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$request->search}%");
        }

        if ($request->aksi) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(20);
        $aksiOptions = ['create', 'update', 'delete', 'login', 'logout'];

        return view('activity.index', compact('logs', 'aksiOptions'));
    }

    public function show(ActivityLog $activityLog)
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('activity.show', compact('activityLog'));
    }

    public function clear()
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        ActivityLog::truncate();
        return redirect()->route('activity.index')
            ->with('success', 'Log berhasil dibersihkan');
    }
}