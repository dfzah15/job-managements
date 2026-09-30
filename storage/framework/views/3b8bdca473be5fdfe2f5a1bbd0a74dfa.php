

<?php $__env->startSection('title', 'Tambah Checklist ' . ucfirst($jobdesk->name)); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="<?php echo e($jobdesk->icon); ?> me-2 fs-4"></i>
                        <h5 class="mb-0">Form Checklist <?php echo e(ucfirst($jobdesk->name)); ?></h5>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?php echo e(route('jobdesk.checklist.store', $jobdesk->slug)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Informasi Utama -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 text-secondary">
                                    <i class="fas fa-info-circle me-1"></i> Informasi Dasar
                                </h6>
                            </div>
                            
                            <!-- Lokasi -->
                            <div class="col-md-6">
                                <label for="lokasi_id" class="form-label fw-semibold">
                                    Lokasi <span class="text-danger">*</span>
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
                                    <option value="">-- Pilih Lokasi --</option>
                                    <?php $__currentLoopData = $lokasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>" <?php echo e(old('lokasi_id') == $item->id ? 'selected' : ''); ?>>
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
                            <div class="col-md-6">
                                <label for="inventaris_id" class="form-label fw-semibold">
                                    Inventaris
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
                                    <option value="">-- Pilih Inventaris --</option>
                                    <?php $__currentLoopData = $inventaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>" <?php echo e(old('inventaris_id') == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->nama_inventaris); ?> - <?php echo e($item->jenis); ?>

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
                            <div class="col-md-4">
                                <label for="tanggal_check" class="form-label fw-semibold">
                                    Tanggal Check <span class="text-danger">*</span>
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
                                       value="<?php echo e(old('tanggal_check', date('Y-m-d'))); ?>" required>
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
                            <div class="col-md-4">
                                <label for="waktu_check" class="form-label fw-semibold">
                                    Waktu Check <span class="text-danger">*</span>
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
                                       value="<?php echo e(old('waktu_check', date('H:i'))); ?>" required>
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
                            <div class="col-md-4">
                                <label for="petugas_check" class="form-label fw-semibold">
                                    Petugas Check <span class="text-danger">*</span>
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
                                       value="<?php echo e(old('petugas_check', auth()->user()->name ?? '')); ?>" required>
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
                        </div>

                        <!-- Checklist Dinamis -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 text-secondary">
                                    <i class="fas fa-clipboard-list me-1"></i> Data Checklist
                                </h6>
                            </div>
                            
                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">
                                        <?php echo e($template->field_label); ?>

                                        <?php if($template->is_required): ?>
                                            <span class="text-danger">*</span>
                                        <?php endif; ?>
                                    </label>

                                    <?php switch($template->field_type):
                                        case ('text'): ?>
                                            <input type="text" class="form-control" 
                                                   name="values[<?php echo e($template->field_name); ?>]" 
                                                   value="<?php echo e(old('values.' . $template->field_name, $template->default_value)); ?>"
                                                   placeholder="<?php echo e($template->placeholder ?? ''); ?>">
                                            <?php break; ?>

                                        <?php case ('number'): ?>
                                            <input type="number" class="form-control" 
                                                   name="values[<?php echo e($template->field_name); ?>]" 
                                                   value="<?php echo e(old('values.' . $template->field_name, $template->default_value)); ?>"
                                                   placeholder="<?php echo e($template->placeholder ?? ''); ?>"
                                                   step="any">
                                            <?php break; ?>

                                        <?php case ('select'): ?>
                                            <select class="form-select" name="values[<?php echo e($template->field_name); ?>]">
                                                <option value="">-- Pilih --</option>
                                                <?php $__currentLoopData = $template->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($key); ?>" <?php echo e(old('values.' . $template->field_name, $template->default_value) == $key ? 'selected' : ''); ?>>
                                                        <?php echo e($label); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php break; ?>

                                        <?php case ('checkbox'): ?>
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" 
                                                       name="values[<?php echo e($template->field_name); ?>]" value="1"
                                                       id="<?php echo e($template->field_name); ?>"
                                                       <?php echo e(old('values.' . $template->field_name, $template->default_value) ? 'checked' : ''); ?>>
                                                <label class="form-check-label fw-normal" for="<?php echo e($template->field_name); ?>">
                                                    <?php echo e($template->field_label); ?>

                                                </label>
                                            </div>
                                            <?php break; ?>

                                        <?php case ('textarea'): ?>
                                            <textarea class="form-control" name="values[<?php echo e($template->field_name); ?>]" 
                                                      rows="2" placeholder="<?php echo e($template->placeholder ?? ''); ?>"><?php echo e(old('values.' . $template->field_name, $template->default_value)); ?></textarea>
                                            <?php break; ?>

                                        <?php default: ?>
                                            <input type="text" class="form-control" 
                                                   name="values[<?php echo e($template->field_name); ?>]" 
                                                   value="<?php echo e(old('values.' . $template->field_name, $template->default_value)); ?>">
                                    <?php endswitch; ?>

                                    <?php if($template->help_text): ?>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-info-circle fa-xs"></i> <?php echo e($template->help_text); ?>

                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Catatan -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 text-secondary">
                                    <i class="fas fa-pencil-alt me-1"></i> Informasi Tambahan
                                </h6>
                            </div>
                            
                            <div class="col-12">
                                <label for="catatan" class="form-label fw-semibold">
                                    Catatan
                                </label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                          placeholder="Tambahkan catatan jika diperlukan..."><?php echo e(old('catatan')); ?></textarea>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="<?php echo e(route('jobdesk.checklist.index', $jobdesk->slug)); ?>" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Simpan Checklist
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Daffa\job-managements\resources\views/checklist/create.blade.php ENDPATH**/ ?>