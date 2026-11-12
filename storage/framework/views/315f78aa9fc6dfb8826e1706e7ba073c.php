<?php $admin = auth()->user(); ?>
<header class="topbar">
  <button class="icon-btn d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar"><i class="bi bi-list"></i></button>
  <div>
    <h1 class="page-title" id="topTitle"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
    <div class="page-title-sub" id="topSub"><?php echo $__env->yieldContent('subtitle'); ?></div>
  </div>
  <div class="ms-auto d-flex align-items-center gap-2">
    <div class="position-relative dropdown">
      <button class="icon-btn" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-bell"></i><?php if(($adminNotifications['count'] ?? 0) > 0): ?><span class="badge-dot"></span><?php endif; ?></button>
      <div class="dropdown-menu dropdown-menu-end shadow-sm mt-2 notify-menu">
        <div class="n-head d-flex justify-content-between align-items-center">
          Notifications
          <span class="status-pill status-pending"><?php echo e($adminNotifications['count'] ?? 0); ?> pending</span>
        </div>
        <?php $__empty_1 = true; $__currentLoopData = ($adminNotifications['items'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <a class="n-item" href="<?php echo e($n['url']); ?>">
            <div class="d-flex gap-2">
              <i class="bi <?php echo e($n['icon']); ?>" style="color:var(--accent)"></i>
              <div>
                <div class="fw-semibold"><?php echo e($n['title']); ?></div>
                <div class="cell-sub"><?php echo e($n['sub']); ?> · <?php echo e(optional($n['date'])->format('Y-m-d')); ?></div>
              </div>
            </div>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <div class="empty-state py-4"><i class="bi bi-bell-slash"></i><p class="mt-2 mb-0 small">You're all caught up.</p></div>
        <?php endif; ?>
      </div>
    </div>
    <div class="dropdown">
      <button class="profile-btn dropdown-toggle" data-bs-toggle="dropdown">
        <div class="avatar"><?php echo e(initials($admin?->name)); ?></div>
        <div class="d-none d-sm-block text-start">
          <div class="profile-name"><?php echo e($admin?->name); ?></div>
          <div class="profile-role">Administrator</div>
        </div>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2">
        <li><a class="dropdown-item" href="<?php echo e(route('admin.profile.edit')); ?>"><i class="bi bi-person me-2"></i>Profile</a></li>
        <li><a class="dropdown-item" href="<?php echo e(route('admin.settings.index')); ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="#" onclick="adminLogout();return false;"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</header>
<?php /**PATH /home/infoitcom/public_html/resources/views/admin/layouts/partials/topbar.blade.php ENDPATH**/ ?>