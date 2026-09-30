

<?php $__env->startSection('title', 'Laporan Aktivitas - ' . ucfirst($jobdesk->name)); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="<?php echo e($jobdesk->icon); ?>"></i> 
        Laporan Aktivitas <?php echo e(ucfirst($jobdesk->name)); ?>

    </h3>
    <div>
        <a href="<?php echo e(route('laporan.create', $jobdesk->slug)); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Laporan
        </a>
        <a href="<?php echo e(route('laporan.export.excel', $jobdesk->slug)); ?>" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
        <a href="<?php echo e(route('laporan.export.pdf', $jobdesk->slug)); ?>" class="btn btn-danger">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-3">
    <div class="card-body">
        <form action="<?php echo e(route('laporan.index', $jobdesk->slug)); ?>" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari laporan..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <select name="lokasi_id" class="form-select">
                    <option value="">Semua Lokasi</option>
                    <?php $__currentLoopData = $lokasi ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($item->id); ?>" <?php echo e(request('lokasi_id') == $item->id ? 'selected' : ''); ?>>
                            <?php echo e($item->nama_lokasi); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = $statusOptions ?? ['selesai', 'pending', 'proses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php echo e(request('status') == $status ? 'selected' : ''); ?>>
                            <?php echo e(ucfirst($status)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>" placeholder="Dari">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>" placeholder="Sampai">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i>
                </button>
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
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Jml Rusak</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo e($item->lokasi->kode_lokasi ?? '-'); ?></span>
                            <?php echo e($item->lokasi->nama_lokasi ?? '-'); ?>

                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($item->jenis_aktivitas == 'perbaikan' ? 'danger' : ($item->jenis_aktivitas == 'pemeliharaan' ? 'warning' : 'info')); ?>">
                                <?php echo e(ucfirst($item->jenis_aktivitas)); ?>

                            </span>
                        </td>
                        <td><?php echo e(Str::limit($item->keterangan, 30)); ?></td>
                        <td>
                            <?php if($item->jumlah_rusak > 0): ?>
                                <span class="badge bg-danger"><?php echo e($item->jumlah_rusak); ?></span>
                            <?php else: ?>
                                <span class="badge bg-success">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                $statusClass = [
                                    'selesai' => 'success',
                                    'pending' => 'warning',
                                    'proses' => 'info'
                                ];
                            ?>
                            <span class="badge bg-<?php echo e($statusClass[$item->status_pekerjaan] ?? 'secondary'); ?>">
                                <?php echo e(ucfirst($item->status_pekerjaan)); ?>

                            </span>
                        </td>
                        <td><?php echo e($item->tanggal_laporan->format('d/m/Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('laporan.show', [$jobdesk->slug, $item->id])); ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo e(route('laporan.edit', [$jobdesk->slug, $item->id])); ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('laporan.destroy', [$jobdesk->slug, $item->id])); ?>" method="POST" class="d-inline delete-confirm">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada laporan aktivitas</p>
                                <a href="<?php echo e(route('laporan.create', $jobdesk->slug)); ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Laporan
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($laporan->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/laporan/index.blade.php ENDPATH**/ ?>