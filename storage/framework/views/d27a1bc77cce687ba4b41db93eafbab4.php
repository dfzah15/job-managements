

<?php $__env->startSection('title', 'Edit Inventaris'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-pencil"></i> Edit Inventaris</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('inventaris.update', $inventaris->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="row">
                <!-- Lokasi -->
                <div class="col-md-6 mb-3">
                    <label for="lokasi_id" class="form-label">
                        <i class="bi bi-geo-alt"></i> Lokasi <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?php $__errorArgs = ['lokasi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="lokasi_id" name="lokasi_id" required>
                        <option value="">Pilih Lokasi</option>
                        <?php $__currentLoopData = $lokasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>" <?php echo e(old('lokasi_id', $inventaris->lokasi_id) == $item->id ? 'selected' : ''); ?>>
                                <?php echo e($item->kode_lokasi); ?> - <?php echo e($item->nama_lokasi); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['lokasi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Nama Inventaris -->
                <div class="col-md-6 mb-3">
                    <label for="nama_inventaris" class="form-label">
                        <i class="bi bi-tag"></i> Nama Inventaris <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control <?php $__errorArgs = ['nama_inventaris'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="nama_inventaris" name="nama_inventaris" 
                           value="<?php echo e(old('nama_inventaris', $inventaris->nama_inventaris)); ?>" required>
                    <?php $__errorArgs = ['nama_inventaris'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Jenis -->
                <div class="col-md-4 mb-3">
                    <label for="jenis" class="form-label">
                        <i class="bi bi-list-ul"></i> Jenis <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?php $__errorArgs = ['jenis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="jenis" name="jenis" required>
                        <option value="">Pilih Jenis</option>
                        <option value="cctv" <?php echo e(old('jenis', $inventaris->jenis) == 'cctv' ? 'selected' : ''); ?>>CCTV</option>
                        <option value="dvr" <?php echo e(old('jenis', $inventaris->jenis) == 'dvr' ? 'selected' : ''); ?>>DVR</option>
                        <option value="monitor" <?php echo e(old('jenis', $inventaris->jenis) == 'monitor' ? 'selected' : ''); ?>>Monitor</option>
                        <option value="kabel" <?php echo e(old('jenis', $inventaris->jenis) == 'kabel' ? 'selected' : ''); ?>>Kabel</option>
                        <option value="konektor" <?php echo e(old('jenis', $inventaris->jenis) == 'konektor' ? 'selected' : ''); ?>>Konektor</option>
                    </select>
                    <?php $__errorArgs = ['jenis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Merk -->
                <div class="col-md-4 mb-3">
                    <label for="merk" class="form-label">
                        <i class="bi bi-building"></i> Merk
                    </label>
                    <input type="text" class="form-control <?php $__errorArgs = ['merk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="merk" name="merk" value="<?php echo e(old('merk', $inventaris->merk)); ?>">
                    <?php $__errorArgs = ['merk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Model -->
                <div class="col-md-4 mb-3">
                    <label for="model" class="form-label">
                        <i class="bi bi-code-square"></i> Model
                    </label>
                    <input type="text" class="form-control <?php $__errorArgs = ['model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="model" name="model" value="<?php echo e(old('model', $inventaris->model)); ?>">
                    <?php $__errorArgs = ['model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Jumlah -->
                <div class="col-md-4 mb-3">
                    <label for="jumlah" class="form-label">
                        <i class="bi bi-hash"></i> Jumlah <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control <?php $__errorArgs = ['jumlah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="jumlah" name="jumlah" value="<?php echo e(old('jumlah', $inventaris->jumlah)); ?>" required min="1">
                    <?php $__errorArgs = ['jumlah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Tanggal Pemasangan -->
                <div class="col-md-4 mb-3">
                    <label for="tanggal_pemasangan" class="form-label">
                        <i class="bi bi-calendar-plus"></i> Tanggal Pemasangan
                    </label>
                    <input type="date" class="form-control <?php $__errorArgs = ['tanggal_pemasangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="tanggal_pemasangan" name="tanggal_pemasangan" 
                           value="<?php echo e(old('tanggal_pemasangan', $inventaris->tanggal_pemasangan)); ?>">
                    <?php $__errorArgs = ['tanggal_pemasangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Status -->
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">
                        <i class="bi bi-circle"></i> Status <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="status" name="status" required>
                        <option value="aktif" <?php echo e(old('status', $inventaris->status) == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                        <option value="rusak" <?php echo e(old('status', $inventaris->status) == 'rusak' ? 'selected' : ''); ?>>Rusak</option>
                        <option value="perbaikan" <?php echo e(old('status', $inventaris->status) == 'perbaikan' ? 'selected' : ''); ?>>Perbaikan</option>
                        <option value="nonaktif" <?php echo e(old('status', $inventaris->status) == 'nonaktif' ? 'selected' : ''); ?>>Nonaktif</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Spesifikasi -->
                <div class="col-12 mb-3">
                    <label for="spesifikasi" class="form-label">
                        <i class="bi bi-gear"></i> Spesifikasi
                    </label>
                    <textarea class="form-control <?php $__errorArgs = ['spesifikasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              id="spesifikasi" name="spesifikasi" rows="3"><?php echo e(old('spesifikasi', $inventaris->spesifikasi)); ?></textarea>
                    <?php $__errorArgs = ['spesifikasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('inventaris.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Inventaris
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/inventaris/edit.blade.php ENDPATH**/ ?>