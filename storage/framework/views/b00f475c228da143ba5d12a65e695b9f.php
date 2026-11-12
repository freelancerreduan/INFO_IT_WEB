<?php if(session('success')): ?>
  <div class="flash flash-ok"><i class="bi bi-check-circle-fill"></i><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
  <div class="flash flash-bad"><i class="bi bi-exclamation-circle-fill"></i><?php echo e(session('error')); ?></div>
<?php endif; ?>
<?php if($errors->any()): ?>
  <div class="flash flash-bad"><i class="bi bi-exclamation-circle-fill"></i>Please fix the highlighted fields and try again.</div>
<?php endif; ?>
<?php /**PATH /home/infoitcom/public_html/resources/views/admin/partials/flash.blade.php ENDPATH**/ ?>