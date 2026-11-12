
<div id="<?php echo e($zone); ?>">
  <label class="upload-box d-block <?php echo e(!empty($existing) ? 'd-none' : ''); ?>" for="<?php echo e($zone); ?>Input" data-upload-label>
    <i class="bi <?php echo e($icon); ?> fs-3" style="color:var(--accent)"></i>
    <div class="fw-semibold mt-2 small"><?php echo $text; ?></div>
    <div class="text-muted small"><?php echo e($hint); ?></div>
  </label>
  <div class="upload-preview <?php echo e(empty($existing) ? 'd-none' : ''); ?>" data-upload-preview>
    <img src="<?php echo e($existing ?? ''); ?>" alt="preview">
    <button type="button" class="upload-remove" onclick="resetUpload('<?php echo e($zone); ?>')"><i class="bi bi-x-lg"></i></button>
  </div>
  <input type="file" name="<?php echo e($name); ?>" accept="image/*" id="<?php echo e($zone); ?>Input" class="d-none" onchange="handlePhoto(event,'<?php echo e($zone); ?>')">
</div>
<?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
<?php /**PATH /home/infoitcom/public_html/resources/views/admin/partials/upload.blade.php ENDPATH**/ ?>