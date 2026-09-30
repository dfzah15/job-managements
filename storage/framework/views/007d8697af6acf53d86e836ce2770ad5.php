

<?php $__env->startSection('title', 'View Layout - ' . $layout->nama_layout); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="<?php echo e($jobdesk->icon); ?>"></i> 
        <?php echo e($layout->nama_layout); ?>

    </h3>
    <div>
        <a href="<?php echo e(route('jobdesk.layout.design', [$jobdesk->slug, $layout->id])); ?>" class="btn btn-info">
            <i class="bi bi-pencil-square"></i> Edit Design
        </a>
        <a href="<?php echo e(route('jobdesk.layout.index', $jobdesk->slug)); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6>Denah & Layout</h6>
            </div>
            <div class="card-body p-0">
                <div class="position-relative" style="min-height: 600px; background: #f8f9fa; overflow: auto;">
                    <?php if($layout->image_path): ?>
                        <img src="<?php echo e(asset('storage/' . $layout->image_path)); ?>" 
                             alt="Denah" 
                             style="width: 100%; height: auto;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center" style="min-height: 400px;">
                            <p class="text-muted">Belum ada denah diupload</p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Devices di atas denah -->
                    <?php $__currentLoopData = $layout->devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="position: absolute; left: <?php echo e($device->pos_x); ?>px; top: <?php echo e($device->pos_y); ?>px; 
                                background: <?php echo e($device->color); ?>; 
                                padding: 8px 12px; border-radius: 6px; color: white; 
                                font-size: 12px; font-weight: bold;
                                transform: rotate(<?php echo e($device->rotation); ?>deg);
                                display: flex; flex-direction: column; align-items: center;
                                min-width: 60px; border: 2px solid rgba(255,255,255,0.3);
                                box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                        <i class="<?php echo e($device->icon); ?> fs-4"></i>
                        <span style="font-size: 10px; text-align: center;"><?php echo e($device->nama_device); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <!-- Connections -->
                    <svg style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
                        <?php $__currentLoopData = $layout->connections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $from = $layout->devices->where('id', $conn->device_from_id)->first();
                                $to = $layout->devices->where('id', $conn->device_to_id)->first();
                            ?>
                            <?php if($from && $to): ?>
                                <line x1="<?php echo e($from->pos_x + 30); ?>" y1="<?php echo e($from->pos_y + 30); ?>" 
                                      x2="<?php echo e($to->pos_x + 30); ?>" y2="<?php echo e($to->pos_y + 30); ?>"
                                      stroke="<?php echo e($conn->warna); ?>" 
                                      stroke-width="3"
                                      stroke-dasharray="<?php echo e($conn->tipe_kabel == 'fiber' ? '10,5' : '5,5'); ?>"
                                      stroke-linecap="round"/>
                                <text x="<?php echo e((($from->pos_x + $to->pos_x) / 2) + 30); ?>" 
                                      y="<?php echo e((($from->pos_y + $to->pos_y) / 2) - 10 + 30); ?>"
                                      font-size="10" fill="#333" text-anchor="middle">
                                    <?php echo e($conn->panjang_meter ? $conn->panjang_meter . 'm' : ''); ?>

                                </text>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/layout/show.blade.php ENDPATH**/ ?>