

<?php $__env->startSection('title', 'Laporan Eksekusi - ' . ucfirst($jobdesk->name)); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="<?php echo e($jobdesk->icon); ?>"></i> 
        Laporan Eksekusi <?php echo e(ucfirst($jobdesk->name)); ?>

    </h3>
    <a href="<?php echo e(route('laporan-eksekusi.create', $jobdesk->slug)); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Eksekusi
    </a>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-3">
    <div class="card-body">
        <form action="<?php echo e(route('laporan-eksekusi.index', $jobdesk->slug)); ?>" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari eksekusi..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = $statusOptions ?? ['pending', 'proses', 'selesai', 'gagal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
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
                        <th>Laporan</th>
                        <th>Teknisi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $eksekusi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo e($item->lokasi->kode_lokasi ?? '-'); ?></span>
                            <?php echo e($item->lokasi->nama_lokasi ?? '-'); ?>

                        </td>
                        <td><?php echo e(Str::limit($item->laporanAktivitas->keterangan ?? '-', 30)); ?></td>
                        <td><?php echo e($item->user->name ?? '-'); ?></td>
                        <td><?php echo e($item->tanggal_eksekusi->format('d/m/Y')); ?></td>
                        <td>
                            <?php
                                $statusColor = [
                                    'pending' => 'warning',
                                    'proses' => 'info',
                                    'selesai' => 'success',
                                    'gagal' => 'danger'
                                ];
                            ?>
                            <span class="badge bg-<?php echo e($statusColor[$item->status_eksekusi] ?? 'secondary'); ?>">
                                <?php echo e(ucfirst($item->status_eksekusi)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($item->gambar->count() > 0): ?>
                                <span class="badge bg-primary"><?php echo e($item->gambar->count()); ?> foto</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('laporan-eksekusi.show', [$jobdesk->slug, $item->id])); ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if($item->status_eksekusi == 'pending'): ?>
                                <a href="<?php echo e(route('laporan-eksekusi.start', [$jobdesk->slug, $item->id])); ?>" class="btn btn-sm btn-success" onclick="return confirm('Mulai eksekusi?')">
                                    <i class="bi bi-play"></i>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('laporan-eksekusi.edit', [$jobdesk->slug, $item->id])); ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('laporan-eksekusi.destroy', [$jobdesk->slug, $item->id])); ?>" method="POST" class="d-inline delete-confirm">
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
                                <p class="text-muted mt-2">Belum ada data eksekusi</p>
                                <a href="<?php echo e(route('laporan-eksekusi.create', $jobdesk->slug)); ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Eksekusi
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($eksekusi->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/laporan_eksekusi/index.blade.php ENDPATH**/ ?>