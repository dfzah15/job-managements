<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChecklistResult;
use App\Models\ChecklistTemplate;
use App\Models\Lokasi;
use App\Models\Inventaris;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\Auth;

class ChecklistCctvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($jobdeskSlug, Request $request)
    {
        // Ambil jobdesk berdasarkan slug
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();

        // Cek akses user ke jobdesk ini
        if (!Auth::user()->isAdmin() && !Auth::user()->hasJobdesk($jobdeskSlug)) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke jobdesk ini.');
        }

        $query = ChecklistResult::where('jobdesk_id', $jobdesk->id)
                                ->with(['lokasi', 'inventaris', 'user']);

        // Pencarian
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('petugas_check', 'LIKE', "%{$request->search}%")
                  ->orWhere('catatan', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('lokasi', function($l) use ($request) {
                      $l->where('nama_lokasi', 'LIKE', "%{$request->search}%")
                        ->orWhere('kode_lokasi', 'LIKE', "%{$request->search}%");
                  });
            });
        }

        // Filter lokasi
        if ($request->lokasi_id) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        // Filter tanggal
        if ($request->tanggal) {
            $query->whereDate('tanggal_check', $request->tanggal);
        }

        $checklist = $query->latest()->paginate(15);
        $lokasi = Lokasi::all();

        return view('checklist.index', compact('checklist', 'lokasi', 'jobdesk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($jobdeskSlug)
    {
        // Ambil jobdesk berdasarkan slug
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();

        // Cek akses create
        if (!Auth::user()->isAdmin() && !Auth::user()->canAccessJobdesk($jobdeskSlug, 'create')) {
            return redirect()->route('jobdesk.checklist.index', $jobdeskSlug)
                ->with('error', 'Anda tidak memiliki akses untuk membuat checklist.');
        }

        $lokasi = Lokasi::all();
        
        // ============================================
        // FILTER INVENTARIS BERDASARKAN JOBDESK
        // ============================================
        $inventaris = Inventaris::where('jobdesk_id', $jobdesk->id)->get();
        
        // Ambil template checklist sesuai jobdesk
        $templates = ChecklistTemplate::where('jobdesk_id', $jobdesk->id)
                                      ->orderBy('sort_order')
                                      ->get();

        return view('checklist.create', compact('lokasi', 'inventaris', 'jobdesk', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($jobdeskSlug, Request $request)
    {
        // Ambil jobdesk berdasarkan slug
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();

        // Cek akses create
        if (!Auth::user()->isAdmin() && !Auth::user()->canAccessJobdesk($jobdeskSlug, 'create')) {
            return redirect()->route('jobdesk.checklist.index', $jobdeskSlug)
                ->with('error', 'Anda tidak memiliki akses untuk membuat checklist.');
        }

        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,id',
            'inventaris_id' => 'nullable|exists:inventaris,id',
            'tanggal_check' => 'required|date',
            'waktu_check' => 'required',
            'petugas_check' => 'required|string|max:255',
            'values' => 'nullable|array',
            'catatan' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['jobdesk_id'] = $jobdesk->id;
        $data['user_id'] = Auth::id();
        $data['petugas_check'] = $request->petugas_check ?? Auth::user()->name;

        // Hitung status berdasarkan nilai checklist
        $status = $this->calculateStatus($request->values ?? [], $jobdesk->id);
        $data['status'] = $status;

        $checklist = ChecklistResult::create($data);

        return redirect()->route('jobdesk.checklist.index', $jobdeskSlug)
            ->with('success', 'Checklist ' . $jobdesk->name . ' berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($jobdeskSlug, $id)
    {
        // Ambil jobdesk berdasarkan slug
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();

        // Cek akses view
        if (!Auth::user()->isAdmin() && !Auth::user()->canAccessJobdesk($jobdeskSlug, 'view')) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke jobdesk ini.');
        }

        $checklist = ChecklistResult::where('jobdesk_id', $jobdesk->id)
                                    ->with(['lokasi', 'inventaris', 'user'])
                                    ->findOrFail($id);

        // Ambil template untuk menampilkan label
        $templates = ChecklistTemplate::where('jobdesk_id', $jobdesk->id)
                                      ->orderBy('sort_order')
                                      ->get();

        return view('checklist.show', compact('checklist', 'jobdesk', 'templates'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jobdeskSlug, $id)
    {
        // Ambil jobdesk berdasarkan slug
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();

        // Cek akses edit
        if (!Auth::user()->isAdmin() && !Auth::user()->canAccessJobdesk($jobdeskSlug, 'edit')) {
            return redirect()->route('jobdesk.checklist.index', $jobdeskSlug)
                ->with('error', 'Anda tidak memiliki akses untuk mengedit checklist.');
        }

        $checklist = ChecklistResult::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        $lokasi = Lokasi::all();
        
        // ============================================
        // FILTER INVENTARIS BERDASARKAN JOBDESK
        // ============================================
        $inventaris = Inventaris::where('jobdesk_id', $jobdesk->id)->get();
        
        $templates = ChecklistTemplate::where('jobdesk_id', $jobdesk->id)
                                      ->orderBy('sort_order')
                                      ->get();

        return view('checklist.edit', compact('checklist', 'lokasi', 'inventaris', 'jobdesk', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($jobdeskSlug, Request $request, $id)
    {
        // Ambil jobdesk berdasarkan slug
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();

        // Cek akses edit
        if (!Auth::user()->isAdmin() && !Auth::user()->canAccessJobdesk($jobdeskSlug, 'edit')) {
            return redirect()->route('jobdesk.checklist.index', $jobdeskSlug)
                ->with('error', 'Anda tidak memiliki akses untuk mengedit checklist.');
        }

        $checklist = ChecklistResult::where('jobdesk_id', $jobdesk->id)->findOrFail($id);

        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,id',
            'inventaris_id' => 'nullable|exists:inventaris,id',
            'tanggal_check' => 'required|date',
            'waktu_check' => 'required',
            'petugas_check' => 'required|string|max:255',
            'values' => 'nullable|array',
            'catatan' => 'nullable|string'
        ]);

        $data = $request->all();
        
        // Hitung status berdasarkan nilai checklist
        $status = $this->calculateStatus($request->values ?? [], $jobdesk->id);
        $data['status'] = $status;

        $checklist->update($data);

        return redirect()->route('jobdesk.checklist.index', $jobdeskSlug)
            ->with('success', 'Checklist ' . $jobdesk->name . ' berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jobdeskSlug, $id)
    {
        // Ambil jobdesk berdasarkan slug
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();

        // Cek akses delete
        if (!Auth::user()->isAdmin() && !Auth::user()->canAccessJobdesk($jobdeskSlug, 'delete')) {
            return redirect()->route('jobdesk.checklist.index', $jobdeskSlug)
                ->with('error', 'Anda tidak memiliki akses untuk menghapus checklist.');
        }

        $checklist = ChecklistResult::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        $checklist->delete();

        return redirect()->route('jobdesk.checklist.index', $jobdeskSlug)
            ->with('success', 'Checklist ' . $jobdesk->name . ' berhasil dihapus');
    }

    /**
     * Calculate status based on checklist values
     */
    private function calculateStatus($values, $jobdeskId)
    {
        // Jika tidak ada nilai, default normal
        if (empty($values)) {
            return 'normal';
        }

        // Ambil template untuk menentukan field yang critical
        $templates = ChecklistTemplate::where('jobdesk_id', $jobdeskId)->get();
        
        $warningCount = 0;
        $dangerCount = 0;

        foreach ($templates as $template) {
            $value = $values[$template->field_name] ?? null;
            
            // Cek berdasarkan field_type
            if ($template->field_type == 'select') {
                $options = $template->options ?? [];
                // Jika value adalah key yang menandakan masalah
                if (in_array($value, ['tidak_normal', 'rusak', 'error', 'offline', 'failed', 'down', 'rendah', 'kotor', 'bocor', 'overheat', 'trip', 'aus', 'putus', 'keruh', 'berbau', 'berlebihan', 'tidak', 'kurang', 'berlebihan'])) {
                    $dangerCount++;
                } elseif (in_array($value, ['warning', 'slow', 'running', 'perlu_ganti', 'kotor'])) {
                    $warningCount++;
                }
            } elseif ($template->field_type == 'checkbox') {
                // Jika checkbox tidak dicentang (0) dan field penting
                if ($value == 0 && $template->is_required) {
                    $warningCount++;
                }
            } elseif ($template->field_type == 'number') {
                // Cek apakah nilai di luar range normal
                if (is_numeric($value)) {
                    // Contoh: suhu terlalu tinggi, tekanan terlalu rendah, dll
                    if (strpos($template->field_name, 'suhu') !== false && $value > 80) {
                        $dangerCount++;
                    } elseif (strpos($template->field_name, 'tekanan') !== false && $value < 10) {
                        $dangerCount++;
                    }
                }
            }
        }

        // Tentukan status akhir
        if ($dangerCount > 0) {
            return 'danger';
        } elseif ($warningCount > 0) {
            return 'warning';
        } else {
            return 'normal';
        }
    }
}