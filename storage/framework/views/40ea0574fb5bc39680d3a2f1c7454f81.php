

<?php $__env->startSection('title', 'Detail Checklist ' . ucfirst($jobdesk->name)); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="<?php echo e($jobdesk->icon); ?>"></i> 
        Detail Checklist <?php echo e(ucfirst($jobdesk->name)); ?>

    </h3>
    <div>
        <a href="<?php echo e(route('jobdesk.checklist.edit', [$jobdesk->slug, $checklist->id])); ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?php echo e(route('jobdesk.checklist.index', $jobdesk->slug)); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Utama -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-info-circle"></i> Informasi Checklist</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">ID Checklist</th>
                        <td><span class="badge bg-primary">#<?php echo e($checklist->id); ?></span></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>
                            <span class="badge bg-info"><?php echo e($checklist->lokasi->kode_lokasi ?? '-'); ?></span>
                            <?php echo e($checklist->lokasi->nama_lokasi ?? '-'); ?>

                        </td>
                    </tr>
                    <tr>
                        <th>Inventaris</th>
                        <td><?php echo e($checklist->inventaris->nama_inventaris ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Petugas</th>
                        <td><?php echo e($checklist->petugas_check); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Check</th>
                        <td><?php echo e($checklist->tanggal_check->format('d/m/Y')); ?></td>
                    </tr>
                    <tr>
                        <th>Waktu Check</th>
                        <td><?php echo e($checklist->waktu_check ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-<?php echo e($checklist->status == 'normal' ? 'success' : ($checklist->status == 'warning' ? 'warning' : 'danger')); ?>">
                                <?php echo e(ucfirst($checklist->status)); ?>

                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td><?php echo e($checklist->created_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Hasil Checklist -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-list-check"></i> Hasil Checklist</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th width="50%"><?php echo e($template->field_label); ?></th>
                        <td>
                            <?php
                                $value = $checklist->getField($template->field_name);
                            ?>
                            <?php if($template->field_type == 'checkbox'): ?>
                                <?php echo $value ? '<span class="badge bg-success">✅ Ya</span>' : '<span class="badge bg-secondary">❌ Tidak</span>'; ?>

                            <?php elseif($template->field_type == 'select'): ?>
                                <?php
                                    $options = $template->options ?? [];
                                    $label = $options[$value] ?? $value ?? '-';
                                    $isWarning = in_array($value, ['tidak_normal', 'rusak', 'error', 'offline', 'failed', 'down', 'rendah', 'kotor', 'bocor', 'overheat', 'trip', 'aus', 'putus', 'keruh', 'berbau', 'berlebihan', 'tidak', 'kurang', 'berlebihan']);
                                    $isDanger = in_array($value, ['rusak', 'error', 'offline', 'failed', 'down', 'bocor', 'overheat', 'trip', 'putus']);
                                ?>
                                <?php if($isDanger): ?>
                                    <span class="badge bg-danger"><?php echo e($label); ?></span>
                                <?php elseif($isWarning): ?>
                                    <span class="badge bg-warning"><?php echo e($label); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?php echo e($label); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo e($value ?? '-'); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
        </div>

        <!-- Catatan -->
        <?php if($checklist->catatan): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="bi bi-sticky"></i> Catatan</h6>
            </div>
            <div class="card-body">
                <?php echo e($checklist->catatan); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/checklist/show.blade.php ENDPATH**/ ?>