

<?php $__env->startSection('title', 'Edit Checklist ' . ucfirst($jobdesk->name)); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5><i class="<?php echo e($jobdesk->icon); ?>"></i> Edit Checklist <?php echo e(ucfirst($jobdesk->name)); ?></h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('jobdesk.checklist.update', [$jobdesk->slug, $checklist->id])); ?>" method="POST">
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
                            <option value="<?php echo e($item->id); ?>" <?php echo e(old('lokasi_id', $checklist->lokasi_id) == $item->id ? 'selected' : ''); ?>>
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

                <!-- Inventaris -->
                <div class="col-md-6 mb-3">
                    <label for="inventaris_id" class="form-label">
                        <i class="bi bi-box"></i> Inventaris
                    </label>
                    <select class="form-select <?php $__errorArgs = ['inventaris_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="inventaris_id" name="inventaris_id">
                        <option value="">Pilih Inventaris</option>
                        <?php $__currentLoopData = $inventaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>" <?php echo e(old('inventaris_id', $checklist->inventaris_id) == $item->id ? 'selected' : ''); ?>>
                                <?php echo e($item->nama_inventaris); ?> - <?php echo e($item->jenis); ?> (<?php echo e($item->merk ?? '-'); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['inventaris_id'];
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

                <!-- Tanggal Check -->
                <div class="col-md-4 mb-3">
                    <label for="tanggal_check" class="form-label">
                        <i class="bi bi-calendar"></i> Tanggal Check <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control <?php $__errorArgs = ['tanggal_check'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="tanggal_check" name="tanggal_check" 
                           value="<?php echo e(old('tanggal_check', $checklist->tanggal_check->format('Y-m-d'))); ?>" required>
                    <?php $__errorArgs = ['tanggal_check'];
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

                <!-- Waktu Check -->
                <div class="col-md-4 mb-3">
                    <label for="waktu_check" class="form-label">
                        <i class="bi bi-clock"></i> Waktu Check <span class="text-danger">*</span>
                    </label>
                    <input type="time" class="form-control <?php $__errorArgs = ['waktu_check'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="waktu_check" name="waktu_check" 
                           value="<?php echo e(old('waktu_check', $checklist->waktu_check)); ?>" required>
                    <?php $__errorArgs = ['waktu_check'];
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

                <!-- Petugas Check -->
                <div class="col-md-4 mb-3">
                    <label for="petugas_check" class="form-label">
                        <i class="bi bi-person"></i> Petugas Check <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control <?php $__errorArgs = ['petugas_check'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="petugas_check" name="petugas_check" 
                           value="<?php echo e(old('petugas_check', $checklist->petugas_check)); ?>" required>
                    <?php $__errorArgs = ['petugas_check'];
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

                <!-- ============================================ -->
                <!-- DINAMIS CHECKLIST PER JOBDESK -->
                <!-- ============================================ -->
                <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <?php echo e($template->field_label); ?>

                        <?php if($template->is_required): ?>
                            <span class="text-danger">*</span>
                        <?php endif; ?>
                    </label>

                    <?php
                        $oldValue = old('values.' . $template->field_name, $checklist->getField($template->field_name));
                    ?>

                    <?php switch($template->field_type):
                        case ('text'): ?>
                            <input type="text" class="form-control" 
                                   name="values[<?php echo e($template->field_name); ?>]" 
                                   value="<?php echo e($oldValue); ?>"
                                   placeholder="<?php echo e($template->placeholder ?? ''); ?>">
                            <?php break; ?>

                        <?php case ('number'): ?>
                            <input type="number" class="form-control" 
                                   name="values[<?php echo e($template->field_name); ?>]" 
                                   value="<?php echo e($oldValue); ?>"
                                   placeholder="<?php echo e($template->placeholder ?? ''); ?>"
                                   step="any">
                            <?php break; ?>

                        <?php case ('select'): ?>
                            <select class="form-select" name="values[<?php echo e($template->field_name); ?>]">
                                <option value="">Pilih</option>
                                <?php $__currentLoopData = $template->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e($oldValue == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php break; ?>

                        <?php case ('checkbox'): ?>
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" 
                                       name="values[<?php echo e($template->field_name); ?>]" value="1"
                                       id="edit_<?php echo e($template->field_name); ?>"
                                       <?php echo e($oldValue ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="edit_<?php echo e($template->field_name); ?>">
                                    Ya
                                </label>
                            </div>
                            <?php break; ?>

                        <?php case ('textarea'): ?>
                            <textarea class="form-control" name="values[<?php echo e($template->field_name); ?>]" 
                                      rows="2" placeholder="<?php echo e($template->placeholder ?? ''); ?>"><?php echo e($oldValue); ?></textarea>
                            <?php break; ?>

                        <?php default: ?>
                            <input type="text" class="form-control" name="values[<?php echo e($template->field_name); ?>]" value="<?php echo e($oldValue); ?>">
                    <?php endswitch; ?>

                    <?php if($template->help_text): ?>
                        <small class="text-muted d-block"><?php echo e($template->help_text); ?></small>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- Catatan -->
                <div class="col-12 mb-3">
                    <label for="catatan" class="form-label">
                        <i class="bi bi-sticky"></i> Catatan
                    </label>
                    <textarea class="form-control <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              id="catatan" name="catatan" rows="3"><?php echo e(old('catatan', $checklist->catatan)); ?></textarea>
                    <?php $__errorArgs = ['catatan'];
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
                <a href="<?php echo e(route('jobdesk.checklist.index', $jobdesk->slug)); ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Checklist
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Informasi Tambahan -->
<div class="card mt-3">
    <div class="card-header">
        <h6><i class="bi bi-info-circle"></i> Informasi Checklist</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong><i class="bi bi-check-circle"></i> Kriteria Normal:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Semua item dalam kondisi normal</li>
                        <li>Tidak ada indikasi kerusakan</li>
                        <li>Semua nilai dalam batas normal</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-warning">
                    <strong><i class="bi bi-exclamation-triangle"></i> Perlu Perhatian:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Ada item yang perlu diperiksa ulang</li>
                        <li>Beberapa nilai di luar batas normal</li>
                        <li>Perlu tindakan pencegahan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/checklist/edit.blade.php ENDPATH**/ ?>