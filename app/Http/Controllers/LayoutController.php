<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layout;
use App\Models\LayoutDevice;
use App\Models\LayoutConnection;
use App\Models\Jobdesk;
use App\Models\Inventaris;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($jobdeskSlug)
    {
        try {
            $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
            $layouts = Layout::where('jobdesk_id', $jobdesk->id)
                ->withCount(['devices', 'connections'])
                ->latest()
                ->paginate(12);
            
            return view('layout.index', compact('jobdesk', 'layouts'));
        } catch (\Exception $e) {
            Log::error('Error loading layouts: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data layout.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($jobdeskSlug)
    {
        try {
            $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
            return view('layout.create', compact('jobdesk'));
        } catch (\Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->route('layout.index', $jobdeskSlug)
                ->with('error', 'Gagal memuat form pembuatan layout.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($jobdeskSlug, Request $request)
    {
        try {
            $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
            
            $request->validate([
                'nama_layout' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                'width' => 'nullable|integer|min:800|max:4000',
                'height' => 'nullable|integer|min:600|max:4000',
            ]);

            $data = $request->only(['nama_layout', 'deskripsi']);
            $data['jobdesk_id'] = $jobdesk->id;
            $data['slug'] = Str::slug($request->nama_layout) . '-' . time();
            $data['width'] = $request->width ?? 1200;
            $data['height'] = $request->height ?? 800;
            $data['created_by'] = Auth::id();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . Str::slug($request->nama_layout) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('layouts', $filename, 'public');
                $data['image_path'] = $path;
            }

            $layout = Layout::create($data);

            return redirect()->route('layout.edit', [$jobdeskSlug, $layout->id])
                ->with('success', 'Layout "' . $layout->nama_layout . '" berhasil dibuat!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating layout: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membuat layout. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($jobdeskSlug, $id)
    {
        try {
            $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
            $layout = Layout::with(['devices', 'connections.sourceDevice', 'connections.targetDevice'])
                ->findOrFail($id);
            
            return view('layout.show', compact('jobdesk', 'layout'));
        } catch (\Exception $e) {
            Log::error('Error showing layout: ' . $e->getMessage());
            return redirect()->route('layout.index', $jobdeskSlug)
                ->with('error', 'Layout tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the resource (Design Mode).
     */
    public function edit($jobdeskSlug, $id)
    {
        try {
            $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
            $layout = Layout::with(['devices', 'connections'])->findOrFail($id);
            $inventaris = Inventaris::where('jobdesk_id', $jobdesk->id)
                ->where('status', 'available')
                ->get();
            
            return view('layout.design', compact('jobdesk', 'layout', 'inventaris'));
        } catch (\Exception $e) {
            Log::error('Error loading design editor: ' . $e->getMessage());
            return redirect()->route('layout.index', $jobdeskSlug)
                ->with('error', 'Gagal memuat editor layout.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($jobdeskSlug, Request $request, $id)
    {
        try {
            $layout = Layout::findOrFail($id);
            
            $request->validate([
                'nama_layout' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ]);

            $data = $request->only(['nama_layout', 'deskripsi']);

            if ($request->hasFile('image')) {
                // Delete old image
                if ($layout->image_path && Storage::disk('public')->exists($layout->image_path)) {
                    Storage::disk('public')->delete($layout->image_path);
                }
                
                $file = $request->file('image');
                $filename = time() . '_' . Str::slug($request->nama_layout) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('layouts', $filename, 'public');
                $data['image_path'] = $path;
            }

            $layout->update($data);

            return redirect()->route('layout.index', $jobdeskSlug)
                ->with('success', 'Layout "' . $layout->nama_layout . '" berhasil diupdate.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating layout: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate layout. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jobdeskSlug, $id)
    {
        try {
            $layout = Layout::findOrFail($id);
            
            // Delete background image
            if ($layout->image_path && Storage::disk('public')->exists($layout->image_path)) {
                Storage::disk('public')->delete($layout->image_path);
            }
            
            // Delete all related data
            foreach ($layout->devices as $device) {
                $device->connectionsFrom()->delete();
                $device->connectionsTo()->delete();
                $device->delete();
            }
            
            $layout->delete();

            return redirect()->route('layout.index', $jobdeskSlug)
                ->with('success', 'Layout "' . $layout->nama_layout . '" berhasil dihapus.');
                
        } catch (\Exception $e) {
            Log::error('Error deleting layout: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus layout. Silakan coba lagi.');
        }
    }

    /**
     * Add device to layout.
     */
    public function addDevice(Request $request, $jobdeskSlug, $id)
    {
        try {
            $layout = Layout::findOrFail($id);
            
            $request->validate([
                'nama_device' => 'required|string|max:255',
                'tipe_device' => 'required|string|max:100',
                'pos_x' => 'nullable|integer|min:0|max:4000',
                'pos_y' => 'nullable|integer|min:0|max:4000',
                'rotation' => 'nullable|integer|min:0|max:360',
                'icon' => 'nullable|string|max:100',
                'color' => 'nullable|string|max:20',
                'inventaris_id' => 'nullable|exists:inventaris,id',
                'keterangan' => 'nullable|string|max:500',
            ]);

            $data = $request->only([
                'nama_device', 'tipe_device', 'pos_x', 'pos_y', 
                'rotation', 'icon', 'color', 'inventaris_id', 'keterangan'
            ]);
            
            $data['layout_id'] = $layout->id;
            $data['pos_x'] = $request->pos_x ?? 100;
            $data['pos_y'] = $request->pos_y ?? 100;
            $data['rotation'] = $request->rotation ?? 0;
            $data['icon'] = $request->icon ?? 'bi bi-geo-alt';
            $data['color'] = $request->color ?? '#3498db';

            $device = LayoutDevice::create($data);

            // If request is AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Device berhasil ditambahkan!',
                    'device' => $device
                ], 201);
            }

            return redirect()->route('layout.edit', [$jobdeskSlug, $layout->id])
                ->with('success', 'Device "' . $device->nama_device . '" berhasil ditambahkan!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error adding device: ' . $e->getMessage());
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan device.'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Gagal menambahkan device. Silakan coba lagi.');
        }
    }

    /**
     * Update device position (AJAX).
     */
    public function updateDevicePosition(Request $request, $jobdeskSlug, $id)
    {
        try {
            $device = LayoutDevice::findOrFail($id);
            
            $request->validate([
                'pos_x' => 'required|integer|min:0|max:4000',
                'pos_y' => 'required|integer|min:0|max:4000',
                'rotation' => 'nullable|integer|min:0|max:360',
            ]);

            $device->update([
                'pos_x' => $request->pos_x,
                'pos_y' => $request->pos_y,
                'rotation' => $request->rotation ?? $device->rotation,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Posisi device berhasil diupdate.',
                'device' => $device
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating device position: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate posisi device.'
            ], 500);
        }
    }

    /**
     * Update device info (AJAX).
     */
    public function updateDevice(Request $request, $jobdeskSlug, $id)
    {
        try {
            $device = LayoutDevice::findOrFail($id);
            
            $request->validate([
                'nama_device' => 'required|string|max:255',
                'tipe_device' => 'nullable|string|max:100',
                'rotation' => 'nullable|integer|min:0|max:360',
                'icon' => 'nullable|string|max:100',
                'color' => 'nullable|string|max:20',
                'keterangan' => 'nullable|string|max:500',
                'inventaris_id' => 'nullable|exists:inventaris,id',
            ]);

            $data = $request->only([
                'nama_device', 'tipe_device', 'rotation', 'icon', 
                'color', 'keterangan', 'inventaris_id'
            ]);

            $device->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil diupdate.',
                'device' => $device
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating device: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate device.'
            ], 500);
        }
    }

    /**
     * Delete device.
     */
    public function deleteDevice($jobdeskSlug, $id)
    {
        try {
            \Log::info('Delete device called:', [
                'jobdesk' => $jobdeskSlug,
                'device_id' => $id,
                'method' => request()->method()
            ]);
            
            // Cari device
            $device = LayoutDevice::find($id);
            
            if (!$device) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device tidak ditemukan dengan ID: ' . $id
                ], 404);
            }
            
            $deviceName = $device->nama_device;
            
            // Hapus semua koneksi terkait
            $device->connectionsFrom()->delete();
            $device->connectionsTo()->delete();
            
            // Hapus device
            $device->delete();

            return response()->json([
                'success' => true,
                'message' => 'Device "' . $deviceName . '" berhasil dihapus.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting device: ' . $e->getMessage(), [
                'device_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus device: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add connection between devices.
     */
    public function addConnection(Request $request, $jobdeskSlug, $id)
    {
        try {
            $layout = Layout::findOrFail($id);
            
            $validated = $request->validate([
                'device_from_id' => 'required|exists:layout_devices,id',
                'device_to_id' => 'required|exists:layout_devices,id|different:device_from_id',
                'tipe_kabel' => 'nullable|string|max:100',
                'panjang_meter' => 'nullable|numeric|min:0',
                'warna' => 'nullable|string|max:20',
                'label' => 'nullable|string|max:255',
            ]);

            // ============================================
            // CEK DUPLIKAT DENGAN LEBIH DETAIL
            // ============================================
            $existing = LayoutConnection::where('layout_id', $layout->id)
                ->where(function($query) use ($validated) {
                    $query->where('device_from_id', $validated['device_from_id'])
                        ->where('device_to_id', $validated['device_to_id']);
                })
                ->orWhere(function($query) use ($validated) {
                    $query->where('device_from_id', $validated['device_to_id'])
                        ->where('device_to_id', $validated['device_from_id']);
                })
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Koneksi sudah ada'
                ], 409);
            }

            // Buat koneksi
            $connection = LayoutConnection::create([
                'layout_id' => $layout->id,
                'device_from_id' => $validated['device_from_id'],
                'device_to_id' => $validated['device_to_id'],
                'tipe_kabel' => $validated['tipe_kabel'] ?? 'standard',
                'panjang_meter' => $validated['panjang_meter'] ?? 0,
                'warna' => $validated['warna'] ?? '#2ecc71',
                'label' => $validated['label'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Koneksi berhasil ditambahkan',
                'connection_id' => $connection->id
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error adding connection: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan koneksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete connection.
     */
    public function deleteConnection($jobdeskSlug, $id)
    {
        try {
            $connection = LayoutConnection::findOrFail($id);
            $connection->delete();

            return redirect()->back()
                ->with('success', 'Koneksi berhasil dihapus.');
                
        } catch (\Exception $e) {
            Log::error('Error deleting connection: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus koneksi. Silakan coba lagi.');
        }
    }

    /**
     * Get layout data for design (AJAX).
     */
    public function getLayoutData($jobdeskSlug, $id)
    {
        try {
            $layout = Layout::with(['devices', 'connections'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'devices' => $layout->devices,
                'connections' => $layout->connections,
                'layout' => $layout
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching layout data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data layout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get device data for edit (AJAX).
     */
    public function getDeviceData($jobdeskSlug, $id)
    {
        try {
            $device = LayoutDevice::with(['inventaris'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'device' => $device
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching device data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data device.'
            ], 500);
        }
    }

    /**
     * Update multiple device positions after bulk drag & drop (AJAX).
     */
    public function updatePositions(Request $request, $jobdeskSlug)
    {
        try {
            $request->validate([
                'devices' => 'required|array|min:1',
                'devices.*.id' => 'required|exists:layout_devices,id',
                'devices.*.pos_x' => 'required|numeric|min:0|max:4000',
                'devices.*.pos_y' => 'required|numeric|min:0|max:4000',
                'devices.*.rotation' => 'nullable|numeric|min:0|max:360',
            ]);

            $updated = 0;
            foreach ($request->devices as $deviceData) {
                $device = LayoutDevice::find($deviceData['id']);
                if ($device) {
                    $updateData = [
                        'pos_x' => $deviceData['pos_x'],
                        'pos_y' => $deviceData['pos_y'],
                    ];
                    if (isset($deviceData['rotation'])) {
                        $updateData['rotation'] = $deviceData['rotation'];
                    }
                    $device->update($updateData);
                    $updated++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => $updated . ' posisi device berhasil disimpan.',
                'updated' => $updated
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating multiple positions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan posisi device.'
            ], 500);
        }
    }

    /**
     * Upload background denah langsung dari editor (AJAX).
     */
    public function uploadBg(Request $request, $jobdeskSlug, $id)
    {
        try {
            $layout = Layout::findOrFail($id);

            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            ]);

            // Delete old image
            if ($layout->image_path && Storage::disk('public')->exists($layout->image_path)) {
                Storage::disk('public')->delete($layout->image_path);
            }

            $file = $request->file('image');
            $filename = time() . '_bg_' . Str::slug($layout->nama_layout) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('layouts/backgrounds', $filename, 'public');
            
            $layout->update(['image_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Gambar denah berhasil diupload!',
                'image_url' => Storage::url($path)
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error uploading background image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload gambar denah. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman cetak laporan Bill of Materials (BOM) & Diagram ke PDF.
     */
    public function exportPdf(Request $request, $jobdeskSlug, $id)
    {
        try {
            $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
            $layout = Layout::with(['devices', 'connections.sourceDevice', 'connections.targetDevice'])
                ->findOrFail($id);
            
            $devices = $layout->devices;
            $connections = $layout->connections;

            // Get canvas image from request (if any)
            $canvasImage = $request->input('canvas_image');
            
            // If no canvas image provided, try to get from layout
            if (!$canvasImage && $layout->image_path) {
                $canvasImage = Storage::disk('public')->url($layout->image_path);
            }

            $pdf = Pdf::loadView('pdf.layout-report', compact(
                'jobdesk',
                'layout', 
                'devices', 
                'connections', 
                'canvasImage'
            ));

            // Set paper size and orientation
            $pdf->setPaper('a4', 'landscape');
            
            return $pdf->download('Laporan-Layout-' . $layout->nama_layout . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('Error exporting PDF: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update device properties (AJAX).
     */
    public function bulkUpdateDevices(Request $request, $jobdeskSlug)
    {
        try {
            $request->validate([
                'devices' => 'required|array|min:1',
                'devices.*.id' => 'required|exists:layout_devices,id',
                'devices.*.color' => 'nullable|string|max:20',
                'devices.*.icon' => 'nullable|string|max:100',
                'devices.*.nama_device' => 'nullable|string|max:255',
            ]);

            $updated = 0;
            foreach ($request->devices as $deviceData) {
                $device = LayoutDevice::find($deviceData['id']);
                if ($device) {
                    $updateData = [];
                    if (isset($deviceData['color'])) $updateData['color'] = $deviceData['color'];
                    if (isset($deviceData['icon'])) $updateData['icon'] = $deviceData['icon'];
                    if (isset($deviceData['nama_device'])) $updateData['nama_device'] = $deviceData['nama_device'];
                    
                    if (!empty($updateData)) {
                        $device->update($updateData);
                        $updated++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => $updated . ' device berhasil diupdate.',
                'updated' => $updated
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error bulk updating devices: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate device.'
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStats($jobdeskSlug)
    {
        try {
            $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
            
            $stats = [
                'total_layouts' => Layout::where('jobdesk_id', $jobdesk->id)->count(),
                'total_devices' => LayoutDevice::whereHas('layout', function($q) use ($jobdesk) {
                    $q->where('jobdesk_id', $jobdesk->id);
                })->count(),
                'total_connections' => LayoutConnection::whereHas('layout', function($q) use ($jobdesk) {
                    $q->where('jobdesk_id', $jobdesk->id);
                })->count(),
                'recent_layouts' => Layout::where('jobdesk_id', $jobdesk->id)
                    ->latest()
                    ->take(5)
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik.'
            ], 500);
        }
    }
}