<!DOCTYPE html>
<html lang="bn">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', setting('institute_name', 'INFO IT TRAINING CENTER')); ?></title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
      href="https://fonts.googleapis.com/css2?family=Anek+Bangla:wght@500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet">

    <link rel="stylesheet" href="<?php echo e(asset('css/theme.css')); ?>">
    <?php echo $__env->yieldPushContent('page-styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/site.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
  </head>

  <body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-vertex sticky-top">
      <div class="container container-xl">
        <a href="<?php echo e(route('home')); ?>" class="d-flex align-items-center text-decoration-none">
          <img src="<?php echo e(asset('img/logo.jpg')); ?>" alt="<?php echo e(setting('institute_name')); ?>" class="img-fluid" style="max-height: 60px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-label="মেনু খুলুন">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navMenu">
          <div class="nav-links d-flex flex-column flex-lg-row mt-3 mt-lg-0">
            <a href="<?php echo e(route('home')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('home')]); ?>" <?php if(request()->routeIs('home')): ?> aria-current="page" <?php endif; ?>>Home</a>
            <a href="<?php echo e(route('courses.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('courses.*')]); ?>" <?php if(request()->routeIs('courses.*')): ?> aria-current="page" <?php endif; ?>> Courses </a>
            <a href="<?php echo e(route('home')); ?>#seminar">Seminer</a>
            <a href="<?php echo e(route('home')); ?>#videos">Class Video</a>
            <a href="<?php echo e(route('gallery')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('gallery')]); ?>" <?php if(request()->routeIs('gallery')): ?> aria-current="page" <?php endif; ?>>Gallary</a>
            <a href="<?php echo e(route('contact')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('contact')]); ?>" <?php if(request()->routeIs('contact')): ?> aria-current="page" <?php endif; ?>>Contact</a>
          </div>
          <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0 ms-lg-4">
            <!-- <a href="tel:<?php echo e(setting('phone', '+8801723296130')); ?>" class="nav-phone d-none d-xl-flex"><i class="bi bi-telephone-fill"></i> <?php echo e(bn_num(setting('phone_display', '+880 9612 345 678'))); ?></a> -->
            <?php if(auth()->guard()->check()): ?>
              
              <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard')); ?>" class="btn btn-navy btn-sm"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
              <form action="<?php echo e(route('logout')); ?>" method="POST" class="m-0">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
              </form>
            <?php else: ?>
              <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-person me-1"></i>Login</a>
              <a href="<?php echo e(route('apply')); ?>" class="btn btn-navy btn-sm">Admission</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>

    <?php echo $__env->yieldContent('content'); ?>

    <!-- ================= FOOTER ================= -->
    <footer class="site-footer" id="footer">
      <div class="container container-xl">
        <div class="row g-4">
          <div class="col-lg-4">
            <a href="<?php echo e(route('home')); ?>" class="footer-logo"><img src="<?php echo e(asset('img/logo.jpg')); ?>" alt="<?php echo e(setting('institute_name')); ?>"></a>
            <p>ওয়েব ডেভেলপমেন্ট, ডিজাইন, ডিজিটাল মার্কেটিং ও আইটি বিষয়ে ক্যারিয়ারমুখী প্রশিক্ষণ — ইন্ডাস্ট্রিতে কাজ করা অভিজ্ঞ প্রশিক্ষকদের হাতে, বাস্তব প্রজেক্টের মাধ্যমে।</p>
          </div>
          <div class="col-6 col-lg-3">
            <h5>কোর্সসমূহ</h5>
            <?php $__currentLoopData = $footerCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a href="<?php echo e(route('courses.show', $fc->slug)); ?>"><?php echo e($fc->title); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <div class="col-6 col-lg-2">
            <h5>প্রতিষ্ঠান</h5>
            <a href="<?php echo e(route('home')); ?>#seminar">ফ্রি সেমিনার</a>
            <a href="<?php echo e(route('home')); ?>#videos">ক্লাস ভিডিও</a>
            <a href="<?php echo e(route('gallery')); ?>">গ্যালারি</a>
            <a href="<?php echo e(route('apply')); ?>">ভর্তি হোন</a>
            <a href="<?php echo e(route('contact')); ?>">যোগাযোগ</a>
            <a href="<?php echo e(auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard')) : route('login')); ?>"><?php echo e(auth()->check() ? 'ড্যাশবোর্ড' : 'লগইন'); ?></a>
          </div>
          <div class="col-lg-3">
            <h5>আমাদের ঠিকানা</h5>
            <p class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i><?php echo e(setting('address', 'লেভেল ৩, বাড়ি ২৭, রোড ১১, বনানী, ঢাকা ১২১৩')); ?></p>
            <p class="mb-2"><i class="bi bi-telephone-fill me-2"></i><?php echo e(bn_num(setting('phone_display', '+880 9612 345 678'))); ?></p>
            <p class="mb-2"><i class="bi bi-envelope-fill me-2"></i><?php echo e(setting('support_email', 'info@infoittraining.com')); ?></p>
            <p class="mb-0"><i class="bi bi-clock-fill me-2"></i><?php echo e(setting('office_hours', 'শনি–বৃহস্পতি, সকাল ৯টা – রাত ৯টা')); ?></p>
          </div>
        </div>
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2">
          <span>&copy; <?php echo e(bn_num(date('Y'))); ?> <?php echo e(setting('institute_name')); ?>। সর্বস্বত্ব সংরক্ষিত।</span>
          <div class="d-flex gap-3">
            <a href="<?php echo e(setting('facebook', '#')); ?>" style="margin:0;" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="<?php echo e(setting('instagram', '#')); ?>" style="margin:0;" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="<?php echo e(setting('linkedin', '#')); ?>" style="margin:0;" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="<?php echo e(setting('youtube', '#')); ?>" style="margin:0;" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('js/site.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
  </body>

</html>
<?php /**PATH /home/infoitcom/public_html/resources/views/layouts/app.blade.php ENDPATH**/ ?>