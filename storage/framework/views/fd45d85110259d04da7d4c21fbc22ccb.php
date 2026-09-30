

<?php $__env->startSection('title', 'Layout & Wiring Diagram - ' . ucfirst($jobdesk->name)); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="<?php echo e($jobdesk->icon); ?>"></i> 
        Layout & Wiring Diagram <?php echo e(ucfirst($jobdesk->name)); ?>

    </h3>
    <a href="<?php echo e(route('jobdesk.layout.create', $jobdesk->slug)); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Buat Layout Baru
    </a>
</div>

<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $layouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><?php echo e($layout->nama_layout); ?></h6>
                <span class="badge bg-info"><?php echo e($layout->devices->count()); ?> Device</span>
            </div>
            <div class="card-body">
                <?php if($layout->image_path): ?>
                    <img src="<?php echo e(asset('storage/' . $layout->image_path)); ?>" 
                         alt="<?php echo e($layout->nama_layout); ?>" 
                         class="img-fluid rounded" 
                         style="height: 150px; width: 100%; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                         style="height: 150px; background: #f8f9fa;">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                <?php endif; ?>
                <p class="mt-2 mb-1 small text-muted"><?php echo e($layout->deskripsi ?? 'Tidak ada deskripsi'); ?></p>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <span class="badge bg-success"><?php echo e($layout->connections->count()); ?> Koneksi</span>
                    </div>
                    <div>
                        <a href="<?php echo e(route('jobdesk.layout.design', [$jobdesk->slug, $layout->id])); ?>" class="btn btn-sm btn-info">
                            <i class="bi bi-pencil-square"></i> Design
                        </a>
                        <a href="<?php echo e(route('jobdesk.layout.show', [$jobdesk->slug, $layout->id])); ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-map fs-1 text-muted"></i>
                <p class="text-muted mt-2">Belum ada layout</p>
                <!-- ✅ PERBAIKAN: Pakai route jobdesk.layout.create -->
                <a href="<?php echo e(route('jobdesk.layout.create', $jobdesk->slug)); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Buat Layout Pertama
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php echo e($layouts->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/layout/index.blade.php ENDPATH**/ ?>