<?php $__env->startSection('title', 'Register'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-5" style="max-width:460px;">
  <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
    <h3 class="font-display fw-bold mb-1">Create your account</h3>
    <p class="text-muted small mb-4">Join Info-IT Training Institute as a student.</p>
    <form method="POST" action="<?php echo e(route('register')); ?>">
      <?php echo csrf_field(); ?>
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
      </div>
      <button class="btn btn-brand w-100 py-2">Register</button>
    </form>
    <p class="text-center small text-muted mt-4 mb-0">Already have an account? <a href="<?php echo e(route('login')); ?>">Login</a></p>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/infoitcom/public_html/resources/views/auth/register.blade.php ENDPATH**/ ?>