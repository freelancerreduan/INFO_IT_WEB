<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-5" style="max-width:460px;">
  <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
    <h3 class="font-display fw-bold mb-1">Welcome back</h3>
    <p class="text-muted small mb-4">Login to your Info-IT account.</p>
    <form method="POST" action="<?php echo e(route('login')); ?>">
      <?php echo csrf_field(); ?>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required autofocus>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label small" for="remember">Remember me</label>
      </div>
      <button class="btn btn-brand w-100 py-2">Login</button>
    </form>
    <p class="text-center small text-muted mt-4 mb-0">Don't have an account? <a href="<?php echo e(route('register')); ?>">Register</a></p>
    <div class="alert alert-light border small mt-4 mb-0">
      Demo admin: <b>admin@vertex.test</b> / password<br>
      Demo student: <b>student@vertex.test</b> / password
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/infoitcom/public_html/resources/views/auth/login.blade.php ENDPATH**/ ?>