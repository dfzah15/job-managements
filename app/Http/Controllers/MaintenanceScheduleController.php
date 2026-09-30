<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceSchedule;
use App\Models\Lokasi;
use App\Models\Inventaris;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\Auth;

class MaintenanceScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($jobdeskSlug, Request $request)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $query = MaintenanceSchedule::where('jobdesk_id', $jobdesk->id)
                                     ->with(['lokasi', 'inventaris']);

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter jenis
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        // Filter tanggal
        if ($request->date_from) {
            $query->whereDate('tanggal_mulai', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('tanggal_mulai', '<=', $request->date_to);
        }

        $schedules = $query->latest()->paginate(15);
        $statusOptions = ['scheduled', 'in_progress', 'completed', 'cancelled'];
        $jenisOptions = ['rutin', 'khusus', 'tahunan'];

        return view('maintenance.index', compact('schedules', 'statusOptions', 'jenisOptions', 'jobdesk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($jobdeskSlug)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $lokasi = Lokasi::all();
        $inventaris = Inventaris::all();
        
        return view('maintenance.create', compact('lokasi', 'inventaris', 'jobdesk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($jobdeskSlug, Request $request)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,id',
            'inventaris_id' => 'required|exists:inventaris,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis' => 'required|in:rutin,khusus,tahunan',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'teknisi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_pattern' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['jobdesk_id'] = $jobdesk->id; // <-- PASTIKAN INI
        $data['is_recurring'] = $request->has('is_recurring') ? 1 : 0;

        MaintenanceSchedule::create($data);

        return redirect()->route('maintenance.index', $jobdeskSlug)
            ->with('success', 'Jadwal maintenance berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($jobdeskSlug, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $maintenance = MaintenanceSchedule::where('jobdesk_id', $jobdesk->id)
                                          ->with(['lokasi', 'inventaris'])
                                          ->findOrFail($id);
        
        return view('maintenance.show', compact('maintenance', 'jobdesk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jobdeskSlug, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $maintenance = MaintenanceSchedule::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        $lokasi = Lokasi::all();
        $inventaris = Inventaris::all();
        
        return view('maintenance.edit', compact('maintenance', 'lokasi', 'inventaris', 'jobdesk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($jobdeskSlug, Request $request, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $maintenance = MaintenanceSchedule::where('jobdesk_id', $jobdesk->id)->findOrFail($id);

        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,id',
            'inventaris_id' => 'required|exists:inventaris,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis' => 'required|in:rutin,khusus,tahunan',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'teknisi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_pattern' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['is_recurring'] = $request->has('is_recurring') ? 1 : 0;

        $maintenance->update($data);

        return redirect()->route('maintenance.index', $jobdeskSlug)
            ->with('success', 'Jadwal maintenance berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jobdeskSlug, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $maintenance = MaintenanceSchedule::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        $maintenance->delete();
        
        return redirect()->route('maintenance.index', $jobdeskSlug)
            ->with('success', 'Jadwal maintenance berhasil dihapus');
    }

    /**
     * Show calendar view.
     */
    public function calendar($jobdeskSlug)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $schedules = MaintenanceSchedule::where('jobdesk_id', $jobdesk->id)
                                        ->with(['lokasi', 'inventaris'])
                                        ->get();
        
        return view('maintenance.calendar', compact('schedules', 'jobdesk'));
    }

    /**
     * Get events for calendar.
     */
    public function getEvents($jobdeskSlug)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $events = MaintenanceSchedule::where('jobdesk_id', $jobdesk->id)
                                     ->with(['lokasi', 'inventaris'])
                                     ->get();
        
        return response()->json($events);
    }

    /**
     * Complete maintenance schedule.
     */
    public function complete($jobdeskSlug, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $schedule = MaintenanceSchedule::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        $schedule->update([
            'status' => 'completed',
            'tanggal_selesai' => date('Y-m-d'),
        ]);
        
        return redirect()->route('maintenance.index', $jobdeskSlug)
            ->with('success', 'Maintenance berhasil diselesaikan');
    }
}