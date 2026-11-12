<?php
  /*
   | NAVIGATION CONFIG (same as original NAV array)
   | 'active' = route name patterns that mark the link as active
   */
  // Finance menu er badge: pending order + approve er opekkhay thaka payment
  $pendingOrders   = \App\Models\Enrollment::where('order_status', 'pending')->count();
  $pendingPayments = \App\Models\Payment::where('status', 'pending')->count();

  $NAV = [
    ['type' => 'link', 'id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-1x2', 'route' => 'admin.dashboard', 'active' => ['admin.dashboard']],
    ['type' => 'group', 'id' => 'student', 'label' => 'Student', 'icon' => 'bi-people', 'items' => [
      ['label' => 'Add Student',  'route' => 'admin.students.create', 'active' => ['admin.students.create']],
      ['label' => 'Student List', 'route' => 'admin.students.index',  'active' => ['admin.students.index', 'admin.students.edit']],
      ['label' => 'Applications', 'route' => 'admin.applications.index', 'active' => ['admin.applications.*']],
    ]],
    ['type' => 'group', 'id' => 'finance', 'label' => 'Finance', 'icon' => 'bi-cash-coin', 'badge' => $pendingOrders + $pendingPayments, 'items' => [
      ['label' => 'Transactions', 'route' => 'admin.transactions.index', 'active' => ['admin.transactions.*'], 'badge' => $pendingPayments],
      ['label' => 'Add Order',    'route' => 'admin.orders.create',      'active' => ['admin.orders.create']],
      ['label' => 'Orders',       'route' => 'admin.orders.index',       'active' => ['admin.orders.index', 'admin.orders.show'], 'badge' => $pendingOrders],
      ['label' => 'Add Payment',  'route' => 'admin.payments.create',    'active' => ['admin.payments.*']],
      ['label' => 'Costing',      'route' => 'admin.costing.index',      'active' => ['admin.costing.*']],
    ]],
    ['type' => 'group', 'id' => 'course', 'label' => 'Course', 'icon' => 'bi-journal-bookmark', 'items' => [
      ['label' => 'Add Course',  'route' => 'admin.courses.create', 'active' => ['admin.courses.create']],
      ['label' => 'Course List', 'route' => 'admin.courses.index',  'active' => ['admin.courses.index', 'admin.courses.edit']],
    ]],
    ['type' => 'group', 'id' => 'seminar', 'label' => 'Seminar', 'icon' => 'bi-megaphone', 'items' => [
      ['label' => 'Add Seminar',  'route' => 'admin.seminars.create', 'active' => ['admin.seminars.create']],
      ['label' => 'Seminar List', 'route' => 'admin.seminars.index',  'active' => ['admin.seminars.index', 'admin.seminars.edit']],
    ]],
    ['type' => 'group', 'id' => 'video', 'label' => 'Class Video', 'icon' => 'bi-play-btn', 'items' => [
      ['label' => 'Add Video',  'route' => 'admin.videos.create', 'active' => ['admin.videos.create']],
      ['label' => 'Video List', 'route' => 'admin.videos.index',  'active' => ['admin.videos.index', 'admin.videos.edit']],
    ]],
    ['type' => 'group', 'id' => 'gallery', 'label' => 'Gallery', 'icon' => 'bi-images', 'items' => [
      ['label' => 'Add Gallery',  'route' => 'admin.gallery.create', 'active' => ['admin.gallery.create']],
      ['label' => 'Gallery List', 'route' => 'admin.gallery.index',  'active' => ['admin.gallery.index', 'admin.gallery.edit']],
    ]],
    ['type' => 'link', 'id' => 'messages', 'label' => 'Messages', 'icon' => 'bi-chat-left-text', 'route' => 'admin.messages.index', 'active' => ['admin.messages.*'], 'badge' => \App\Models\ContactMessage::where('status', 'new')->count()],
    ['type' => 'link', 'id' => 'reports',  'label' => 'Reports',  'icon' => 'bi-bar-chart-line', 'route' => 'admin.reports.index',  'active' => ['admin.reports.*']],
    ['type' => 'link', 'id' => 'settings', 'label' => 'Settings', 'icon' => 'bi-gear',           'route' => 'admin.settings.index', 'active' => ['admin.settings.*']],
  ];
?>

<?php $__currentLoopData = $NAV; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php if($n['type'] === 'link'): ?>
    <a class="side-link <?php echo e(request()->routeIs(...$n['active']) ? 'active' : ''); ?>" href="<?php echo e(route($n['route'])); ?>">
      <i class="bi <?php echo e($n['icon']); ?> side-ic"></i><?php echo e($n['label']); ?>

      <?php if(! empty($n['badge'])): ?> <span class="side-badge"><?php echo e($n['badge']); ?></span> <?php endif; ?>
    </a>
  <?php else: ?>
    <?php $open = collect($n['items'])->contains(fn ($it) => request()->routeIs(...$it['active'])); ?>
    <div class="side-link" data-toggle-group="<?php echo e($n['id']); ?>" aria-expanded="<?php echo e($open ? 'true' : 'false'); ?>" onclick="toggleGroup('<?php echo e($n['id']); ?>',this)">
      <i class="bi <?php echo e($n['icon']); ?> side-ic"></i><?php echo e($n['label']); ?>

      <?php if(! empty($n['badge'])): ?> <span class="side-badge"><?php echo e($n['badge']); ?></span> <?php endif; ?>
      <i class="bi bi-chevron-right chev <?php echo e(! empty($n['badge']) ? 'ms-2' : ''); ?>"></i>
    </div>
    <div class="collapse side-sub <?php echo e($open ? 'show' : ''); ?>" id="grp-<?php echo e($n['id']); ?>-<?php echo e($navKey); ?>">
      <?php $__currentLoopData = $n['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a class="side-link <?php echo e(request()->routeIs(...$it['active']) ? 'active' : ''); ?>" href="<?php echo e(route($it['route'])); ?>"><?php echo e($it['label']); ?>

          <?php if(! empty($it['badge'])): ?> <span class="side-badge" title="Pending"><?php echo e($it['badge']); ?></span> <?php endif; ?>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /home/infoitcom/public_html/resources/views/admin/layouts/partials/sidebar-nav.blade.php ENDPATH**/ ?>