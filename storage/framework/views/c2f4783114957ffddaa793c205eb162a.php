

<?php $__env->startSection('title', 'CAD Designer Pro - ' . $layout->nama_layout); ?>

<?php $__env->startSection('content'); ?>
<!-- Bootstrap Icons & FontAwesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* === DESIGNER CANVAS & WORKSPACE === */
    .designer-wrapper {
        display: flex;
        gap: 12px;
        height: calc(100vh - 120px);
        min-height: 700px;
    }
    
    .designer-sidebar {
        width: 290px;
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .designer-canvas-viewport {
        flex: 1;
        position: relative;
        background: #1e1e1e;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #333;
        cursor: grab;
    }

    .designer-canvas-viewport:active {
        cursor: grabbing;
    }

    .designer-canvas-container {
        width: 3000px;
        height: 2000px;
        position: absolute;
        top: 0; left: 0;
        transform-origin: 0 0;
        background-color: #f8f9fa;
        background-image: 
            linear-gradient(rgba(0,0,0,0.06) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,0.06) 1px, transparent 1px);
        background-size: 20px 20px;
    }

    .designer-canvas-container img.denah-bg {
        width: 100%;
        height: 100%;
        object-fit: contain;
        pointer-events: none;
        position: absolute;
        top: 0; left: 0;
        z-index: 1;
        transition: opacity 0.15s ease;
    }

    /* === PALETTE ACCORDION & ITEMS === */
    .palette-scroll {
        overflow-y: auto;
        padding: 8px;
        flex: 1;
    }

    .palette-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 10px;
        margin-bottom: 4px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        cursor: grab;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.15s ease;
        user-select: none;
    }
    .palette-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateX(3px);
    }
    .palette-item:active {
        cursor: grabbing;
    }
    .palette-item i {
        font-size: 16px;
    }

    /* === NODE / DEVICE STYLING === */
    .designer-canvas-container .device-item {
        position: absolute;
        cursor: move;
        padding: 6px 10px;
        border-radius: 6px;
        color: #ffffff;
        font-size: 11px;
        font-weight: 600;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 60px;
        min-height: 60px;
        border: 2px solid rgba(255,255,255,0.9);
        box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        user-select: none;
        z-index: 10;
        transition: border-color 0.2s, box-shadow 0.2s;
        pointer-events: auto;
    }
    
    .designer-canvas-container .device-item.active-selected {
        border-color: #f1c40f !important;
        box-shadow: 0 0 0 4px rgba(241, 196, 15, 0.4);
    }

    .designer-canvas-container .device-item .device-icon {
        font-size: 22px;
        margin-bottom: 2px;
        pointer-events: none;
    }
    
    .designer-canvas-container .device-item .device-name {
        font-size: 9.5px;
        text-align: center;
        max-width: 85px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        pointer-events: none;
    }

    /* === CCTV FOV CONE === */
    .device-item.cctv-device { overflow: visible !important; }
    .device-item .cctv-cone {
        position: absolute;
        top: 50%; left: 50%;
        width: 0; height: 0;
        border-left: 50px solid transparent;
        border-right: 50px solid transparent;
        border-top: 100px solid rgba(231, 76, 60, 0.35);
        transform-origin: 50% 0%;
        pointer-events: none;
        z-index: -1;
        margin-left: -50px;
    }

    /* === ACTION CONTROLS === */
    .device-item .device-action {
        position: absolute;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 10px;
        display: none;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        border: 1.5px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        z-index: 100;
        background: rgba(0,0,0,0.6);
    }
    .device-item .device-delete { top: -8px; right: -8px; background: #e74c3c; }
    .device-item .device-rotate { top: -8px; left: -8px; background: #f39c12; }
    .device-item:hover .device-action { display: flex; }

    /* === RIGHT OBJECT PANEL TREE === */
    .object-panel {
        width: 320px;
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        max-height: 100%;
    }
    .object-list-item {
        font-size: 12px;
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }
    .object-list-item:hover { background: #f8fafc; }

    .jtk-endpoint { z-index: 12; }

    /* Zoom controls */
    .zoom-controls {
        position: absolute;
        bottom: 20px;
        right: 20px;
        z-index: 50;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .zoom-controls button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: rgba(255,255,255,0.9);
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .zoom-controls button:hover {
        background: white;
        transform: scale(1.05);
    }
    .zoom-level {
        position: absolute;
        bottom: 20px;
        right: 75px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        z-index: 50;
    }
    /* ============================================ */
    /* MODAL DELETE DEVICE CUSTOM */
    /* ============================================ */
    #deleteDeviceModal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    #deleteDeviceModal .modal-header {
        border-radius: 16px 16px 0 0;
        padding: 20px 24px;
    }

    #deleteDeviceModal .modal-body {
        padding: 30px 24px;
    }

    #deleteDeviceModal .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 16px 24px;
    }

    #deleteDeviceModal .btn {
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 600;
    }

    /* Animasi masuk modal */
    #deleteDeviceModal.show .modal-dialog {
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px) scale(0.9);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }
</style>

<!-- TOP CONTROL BAR EXPORTS & SETTINGS -->
<div class="card mb-2 shadow-sm border-0">
    <div class="card-body p-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <!-- Title Info -->
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo e(route('jobdesk.layout.index', $jobdesk->slug)); ?>" class="btn btn-sm btn-light border">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h6 class="mb-0 fw-bold"><i class="<?php echo e($jobdesk->icon); ?>"></i> <?php echo e($layout->nama_layout); ?></h6>
                <small class="text-muted" style="font-size: 11px;">Expert Engineering CAD Workbench</small>
            </div>
        </div>

        <!-- Wire Line Styling Controls -->
        <div class="d-flex align-items-center gap-2 border-start border-end px-3">
            <div>
                <label class="form-label mb-0 small fw-bold" style="font-size:10px;">Routing Kabel:</label>
                <select id="lineTypeSelect" class="form-select form-select-sm" style="font-size:11px;" onchange="updateLineStyle()">
                    <option value="Flowchart" selected>Orthogonal (Siku)</option>
                    <option value="Bezier">Bezier (Lengkung Smooth)</option>
                    <option value="Straight">Straight (Garis Lurus)</option>
                </select>
            </div>
            <div>
                <label class="form-label mb-0 small fw-bold" style="font-size:10px;">Corner Radius:</label>
                <input type="number" id="lineCornerInput" class="form-control form-control-sm" value="12" min="0" max="50" style="width:55px; font-size:11px;" onchange="updateLineStyle()">
            </div>
            <div>
                <label class="form-label mb-0 small fw-bold" style="font-size:10px;">Tebal Kabel:</label>
                <input type="number" id="lineWidthInput" class="form-control form-control-sm" value="3" min="1" max="12" style="width:50px; font-size:11px;" onchange="updateLineStyle()">
            </div>
            <div>
                <label class="form-label mb-0 small fw-bold" style="font-size:10px;">Warna Kabel:</label>
                <input type="color" id="lineColorInput" class="form-control form-control-sm form-control-color" value="#34495e" style="width:40px; height:28px;" onchange="updateLineStyle()">
            </div>
        </div>

        <!-- Denah Opacity & BG Controller -->
        <div class="d-flex align-items-center gap-2 border-end pr-3">
            <div>
                <label class="form-label mb-0 small fw-bold" style="font-size:10px;">Opacity Blueprint:</label>
                <input type="range" id="opacityRange" class="form-range d-block" min="0" max="1" step="0.05" value="0.85" style="width: 90px;" oninput="updateBgOpacity(this.value)">
            </div>
            <label class="btn btn-sm btn-outline-secondary mb-0" title="Upload Denah Baru" style="font-size:11px; cursor:pointer;">
                <i class="bi bi-file-earmark-image"></i> Denah
                <input type="file" id="bgUploadInput" accept="image/*" class="d-none" onchange="uploadBgImage(this)">
            </label>
        </div>

        <!-- Exports & Actions -->
        <div class="d-flex gap-1">
            <button onclick="exportToImage()" class="btn btn-sm btn-outline-primary" style="font-size:11px;">
                <i class="bi bi-image"></i> Export PNG
            </button>
            <button onclick="exportToPDF()" class="btn btn-sm btn-outline-danger" style="font-size:11px;">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </button>
            <button onclick="saveLayout()" class="btn btn-sm btn-success" style="font-size:11px;">
                <i class="bi bi-floppy"></i> Simpan Diagram
            </button>
        </div>
    </div>
</div>

<!-- WORKSPACE CONTAINER -->
<div class="designer-wrapper">
    <!-- LEFT SIDEBAR: EXPERT PALETTE -->
    <!-- LEFT SIDEBAR: EXPERT PALETTE -->
    <div class="designer-sidebar">
        <div class="p-2 border-bottom bg-light fw-bold text-center small text-secondary">
            <i class="bi bi-cpu"></i> ENGINEERING PALETTE
            <span class="badge bg-primary ms-2" id="paletteCount">0</span>
        </div>
        <div class="palette-scroll" id="paletteContainer">
            <!-- PALETTE ITEMS - PASTIKAN INI ADA -->
            <div class="accordion accordion-flush" id="paletteAccordion">
                
                <!-- KELISTRIKAN -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button p-2 small fw-bold text-danger" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#catElectrical" 
                                aria-expanded="true">
                            <i class="bi bi-lightning-charge me-2"></i> Electrical & Power
                        </button>
                    </h2>
                    <div id="catElectrical" class="accordion-collapse collapse show" 
                        data-bs-parent="#paletteAccordion">
                        <div class="accordion-body p-1">
                            <div class="palette-item" draggable="true" 
                                data-device-type="mcb" 
                                data-device-name="Panel MCB / SDP" 
                                data-device-icon="bi bi-lightning-charge-fill" 
                                data-device-color="#e74c3c">
                                <i class="bi bi-lightning-charge-fill text-danger"></i> Main Panel / MCB
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="stopkontak" 
                                data-device-name="Stopkontak Power" 
                                data-device-icon="bi bi-plug-fill" 
                                data-device-color="#d35400">
                                <i class="bi bi-plug-fill text-warning"></i> Stopkontak Grounding
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="saklar" 
                                data-device-name="Saklar Ganda" 
                                data-device-icon="bi bi-toggle-on" 
                                data-device-color="#e67e22">
                                <i class="bi bi-toggle-on text-warning"></i> Saklar Lampu
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="lampu" 
                                data-device-name="Lampu Downlight" 
                                data-device-icon="bi bi-lightbulb-fill" 
                                data-device-color="#f1c40f">
                                <i class="bi bi-lightbulb-fill text-warning"></i> Lampu / Lighting
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="generator" 
                                data-device-name="Genset / UPS" 
                                data-device-icon="bi bi-fuel-pump-fill" 
                                data-device-color="#7f8c8d">
                                <i class="bi bi-fuel-pump-fill text-secondary"></i> Generator / UPS
                            </div>
                        </div>
                    </div>
                </div>

                <!-- JARINGAN & IT -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed p-2 small fw-bold text-primary" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#catNetwork">
                            <i class="bi bi-diagram-3 me-2"></i> Network & Datacenter
                        </button>
                    </h2>
                    <div id="catNetwork" class="accordion-collapse collapse" data-bs-parent="#paletteAccordion">
                        <div class="accordion-body p-1">
                            <div class="palette-item" draggable="true" 
                                data-device-type="router" 
                                data-device-name="Core Router" 
                                data-device-icon="bi bi-router-fill" 
                                data-device-color="#2980b9">
                                <i class="bi bi-router-fill text-primary"></i> Router Utama
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="switch" 
                                data-device-name="Switch Managed" 
                                data-device-icon="bi bi-hdd-network-fill" 
                                data-device-color="#3498db">
                                <i class="bi bi-hdd-network-fill text-primary"></i> Switch Managed
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="access_point" 
                                data-device-name="Wi-Fi AP" 
                                data-device-icon="bi bi-wifi" 
                                data-device-color="#1abc9c">
                                <i class="bi bi-wifi text-info"></i> Access Point
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="server" 
                                data-device-name="Server Rack 42U" 
                                data-device-icon="bi bi-server" 
                                data-device-color="#2c3e50">
                                <i class="bi bi-server text-dark"></i> Server Rack
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="patch_panel" 
                                data-device-name="Patch Panel" 
                                data-device-icon="bi bi-grid-3x3-gap-fill" 
                                data-device-color="#16a085">
                                <i class="bi bi-grid-3x3-gap-fill text-success"></i> Patch Panel
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CCTV & SECURITY -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed p-2 small fw-bold text-dark" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#catSecurity">
                            <i class="bi bi-shield-lock me-2"></i> CCTV & Security
                        </button>
                    </h2>
                    <div id="catSecurity" class="accordion-collapse collapse" data-bs-parent="#paletteAccordion">
                        <div class="accordion-body p-1">
                            <div class="palette-item" draggable="true" 
                                data-device-type="cctv" 
                                data-device-name="CCTV IP Camera" 
                                data-device-icon="bi bi-webcam-fill" 
                                data-device-color="#c0392b">
                                <i class="bi bi-webcam-fill text-danger"></i> CCTV Dome / Bullet
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="dvr_nvr" 
                                data-device-name="NVR Storage" 
                                data-device-icon="bi bi-box-seam-fill" 
                                data-device-color="#8e44ad">
                                <i class="bi bi-box-seam-fill text-purple"></i> NVR / DVR Unit
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="door_access" 
                                data-device-name="Access Control" 
                                data-device-icon="bi bi-door-closed-fill" 
                                data-device-color="#27ae60">
                                <i class="bi bi-door-closed-fill text-success"></i> Door Access RFID
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="alarm" 
                                data-device-name="Sirine Alarm" 
                                data-device-icon="bi bi-bell-fill" 
                                data-device-color="#e67e22">
                                <i class="bi bi-bell-fill text-warning"></i> Sirine Panic Button
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PLUMBING -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed p-2 small fw-bold text-info" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#catPlumbing">
                            <i class="bi bi-droplet me-2"></i> Plumbing & Piping
                        </button>
                    </h2>
                    <div id="catPlumbing" class="accordion-collapse collapse" data-bs-parent="#paletteAccordion">
                        <div class="accordion-body p-1">
                            <div class="palette-item" draggable="true" 
                                data-device-type="pompa" 
                                data-device-name="Pompa Pendorong" 
                                data-device-icon="bi bi-droplet-fill" 
                                data-device-color="#2980b9">
                                <i class="bi bi-droplet-fill text-primary"></i> Pompa Air
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="tandon" 
                                data-device-name="Tandon Air 1000L" 
                                data-device-icon="bi bi-badge-wc-fill" 
                                data-device-color="#16a085">
                                <i class="bi bi-badge-wc-fill text-info"></i> Tandon Tank
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="valve" 
                                data-device-name="Valve Utama" 
                                data-device-icon="bi bi-sliders" 
                                data-device-color="#f39c12">
                                <i class="bi bi-sliders text-warning"></i> Valve / Stop Kran
                            </div>
                            <div class="palette-item" draggable="true" 
                                data-device-type="meteran" 
                                data-device-name="Meteran Air" 
                                data-device-icon="bi bi-speedometer2" 
                                data-device-color="#27ae60">
                                <i class="bi bi-speedometer2 text-success"></i> Meteran Air
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- CENTER CANVAS VIEWPORT -->
    <div class="designer-canvas-viewport" id="viewport">
        <div class="designer-canvas-container" id="designContainer" 
            data-layout-id="<?php echo e($layout->id); ?>"
            data-jobdesk="<?php echo e($jobdesk->slug); ?>"
            ondragover="event.preventDefault()" 
            ondrop="onCanvasDrop(event)">
            
            <img id="denahBgImg" 
                src="<?php echo e($layout->image_path ? asset('storage/' . $layout->image_path) : ''); ?>" 
                class="denah-bg" 
                style="<?php echo e(!$layout->image_path ? 'display:none;' : ''); ?>"
                alt="Background Denah">
        </div>
        
        <!-- Zoom Controls -->
        <div class="zoom-controls">
            <button onclick="zoomIn()" title="Zoom In"><i class="bi bi-plus-lg"></i></button>
            <button onclick="zoomOut()" title="Zoom Out"><i class="bi bi-dash-lg"></i></button>
            <button onclick="resetZoom()" title="Reset Zoom"><i class="bi bi-house"></i></button>
        </div>
        <div class="zoom-level" id="zoomLevelDisplay">100%</div>
    </div>

    <!-- RIGHT OBJECT LIST & PROPERTIES PANEL -->
    <div class="object-panel">
        <div class="p-2 border-bottom bg-light fw-bold text-center small text-secondary">
            <i class="bi bi-layers"></i> OBJECT TREE & INVENTORY
        </div>
        
        <div class="p-2 border-bottom">
            <input type="text" id="searchObjectInput" class="form-control form-control-sm" placeholder="Cari object / node..." onkeyup="filterObjectTree()">
        </div>

        <div class="palette-scroll p-0" id="objectTreeList">
            <!-- Node items injected dynamically -->
        </div>

        <div class="p-2 border-top bg-light" id="activeNodeProps" style="display: none;">
            <h6 class="fw-bold mb-2 small text-primary">Edit Selected Node</h6>
            <div class="mb-2">
                <label class="form-label mb-0 small" style="font-size:10px;">Label Node:</label>
                <input type="text" id="propNodeName" class="form-control form-control-sm">
            </div>
            <div class="mb-2">
                <label class="form-label mb-0 small" style="font-size:10px;">Link Hardware Inventaris:</label>
                <select id="propNodeInventaris" class="form-select form-select-sm">
                    <option value="">-- Tanpa Hardware --</option>
                    <?php $__currentLoopData = $inventaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($inv->id); ?>"><?php echo e($inv->nama_barang); ?> (<?php echo e($inv->kode_barang); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button onclick="updateNodeProperties()" class="btn btn-sm btn-primary w-100" style="font-size: 11px;">Update Node</button>
        </div>
    </div>
    <!-- ============================================ -->
    <!-- MODAL KONFIRMASI DELETE DEVICE -->
    <!-- ============================================ -->
    <div class="modal fade" id="deleteDeviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: #dc3545; color: white;">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Hapus Device
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-trash3-fill" style="font-size: 48px; color: #dc3545;"></i>
                    </div>
                    <p class="text-center">
                        Apakah Anda yakin ingin menghapus device 
                        <strong id="deleteDeviceName" class="text-danger">"Unknown"</strong>?
                    </p>
                    <p class="text-center text-muted small">
                        <i class="bi bi-info-circle"></i> 
                        Semua koneksi yang terhubung dengan device ini juga akan dihapus.
                    </p>
                    <div class="alert alert-warning small mb-0">
                        <i class="bi bi-exclamation-triangle"></i> 
                        Tindakan ini tidak dapat dibatalkan!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteDevice">
                        <i class="bi bi-trash3"></i> Ya, Hapus Device
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LIBRARIES -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsPlumb/2.15.6/js/jsplumb.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    (function() {
        'use strict';

        console.log('🚀 Designer page loading...');

        // ============================================
        // DOM ELEMENTS
        // ============================================
        const container = document.getElementById('designContainer');
        const viewport = document.getElementById('viewport');
        const bgImg = document.getElementById('denahBgImg');

        // VALIDASI: Cek apakah element penting ada
        if (!container) {
            console.error('❌ Container #designContainer not found!');
            return;
        }

        if (!viewport) {
            console.error('❌ Viewport #viewport not found!');
            return;
        }

        console.log('✅ Container found:', container);
        console.log('✅ Viewport found:', viewport);
        console.log('✅ Background image:', bgImg);

        // ============================================
        // DATA DARI BLADE
        // ============================================
        const jobdesk = container.dataset.jobdesk || '';
        const layoutId = container.dataset.layoutId || '';

        if (!jobdesk || !layoutId) {
            console.error('❌ Missing jobdesk or layoutId data attributes');
        }

        console.log('📋 Jobdesk:', jobdesk);
        console.log('📋 Layout ID:', layoutId);

        // ============================================
        // STATE
        // ============================================
        let jsPlumbInstance = null;
        let currentSelectedNodeId = null;
        let layoutDataStore = { devices: [], connections: [] };
        let currentZoom = 1;
        let panX = 0;
        let panY = 0;
        let isPanning = false;
        let startPanX = 0;
        let startPanY = 0;
        let isLoading = false;

        // ============================================
        // FUNGSI UTAMA
        // ============================================

        // LOAD DATA
        function loadLayoutData() {
            if (isLoading) return;
            isLoading = true;

            console.log('🔄 Loading layout data...');

            const url = `/${jobdesk}/layout/${layoutId}/data?t=${Date.now()}`;
            
            fetch(url, {
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.json();
            })
            .then(data => {
                console.log('✅ Data received:', data);
                
                let devices = [];
                let connections = [];
                
                // ============================================
                // PERBAIKAN: Ambil devices seperti di show.blade
                // ============================================
                if (data.layout && data.layout.devices) {
                    devices = data.layout.devices;
                    connections = data.layout.connections || [];
                    console.log('📦 From layout.devices:', devices.length);
                } else if (data.devices) {
                    devices = data.devices;
                    connections = data.connections || [];
                    console.log('📦 From data.devices:', devices.length);
                } else if (Array.isArray(data)) {
                    devices = data;
                    console.log('📦 Data is array:', devices.length);
                }
                
                console.log('📦 Devices:', devices);
                
                // Simpan ke store
                layoutDataStore = {
                    devices: devices,
                    connections: connections
                };
                
                // Render
                if (jsPlumbInstance) {
                    renderDesign(layoutDataStore);
                    buildObjectTree(devices);
                }
                
                isLoading = false;
            })
            .catch(error => {
                console.error('❌ Load data error:', error);
                alert('Gagal memuat data layout.');
                isLoading = false;
            });
        }

        // RENDER DESIGN
        function renderDesign(data) {
            console.log('🎨 Rendering design with data:', data);
            
            let devices = [];
            let connections = [];
            
            // Ambil devices dari berbagai kemungkinan struktur
            if (data.layout && data.layout.devices) {
                devices = data.layout.devices;
                connections = data.layout.connections || [];
            } else if (data.devices) {
                devices = data.devices;
                connections = data.connections || [];
            } else if (Array.isArray(data)) {
                devices = data;
            }
            
            console.log(`📦 Rendering ${devices.length} devices`);

            // Hapus semua device items lama
            const oldDevices = container.querySelectorAll('.device-item');
            oldDevices.forEach(el => el.remove());

            const emptyMsg = document.getElementById('emptyStateMsg');
            if (emptyMsg) emptyMsg.remove();

            if (!devices || devices.length === 0) {
                console.log('ℹ️ No devices to render');
                showEmptyState();
                return;
            }

            // ============================================
            // PERBAIKAN: Gunakan posisi yang sama dengan show.blade
            // ============================================
            devices.forEach((device, index) => {
                try {
                    const el = document.createElement('div');
                    el.className = 'device-item';
                    el.id = `device-${device.id}`;
                    
                    // Gunakan posisi langsung dari database (tanpa modifikasi)
                    const posX = parseFloat(device.pos_x) || 0;
                    const posY = parseFloat(device.pos_y) || 0;
                    const rotation = parseInt(device.rotation) || 0;

                    // Set posisi langsung (tidak perlu dikali zoom karena container yang di-scale)
                    el.style.left = `${posX}px`;
                    el.style.top = `${posY}px`;
                    el.style.background = device.color || '#3498db';
                    el.setAttribute('data-rotation', rotation);
                    el.setAttribute('data-device-id', device.id);
                    el.setAttribute('data-device-type', device.tipe_device || 'unknown');

                    // CCTV cone
                    let cctvConeHtml = '';
                    let rotateBtnHtml = '';
                    
                    if (device.tipe_device === 'cctv') {
                        el.classList.add('cctv-device');
                        cctvConeHtml = `<div class="cctv-cone" style="transform: rotate(${rotation}deg);"></div>`;
                        rotateBtnHtml = `<button class="device-action device-rotate" onclick="window.rotateCctv(${device.id})" title="Putar">↻</button>`;
                    }

                    el.innerHTML = `
                        ${cctvConeHtml}
                        ${rotateBtnHtml}
                        <button class="device-action device-delete" onclick="window.deleteDevice(${device.id})" title="Hapus">×</button>
                        <span class="device-icon"><i class="${device.icon || 'bi bi-geo-alt'}"></i></span>
                        <span class="device-name">${device.nama_device || 'Unknown'}</span>
                    `;

                    el.onclick = (evt) => {
                        evt.stopPropagation();
                        selectNode(device);
                    };

                    container.appendChild(el);
                    
                    // Make draggable dengan containment yang benar
                    jsPlumbInstance.draggable(el, { 
                        containment: true,
                        grid: [5, 5]
                    });

                    // Add endpoints
                    ["Top", "Bottom", "Left", "Right"].forEach(anchor => {
                        jsPlumbInstance.addEndpoint(el, {
                            anchor: anchor,
                            isSource: true,
                            isTarget: true,
                            maxConnections: -1,
                            endpoint: ["Dot", { radius: 5 }],
                            paintStyle: { fill: "#2ecc71" }
                        });
                    });

                    console.log(`✅ Device ${device.id} at (${posX}, ${posY})`);

                } catch (err) {
                    console.error('❌ Error rendering device:', device, err);
                }
            });

            // Render connections
            connections.forEach(conn => {
                try {
                    const sourceEl = document.getElementById(`device-${conn.device_from_id}`);
                    const targetEl = document.getElementById(`device-${conn.device_to_id}`);

                    if (sourceEl && targetEl) {
                        const connection = jsPlumbInstance.connect({
                            source: sourceEl,
                            target: targetEl,
                            paintStyle: { 
                                stroke: conn.warna || '#34495e', 
                                strokeWidth: 3
                            }
                        });

                        if (connection) {
                            connection.setData({ db_id: conn.id });
                            const labelText = conn.label || (conn.panjang_meter ? `${conn.panjang_meter}m` : '');
                            if (labelText) {
                                const overlay = connection.getOverlay("label");
                                if (overlay) overlay.setLabel(labelText);
                            }
                        }
                    }
                } catch (err) {
                    console.error('❌ Error rendering connection:', conn, err);
                }
            });

            if (jsPlumbInstance) {
                jsPlumbInstance.revalidate(container);
            }
            
            const rendered = container.querySelectorAll('.device-item').length;
            console.log(`✅ Render complete: ${rendered} devices in DOM`);
        }

        // BUILD OBJECT TREE
        function buildObjectTree(devices) {
            const treeList = document.getElementById('objectTreeList');
            if (!treeList) return;

            treeList.innerHTML = '';

            if (!devices || devices.length === 0) {
                treeList.innerHTML = `<div class="p-3 text-center text-muted small">Belum ada node dimasukkan</div>`;
                return;
            }

            devices.forEach(dev => {
                const item = document.createElement('div');
                item.className = 'object-list-item';
                item.id = `tree-item-${dev.id}`;
                item.innerHTML = `
                    <div>
                        <i class="${dev.icon} me-1" style="color: ${dev.color}"></i>
                        <strong>${dev.nama_device}</strong>
                    </div>
                    <span class="badge bg-light text-dark border" style="font-size: 9px;">${dev.tipe_device}</span>
                `;
                item.onclick = () => selectNode(dev);
                treeList.appendChild(item);
            });
        }

        // SELECT NODE
        function selectNode(device) {
            currentSelectedNodeId = device.id;
            
            document.querySelectorAll('.device-item').forEach(el => {
                el.classList.remove('active-selected');
            });
            
            const selectedEl = document.getElementById(`device-${device.id}`);
            if (selectedEl) {
                selectedEl.classList.add('active-selected');
            }

            const propsPanel = document.getElementById('activeNodeProps');
            if (propsPanel) {
                propsPanel.style.display = 'block';
                document.getElementById('propNodeName').value = device.nama_device || '';
                document.getElementById('propNodeInventaris').value = device.inventaris_id || '';
            }
        }

        // ============================================
        // ZOOM & PAN
        // ============================================
        function applyTransform() {
            container.style.transform = `translate(${panX}px, ${panY}px) scale(${currentZoom})`;
            const zoomDisplay = document.getElementById('zoomLevelDisplay');
            if (zoomDisplay) {
                zoomDisplay.textContent = Math.round(currentZoom * 100) + '%';
            }
            if (jsPlumbInstance) {
                jsPlumbInstance.revalidate(container);
            }
        }

        window.zoomIn = function() {
            currentZoom = Math.min(3, currentZoom + 0.1);
            applyTransform();
        };

        window.zoomOut = function() {
            currentZoom = Math.max(0.1, currentZoom - 0.1);
            applyTransform();
        };

        window.resetZoom = function() {
            currentZoom = 1;
            panX = 0;
            panY = 0;
            applyTransform();
        };

        // ============================================
        // BACKGROUND
        // ============================================
        window.updateBgOpacity = function(val) {
            const bgImg = document.getElementById('denahBgImg');
            if (bgImg) {
                bgImg.style.opacity = val;
            }
        };

        window.uploadBgImage = function(input) {
            if (!input || !input.files || !input.files[0]) {
                alert('Silakan pilih file gambar.');
                return;
            }

            const file = input.files[0];
            
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                input.value = '';
                return;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung.');
                input.value = '';
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            const btn = input.closest('label');
            let originalHtml = btn ? btn.innerHTML : '';

            if (btn) {
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Uploading...';
                btn.disabled = true;
            }

            fetch(`/${jobdesk}/layout/${layoutId}/upload-bg?t=${Date.now()}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error('Upload failed');
                return res.json();
            })
            .then(data => {
                if (data.success && data.image_url) {
                    const bgImg = document.getElementById('denahBgImg');
                    if (bgImg) {
                        bgImg.src = data.image_url + '?t=' + Date.now();
                        bgImg.style.display = 'block';
                        alert('✅ Gambar denah berhasil diupload!');
                    }
                } else {
                    alert('❌ Gagal upload: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                alert('❌ Gagal upload gambar: ' + error.message);
            })
            .finally(() => {
                if (btn) {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
                input.value = '';
            });
        };

        // ============================================
        // LINE STYLE
        // ============================================
        window.updateLineStyle = function() {
            if (!jsPlumbInstance) return;

            const type = document.getElementById('lineTypeSelect')?.value || 'Flowchart';
            const corner = parseInt(document.getElementById('lineCornerInput')?.value) || 12;
            const width = parseInt(document.getElementById('lineWidthInput')?.value) || 3;
            const color = document.getElementById('lineColorInput')?.value || '#34495e';

            let connectorConfig = ["Flowchart", { cornerRadius: corner, stub: 15 }];
            if (type === "Bezier") {
                connectorConfig = ["Bezier", { curviness: 50 }];
            } else if (type === "Straight") {
                connectorConfig = ["Straight", { stub: 0 }];
            }

            jsPlumbInstance.importDefaults({
                Connector: connectorConfig,
                PaintStyle: { stroke: color, strokeWidth: width }
            });

            jsPlumbInstance.getAllConnections().forEach(conn => {
                conn.setConnector(connectorConfig);
                conn.setPaintStyle({ stroke: color, strokeWidth: width });
            });
        };

        // ============================================
        // EXPORT
        // ============================================
        window.exportToImage = function() {
            document.querySelectorAll('.jtk-endpoint').forEach(ep => ep.style.display = 'none');
            const originalTransform = container.style.transform;
            container.style.transform = 'none';
            
            html2canvas(container, {
                useCORS: true,
                allowTaint: true,
                scale: 2,
                backgroundColor: '#f8f9fa',
                width: 3000,
                height: 2000
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `Engineering-Layout-${layoutId}.png`;
                link.href = canvas.toDataURL("image/png");
                link.click();
                container.style.transform = originalTransform;
                document.querySelectorAll('.jtk-endpoint').forEach(ep => ep.style.display = 'block');
            }).catch(error => {
                console.error('Export error:', error);
                alert('Gagal export PNG.');
                container.style.transform = originalTransform;
                document.querySelectorAll('.jtk-endpoint').forEach(ep => ep.style.display = 'block');
            });
        };

        window.exportToPDF = function() {
            document.querySelectorAll('.jtk-endpoint').forEach(ep => ep.style.display = 'none');
            const originalTransform = container.style.transform;
            container.style.transform = 'none';
            
            html2canvas(container, {
                useCORS: true,
                allowTaint: true,
                scale: 2,
                backgroundColor: '#f8f9fa',
                width: 3000,
                height: 2000
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('l', 'mm', 'a4');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = pdf.internal.pageSize.getHeight();
                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                pdf.save(`Engineering-Layout-${layoutId}.pdf`);
                container.style.transform = originalTransform;
                document.querySelectorAll('.jtk-endpoint').forEach(ep => ep.style.display = 'block');
            }).catch(error => {
                console.error('PDF export error:', error);
                alert('Gagal export PDF.');
                container.style.transform = originalTransform;
                document.querySelectorAll('.jtk-endpoint').forEach(ep => ep.style.display = 'block');
            });
        };

        // ============================================
        // DRAG & DROP
        // ============================================
        window.onCanvasDrop = function(e) {
            e.preventDefault();
            const rawData = e.dataTransfer.getData('deviceData');
            if (!rawData) return;

            try {
                const data = JSON.parse(rawData);
                const rect = container.getBoundingClientRect();
                const zoom = currentZoom || 1;
                
                const posX = Math.round((e.clientX - rect.left - panX) / zoom - 30);
                const posY = Math.round((e.clientY - rect.top - panY) / zoom - 30);
                const clampedX = Math.max(0, Math.min(3000 - 100, posX));
                const clampedY = Math.max(0, Math.min(2000 - 100, posY));

                fetch(`/${jobdesk}/layout/${layoutId}/device`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tipe_device: data.type,
                        nama_device: data.name,
                        icon: data.icon,
                        color: data.color,
                        pos_x: clampedX,
                        pos_y: clampedY
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Failed to add device');
                    return res.json();
                })
                .then(() => {
                    loadLayoutData();
                })
                .catch(error => {
                    console.error('Add device error:', error);
                    alert('Gagal menambahkan device.');
                });
            } catch (error) {
                console.error('Error parsing device data:', error);
            }
        };

        // ============================================
        // SAVE
        // ============================================
        window.saveLayout = function() {
            const updatedDevices = [];
            let hasError = false;
            
            document.querySelectorAll('.device-item').forEach(el => {
                const id = el.id.replace('device-', '');
                const left = parseInt(el.style.left, 10) || 0;
                const top = parseInt(el.style.top, 10) || 0;
                const rotation = parseInt(el.getAttribute('data-rotation'), 10) || 0;
                
                if (isNaN(left) || isNaN(top)) {
                    hasError = true;
                    return;
                }
                
                updatedDevices.push({
                    id: id,
                    pos_x: Math.max(0, Math.min(3000, left)),
                    pos_y: Math.max(0, Math.min(2000, top)),
                    rotation: rotation
                });
            });

            if (hasError) {
                alert('Ada posisi node yang tidak valid.');
                return;
            }

            fetch(`/${jobdesk}/layout/update-positions`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ devices: updatedDevices })
            })
            .then(res => {
                if (!res.ok) throw new Error('Failed to save');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    alert('✅ Diagram & Posisi Node Berhasil Disimpan!');
                } else {
                    alert('❌ Gagal menyimpan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Save error:', error);
                alert('❌ Gagal menyimpan layout.');
            });
        };

        // ============================================
        // DELETE & ROTATE
        // ============================================
        window.deleteDevice = function(id) {
            if (!confirm('Hapus node device ini?')) return;
            
            fetch(`/${jobdesk}/layout/device/${id}`, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Failed to delete');
                return res.json();
            })
            .then(() => {
                loadLayoutData();
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('Gagal menghapus device.');
            });
        };

        window.rotateCctv = function(id) {
            const el = document.getElementById(`device-${id}`);
            if (!el) return;
            
            const cone = el.querySelector('.cctv-cone');
            if (!cone) return;

            let currentRot = parseInt(el.getAttribute('data-rotation'), 10) || 0;
            currentRot = (currentRot + 45) % 360;

            el.setAttribute('data-rotation', currentRot);
            cone.style.transform = `rotate(${currentRot}deg)`;
            
            if (jsPlumbInstance) {
                jsPlumbInstance.revalidate(el);
            }
        };

        // ============================================
        // FILTER
        // ============================================
        window.filterObjectTree = function() {
            const term = document.getElementById('searchObjectInput')?.value.toLowerCase().trim() || '';
            document.querySelectorAll('.object-list-item').forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(term) ? 'flex' : 'none';
            });
        };

        // ============================================
        // SERVICE WORKER (NONAKTIFKAN)
        // ============================================
        // Service Worker dinonaktifkan untuk menghindari masalah cache
        console.log('ℹ️ Service Worker disabled');

        // ============================================
        // INITIALIZATION
        // ============================================
        function init() {
            console.log('🚀 Initializing designer...');

            // Setup palette drag events
            document.querySelectorAll('.palette-item').forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    const data = {
                        type: this.dataset.deviceType,
                        name: this.dataset.deviceName,
                        icon: this.dataset.deviceIcon,
                        color: this.dataset.deviceColor
                    };
                    e.dataTransfer.setData('deviceData', JSON.stringify(data));
                    e.dataTransfer.effectAllowed = 'copy';
                });
            });

            // Setup viewport panning
            viewport.addEventListener('mousedown', function(e) {
                if (e.target === this || e.target === container) {
                    isPanning = true;
                    startPanX = e.clientX - panX;
                    startPanY = e.clientY - panY;
                    viewport.style.cursor = 'grabbing';
                }
            });

            document.addEventListener('mousemove', function(e) {
                if (isPanning) {
                    panX = e.clientX - startPanX;
                    panY = e.clientY - startPanY;
                    applyTransform();
                }
            });

            document.addEventListener('mouseup', function() {
                if (isPanning) {
                    isPanning = false;
                    viewport.style.cursor = 'grab';
                }
            });

            // Mouse wheel zoom
            viewport.addEventListener('wheel', function(e) {
                e.preventDefault();
                const delta = e.deltaY > 0 ? -0.1 : 0.1;
                const newZoom = Math.min(3, Math.max(0.1, currentZoom + delta));
                const rect = viewport.getBoundingClientRect();
                const mouseX = e.clientX - rect.left;
                const mouseY = e.clientY - rect.top;
                
                const scaleRatio = newZoom / currentZoom;
                panX = mouseX - (mouseX - panX) * scaleRatio;
                panY = mouseY - (mouseY - panY) * scaleRatio;
                currentZoom = newZoom;
                applyTransform();
            }, { passive: false });

            // Initialize jsPlumb
            jsPlumb.ready(function() {
                console.log('🔄 jsPlumb ready');
                
                jsPlumbInstance = jsPlumb.getInstance({
                    Endpoint: ["Dot", { radius: 5 }],
                    EndpointStyle: { fill: "#2ecc71" },
                    PaintStyle: { stroke: "#34495e", strokeWidth: 3 },
                    HoverPaintStyle: { stroke: "#e74c3c", strokeWidth: 5 },
                    ConnectionOverlays: [
                        ["Arrow", { location: 1, width: 10, length: 10 }],
                        ["Label", { label: "", id: "label", cssClass: "connection-label" }]
                    ],
                    Container: "designContainer"
                });

                console.log('✅ jsPlumb instance created');

                // Setup line style
                window.updateLineStyle();

                // Bind connection event
                // Di bagian init jsPlumb
                jsPlumbInstance.bind("connection", function(info, originalEvent) {
                    console.log('🔗 Connection event triggered:', info);
                    
                    // Cegah jika ini bukan event dari user (drag)
                    if (!originalEvent) {
                        console.log('ℹ️ Connection from restore, skipping save');
                        return;
                    }
                    
                    // Cek apakah source dan target valid
                    if (!info.sourceId || !info.targetId) {
                        console.warn('⚠️ Invalid source or target');
                        return;
                    }
                    
                    const fromId = info.sourceId.replace('device-', '');
                    const toId = info.targetId.replace('device-', '');
                    
                    // Cegah self-connection
                    if (fromId === toId) {
                        console.warn('⚠️ Self-connection detected, removing...');
                        jsPlumbInstance.deleteConnection(info.connection);
                        alert('❌ Tidak bisa menghubungkan device ke dirinya sendiri!');
                        return;
                    }
                    
                    // Cek apakah sudah ada koneksi yang sama
                    const existing = jsPlumbInstance.getAllConnections().some(conn => {
                        if (conn === info.connection) return false;
                        const sId = conn.source?.id?.replace('device-', '');
                        const tId = conn.target?.id?.replace('device-', '');
                        return (sId === fromId && tId === toId) || (sId === toId && tId === fromId);
                    });
                    
                    if (existing) {
                        console.warn('⚠️ Duplicate connection, removing...');
                        jsPlumbInstance.deleteConnection(info.connection);
                        alert('❌ Koneksi sudah ada!');
                        return;
                    }
                    
                    // Simpan ke database
                    saveNewConnection(fromId, toId, info.connection);
                });

                // ============================================
                // CEGAH CONNECTION SAAT LOAD DATA
                // ============================================
                jsPlumbInstance.bind("connection", function(info) {
                    // Jika connection memiliki data db_id, berarti ini dari restore
                    if (info.connection.getData()?.db_id) {
                        console.log('ℹ️ Restored connection, skipping save');
                        return;
                    }
                });

                // Delete connection on click
                jsPlumbInstance.bind("click", function(conn) {
                    if (confirm("Hapus rute jalur kabel ini?")) {
                        deleteConnection(conn);
                    }
                });

                jsPlumbInstance.setContainer(container);

                // Load data
                loadLayoutData();
            });
        }

        // ============================================
        // CONNECTION MANAGEMENT
        // ============================================
        function saveNewConnection(fromId, toId, connectionObj) {
            if (!fromId || !toId) {
                console.error('❌ Invalid connection IDs');
                return;
            }

            console.log('💾 Saving connection:', fromId, '->', toId);

            // ============================================
            // CEK DUPLIKAT DI UI
            // ============================================
            const allConnections = jsPlumbInstance.getAllConnections();
            const isDuplicate = allConnections.some(conn => {
                if (conn === connectionObj) return false;
                
                const sourceId = conn.source?.id?.replace('device-', '');
                const targetId = conn.target?.id?.replace('device-', '');
                return (sourceId === fromId && targetId === toId) || 
                    (sourceId === toId && targetId === fromId);
            });

            if (isDuplicate) {
                console.warn('⚠️ Duplicate connection detected, removing...');
                jsPlumbInstance.deleteConnection(connectionObj);
                alert('❌ Koneksi sudah ada!');
                return;
            }

            // ============================================
            // CEK DUPLIKAT DI DATABASE VIA API
            // ============================================
            const color = document.getElementById('lineColorInput')?.value || '#34495e';

            fetch(`/${jobdesk}/layout/${layoutId}/connection`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    device_from_id: parseInt(fromId), 
                    device_to_id: parseInt(toId), 
                    warna: color,
                    tipe_kabel: 'standard',
                    panjang_meter: 0,
                    label: ''
                })
            })
            .then(async res => {
                console.log('📥 Response status:', res.status);
                const data = await res.json();
                
                if (res.status === 409) {
                    // Koneksi sudah ada di database
                    console.warn('⚠️ Connection already exists in database');
                    jsPlumbInstance.deleteConnection(connectionObj);
                    alert('❌ Koneksi sudah ada!');
                    return null;
                }
                
                if (!res.ok) {
                    throw new Error(data.message || `HTTP ${res.status}`);
                }
                return data;
            })
            .then(data => {
                if (!data) return; // Sudah ditangani di atas
                
                console.log('✅ Connection saved:', data);
                if (data.success && data.connection_id) {
                    connectionObj.setData({ db_id: data.connection_id });
                    // Update warna
                    connectionObj.setPaintStyle({ stroke: color, strokeWidth: 3 });
                    alert('✅ Koneksi berhasil dibuat!');
                } else {
                    throw new Error(data.message || 'Unknown error');
                }
            })
            .catch(error => {
                console.error('❌ Save connection error:', error);
                // Hapus connection dari UI jika gagal
                if (connectionObj) {
                    jsPlumbInstance.deleteConnection(connectionObj);
                }
                // Jangan tampilkan alert lagi jika sudah ditangani
                if (!error.message.includes('already exists')) {
                    alert('❌ Gagal menyimpan koneksi: ' + error.message);
                }
            });
        }

        function deleteConnection(connectionObj) {
            const connData = connectionObj.getData();
            const connectionId = connData ? connData.db_id : null;

            if (connectionId) {
                fetch(`/${jobdesk}/layout/connection/${connectionId}`, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Failed to delete');
                    jsPlumbInstance.deleteConnection(connectionObj);
                })
                .catch(error => {
                    console.error('Delete connection error:', error);
                    alert('Gagal menghapus koneksi.');
                });
            } else {
                jsPlumbInstance.deleteConnection(connectionObj);
            }
        }

        // ============================================
        // UPDATE NODE PROPERTIES
        // ============================================
        window.updateNodeProperties = function() {
            if (!currentSelectedNodeId) {
                alert('Silakan pilih node terlebih dahulu.');
                return;
            }

            const name = document.getElementById('propNodeName')?.value.trim();
            if (!name) {
                alert('Nama node tidak boleh kosong.');
                return;
            }

            const invId = document.getElementById('propNodeInventaris')?.value || null;

            fetch(`/${jobdesk}/layout/device/${currentSelectedNodeId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nama_device: name,
                    inventaris_id: invId
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Failed to update');
                return res.json();
            })
            .then(() => {
                loadLayoutData();
                alert('✅ Node berhasil diupdate!');
            })
            .catch(error => {
                console.error('Update error:', error);
                alert('❌ Gagal update node.');
            });
        };

        // ============================================
        // RUN
        // ============================================
        // Tunggu DOM siap
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        // Cleanup
        window.addEventListener('beforeunload', function() {
            if (jsPlumbInstance) {
                jsPlumbInstance.reset();
            }
        });

        console.log('💡 Designer loaded successfully');
        console.log('💡 Commands: loadLayoutData(), zoomIn(), zoomOut(), resetZoom()');

    })();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/layout/design.blade.php ENDPATH**/ ?>