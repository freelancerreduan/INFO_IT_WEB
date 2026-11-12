<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo e(setting('institute_name')); ?> · <?php echo $__env->yieldContent('title', 'Admin'); ?></title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
<?php echo $__env->make('admin.layouts.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<div class="app-shell">

  <!-- ============ DESKTOP SIDEBAR ============ -->
  <aside class="sidebar">
    <?php echo $__env->make('admin.layouts.partials.brand', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <nav class="side-scroll" id="sideNav">
      <?php echo $__env->make('admin.layouts.partials.sidebar-nav', ['navKey' => 'd'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </nav>
    <div class="side-foot">
      <div class="side-link" onclick="adminLogout()"><i class="bi bi-box-arrow-right side-ic"></i>Logout</div>
    </div>
  </aside>

  <div class="main-col">

    <!-- ============ TOPBAR ============ -->
    <?php echo $__env->make('admin.layouts.partials.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- ============ CONTENT ============ -->
    <main class="content" id="content">
      <?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <?php echo $__env->yieldContent('content'); ?>
    </main>
    <footer class="text-center text-muted small py-4">© <?php echo e(date('Y')); ?> <?php echo e(setting('institute_name')); ?> — Admin Panel</footer>
  </div>
</div>

<!-- ============ MOBILE OFFCANVAS SIDEBAR ============ -->
<div class="offcanvas offcanvas-start offcanvas-dark" id="mobileSidebar">
  <div class="offcanvas-header" style="border-bottom:1px solid var(--side-line)">
    <?php echo $__env->make('admin.layouts.partials.brand', ['extraClass' => 'py-0'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <nav class="side-scroll" id="sideNavMobile">
      <?php echo $__env->make('admin.layouts.partials.sidebar-nav', ['navKey' => 'm'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </nav>
    <div class="side-foot">
      <div class="side-link" onclick="adminLogout()"><i class="bi bi-box-arrow-right side-ic"></i>Logout</div>
    </div>
  </div>
</div>

<form id="logoutForm" action="<?php echo e(route('admin.logout')); ?>" method="POST" class="d-none"><?php echo csrf_field(); ?></form>

<!-- ============ MODALS ============ -->
<?php echo $__env->make('admin.layouts.partials.modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('admin.layouts.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/infoitcom/public_html/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>