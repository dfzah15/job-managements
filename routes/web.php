<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    LokasiController,
    InventarisController,
    ChecklistCctvController,
    LaporanAktivitasController,
    LaporanEksekusiController,
    UserController,
    GambarController,
    ProfileController,
    HomeController,
    MaintenanceScheduleController,
    ActivityLogController,
    LayoutController
};

// ============================================
// HALAMAN UTAMA
// ============================================
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('login');
});

// ============================================
// AUTHENTICATION
// ============================================
Auth::routes();

// ============================================
// PROTECTED ROUTES
// ============================================
Route::middleware(['auth'])->group(function () {

    // ============================================
    // DASHBOARD & HOME
    // ============================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ============================================
    // API ROUTES (Tanpa prefix jobdesk)
    // ============================================
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData'])->name('dashboard');
        Route::get('/lokasi/search', [LokasiController::class, 'search'])->name('lokasi.search');
    });

    // ============================================
    // MASTER DATA (Shared)
    // ============================================
    Route::resource('lokasi', LokasiController::class);
    Route::resource('inventaris', InventarisController::class);

    // QR Code Routes
    Route::prefix('inventaris/{id}')->name('inventaris.')->group(function () {
        Route::get('/qr', [InventarisController::class, 'generateQr'])->name('qr');
        Route::get('/show-qr', [InventarisController::class, 'showQr'])->name('show-qr');
        Route::post('/scan-qr', [InventarisController::class, 'scanQr'])->name('scan-qr');
    });

    // ============================================
    // PROFILE MANAGEMENT
    // ============================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('photo');
    });

    // ============================================
    // USER MANAGEMENT (Admin Only)
    // ============================================
    Route::resource('users', UserController::class)->except(['show']);
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::put('/{user}/assign-jobdesk', [UserController::class, 'assignJobdesk'])->name('assign-jobdesk');
    });

    // ============================================
    // ACTIVITY LOG (Admin Only)
    // ============================================
    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::post('/clear', [ActivityLogController::class, 'clear'])->name('clear');
    });

    // ============================================
    // GAMBAR ROUTES
    // ============================================
    Route::prefix('gambar')->name('gambar.')->group(function () {
        Route::post('/upload', [GambarController::class, 'upload'])->name('upload');
        Route::post('/capture', [GambarController::class, 'captureCamera'])->name('capture');
        Route::delete('/{id}', [GambarController::class, 'destroy'])->name('destroy');
    });

    // ============================================
    // ALIAS UNTUK BACKWARD COMPATIBILITY
    // ============================================
    Route::get('/{jobdesk}/layout', function($jobdesk) {
        return redirect()->route('jobdesk.layout.index', $jobdesk);
    })->name('layout.index');
    
    Route::get('/{jobdesk}/layout/{id}/design', function($jobdesk, $id) {
        return redirect()->route('jobdesk.layout.design', [$jobdesk, $id]);
    })->name('layout.design');
    
    Route::get('/{jobdesk}/layout/{id}/export-pdf', function($jobdesk, $id) {
        return redirect()->route('jobdesk.layout.export-pdf', [$jobdesk, $id]);
    })->name('layout.export-pdf');
    
    Route::get('/{jobdesk}/checklist', function($jobdesk) {
        return redirect()->route('jobdesk.checklist.index', $jobdesk);
    })->name('checklist.index');
    
    Route::get('/{jobdesk}/laporan', function($jobdesk) {
        return redirect()->route('jobdesk.laporan.index', $jobdesk);
    })->name('laporan.index');
    
    Route::get('/{jobdesk}/laporan-eksekusi', function($jobdesk) {
        return redirect()->route('jobdesk.laporan-eksekusi.index', $jobdesk);
    })->name('laporan-eksekusi.index');
    
    Route::get('/{jobdesk}/maintenance', function($jobdesk) {
        return redirect()->route('jobdesk.maintenance.index', $jobdesk);
    })->name('maintenance.index');

    // ============================================
    // JOBDESK ROUTES
    // ============================================
    Route::prefix('{jobdesk}')
        ->middleware(['jobdesk:{jobdesk}'])
        ->name('jobdesk.')
        ->group(function () {

        // ============================================
        // CHECKLIST CCTV
        // ============================================
        Route::resource('checklist', ChecklistCctvController::class);

        // ============================================
        // LAPORAN AKTIVITAS
        // ============================================
        Route::resource('laporan', LaporanAktivitasController::class);

        // Export Routes
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/export/excel', [LaporanAktivitasController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [LaporanAktivitasController::class, 'exportPdf'])->name('export.pdf');
        });

        // ============================================
        // LAPORAN EKSEKUSI
        // ============================================
        Route::resource('laporan-eksekusi', LaporanEksekusiController::class);
        Route::prefix('laporan-eksekusi/{id}')->name('laporan-eksekusi.')->group(function () {
            Route::get('/start', [LaporanEksekusiController::class, 'start'])->name('start');
            Route::post('/complete', [LaporanEksekusiController::class, 'complete'])->name('complete');
        });

        // ============================================
        // MAINTENANCE SCHEDULE
        // ============================================
        Route::resource('maintenance', MaintenanceScheduleController::class);
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::get('/calendar', [MaintenanceScheduleController::class, 'calendar'])->name('calendar');
            Route::get('/events', [MaintenanceScheduleController::class, 'getEvents'])->name('events');
            Route::get('/complete/{id}', [MaintenanceScheduleController::class, 'complete'])->name('complete');
        });

        // ============================================
        // LAYOUT & WIRING DIAGRAM
        // ============================================
        Route::resource('layout', LayoutController::class)->except(['edit']);
        Route::get('/layout/{id}/design', [LayoutController::class, 'edit'])->name('layout.design');
        Route::get('/layout/{id}/export-pdf', [LayoutController::class, 'exportPdf'])->name('layout.export-pdf');

        // ============================================
        // LAYOUT DEVICES - SATU ROUTE DELETE SAJA
        // ============================================
        Route::prefix('layout/device/{id}')->name('layout.device.')->group(function () {
            Route::put('/position', [LayoutController::class, 'updateDevicePosition'])->name('position');
            Route::put('/', [LayoutController::class, 'updateDevice'])->name('update');
            Route::delete('/', [LayoutController::class, 'deleteDevice'])->name('delete'); // <-- HANYA INI
        });
        Route::post('/layout/{id}/device', [LayoutController::class, 'addDevice'])->name('layout.device.store');

        // ============================================
        // LAYOUT CONNECTIONS
        // ============================================
        Route::prefix('layout')->name('layout.')->group(function () {
            Route::post('/{id}/connection', [LayoutController::class, 'addConnection'])->name('connection.store');
            Route::delete('/connection/{id}', [LayoutController::class, 'deleteConnection'])->name('connection.delete');
        });

        // ============================================
        // LAYOUT DATA & UPDATES
        // ============================================
        Route::prefix('layout')->name('layout.')->group(function () {
            Route::get('/{id}/data', [LayoutController::class, 'getLayoutData'])->name('data');
            Route::post('/{id}/upload-bg', [LayoutController::class, 'uploadBg'])->name('upload-bg');
            Route::post('/update-positions', [LayoutController::class, 'updatePositions'])->name('update-positions');
        });

    }); // End Jobdesk Routes

}); // End Auth Middleware

// ============================================
// TEST ROUTE
// ============================================
Route::get('/test', fn() => 'Test route works!');