

<?php $__env->startSection('title', 'Checklist ' . ucfirst($jobdesk->name)); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="<?php echo e($jobdesk->icon); ?>"></i> 
        Checklist <?php echo e(ucfirst($jobdesk->name)); ?>

    </h3>
    <a href="<?php echo e(route('jobdesk.checklist.create', $jobdesk->slug)); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Checklist
    </a>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-3">
    <div class="card-body">
        <form action="<?php echo e(route('jobdesk.checklist.index', $jobdesk->slug)); ?>" method="GET" class="row g-3">
            <div class="row g-2 align-items-center">
                <!-- Input Search -->
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" 
                        placeholder="Cari petugas, lokasi..." value="<?php echo e(request('search')); ?>">
                </div>

                <!-- Dropdown Lokasi -->
                <div class="col-md-3">
                    <select name="lokasi_id" class="form-select">
                        <option value="">Semua Lokasi</option>
                        <?php $__currentLoopData = $lokasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>" <?php echo e(request('lokasi_id') == $item->id ? 'selected' : ''); ?>>
                                <?php echo e($item->nama_lokasi); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Input Tanggal -->
                <div class="col-md-2">
                    <input type="date" name="tanggal" class="form-control" value="<?php echo e(request('tanggal')); ?>" placeholder="Tanggal">
                </div>

                <!-- Tombol Cari -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('jobdesk.checklist.create', $jobdesk->slug)); ?>" class="btn btn-primary w-100 text-nowrap">
                            <i class="bi bi-plus-circle"></i> Tambah Checklist
                        </a>
                    </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lokasi</th>
                        <th>Petugas</th>
                        <th>Tanggal</th>
                        <!-- Field dinamis sesuai jobdesk -->
                        <?php
                            $templates = App\Models\ChecklistTemplate::where('jobdesk_id', $jobdesk->id)
                                                                     ->orderBy('sort_order')
                                                                     ->limit(4)
                                                                     ->get();
                        ?>
                        <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th><?php echo e(Str::limit($template->field_label, 15)); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $checklist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo e($item->lokasi->kode_lokasi ?? '-'); ?></span>
                            <?php echo e($item->lokasi->nama_lokasi ?? '-'); ?>

                        </td>
                        <td><?php echo e($item->petugas_check); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($item->tanggal_check)->format('d/m/Y')); ?></td>
                        <!-- Tampilkan nilai field dinamis -->
                        <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td>
                                <?php
                                    $value = $item->getField($template->field_name);
                                ?>
                                <?php if($template->field_type == 'checkbox'): ?>
                                    <?php echo $value ? '<span class="badge bg-success">✅</span>' : '<span class="badge bg-secondary">❌</span>'; ?>

                                <?php elseif($template->field_type == 'select'): ?>
                                    <?php
                                        $options = $template->options ?? [];
                                        $label = $options[$value] ?? $value ?? '-';
                                        $isDanger = in_array($value, ['rusak', 'error', 'offline', 'failed', 'down', 'bocor', 'overheat', 'trip', 'putus', 'tidak_normal', 'tidak', 'berlebihan', 'keruh', 'berbau']);
                                        $isWarning = in_array($value, ['warning', 'slow', 'running', 'perlu_ganti', 'kotor', 'rendah', 'kurang', 'berlebihan', 'berasap']);
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <td>
                            <span class="badge bg-<?php echo e($item->status == 'normal' ? 'success' : ($item->status == 'warning' ? 'warning' : 'danger')); ?>">
                                <?php echo e(ucfirst($item->status)); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('jobdesk.checklist.show', [$jobdesk->slug, $item->id])); ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo e(route('jobdesk.checklist.edit', [$jobdesk->slug, $item->id])); ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                                <form action="<?php echo e(route('jobdesk.checklist.destroy', [$jobdesk->slug, $item->id])); ?>"
                                    method="POST"
                                    class="d-inline">

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="hapusChecklist(this.form)">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="<?php echo e(6 + $templates->count()); ?>" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data checklist</p>
                                <!-- <a href="<?php echo e(route('jobdesk.checklist.create', $jobdesk->slug)); ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Checklist
                                </a> -->
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($checklist->withQueryString()->links('pagination::bootstrap-5')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/checklist/index.blade.php ENDPATH**/ ?>