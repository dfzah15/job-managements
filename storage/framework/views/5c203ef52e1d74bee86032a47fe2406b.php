

<?php $__env->startSection('title', 'Data Inventaris'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-box"></i> Data Inventaris</h3>
    <a href="<?php echo e(route('inventaris.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Inventaris
    </a>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-3">
    <div class="card-body">
        <form action="<?php echo e(route('inventaris.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari nama, merk, model..." 
                       value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <select name="jobdesk_id" class="form-select">
                    <option value="">Semua Jobdesk</option>
                    <?php $__currentLoopData = $jobdesks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($item->id); ?>" <?php echo e(request('jobdesk_id') == $item->id ? 'selected' : ''); ?>>
                            <?php echo e($item->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="cctv" <?php echo e(request('jenis') == 'cctv' ? 'selected' : ''); ?>>CCTV</option>
                    <option value="dvr" <?php echo e(request('jenis') == 'dvr' ? 'selected' : ''); ?>>DVR</option>
                    <option value="monitor" <?php echo e(request('jenis') == 'monitor' ? 'selected' : ''); ?>>Monitor</option>
                    <option value="server" <?php echo e(request('jenis') == 'server' ? 'selected' : ''); ?>>Server</option>
                    <option value="genset" <?php echo e(request('jenis') == 'genset' ? 'selected' : ''); ?>>Genset</option>
                    <option value="pompa" <?php echo e(request('jenis') == 'pompa' ? 'selected' : ''); ?>>Pompa</option>
                    <option value="panel" <?php echo e(request('jenis') == 'panel' ? 'selected' : ''); ?>>Panel</option>
                    <option value="kabel" <?php echo e(request('jenis') == 'kabel' ? 'selected' : ''); ?>>Kabel</option>
                    <option value="konektor" <?php echo e(request('jenis') == 'konektor' ? 'selected' : ''); ?>>Konektor</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="rusak" <?php echo e(request('status') == 'rusak' ? 'selected' : ''); ?>>Rusak</option>
                    <option value="perbaikan" <?php echo e(request('status') == 'perbaikan' ? 'selected' : ''); ?>>Perbaikan</option>
                    <option value="nonaktif" <?php echo e(request('status') == 'nonaktif' ? 'selected' : ''); ?>>Nonaktif</option>
                </select>
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
                        <th>Jobdesk</th>
                        <th>Lokasi</th>
                        <th>Kode</th>
                        <th>Nama Inventaris</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $inventaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($item->jobdesk->color ?? 'secondary'); ?>">
                                <i class="<?php echo e($item->jobdesk->icon ?? 'bi bi-briefcase'); ?>"></i>
                                <?php echo e($item->jobdesk->name ?? '-'); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo e($item->lokasi->kode_lokasi ?? '-'); ?></span>
                            <?php echo e($item->lokasi->nama_lokasi ?? '-'); ?>

                        </td>
                        <td><code><?php echo e($item->kode_inventaris); ?></code></td>
                        <td><?php echo e($item->nama_inventaris); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($item->jenis_badge); ?>"><?php echo e(strtoupper($item->jenis)); ?></span>
                        </td>
                        <td><span class="badge bg-primary"><?php echo e($item->jumlah); ?></span></td>
                        <td>
                            <span class="badge bg-<?php echo e($item->status_badge); ?>">
                                <?php echo e(ucfirst($item->status)); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('inventaris.show', $item->id)); ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo e(route('inventaris.edit', $item->id)); ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('inventaris.destroy', $item->id)); ?>" method="POST" class="d-inline delete-confirm">
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
                        <td colspan="9" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data inventaris</p>
                                <a href="<?php echo e(route('inventaris.create')); ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Inventaris
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($inventaris->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/inventaris/index.blade.php ENDPATH**/ ?>