<?php $__env->startSection('title', $course->title . ' — ' . setting('institute_name')); ?>

<?php $__env->startSection('content'); ?>
<!-- ================= COURSE DETAILS ================= -->
<section class="section-pad" style="padding-top:2.5rem;">
  <div class="container container-xl">
    <nav style="font-size:.82rem;color:var(--ink-soft);margin-bottom:1.4rem;">
      <a href="<?php echo e(route('home')); ?>" style="color:var(--ink-soft);">হোম</a> /
      <a href="<?php echo e(route('courses.index')); ?>" style="color:var(--ink-soft);">কোর্সসমূহ</a> /
      <span style="color:var(--ink);font-weight:600;"><?php echo e($course->title); ?></span>
    </nav>

    <div class="row g-5">
      <!-- Main content -->
      <div class="col-lg-8">
        <span class="badge-cat" style="position:static;display:inline-block;background:var(--navy-800);margin-bottom:1rem;"><?php echo e($course->category); ?></span>
        <h1 style="font-size:2rem;font-weight:700;margin-bottom:.8rem;"><?php echo e($course->title); ?></h1>
        <div class="d-flex flex-wrap align-items-center gap-3 mb-4" style="font-size:.88rem;color:var(--ink-soft);">
          <span class="rating" style="color:var(--gold);font-weight:700;"><i class="bi bi-star-fill"></i> <?php echo e(bn_num($course->rating)); ?></span>
          <span><i class="bi bi-people"></i> <?php echo e(bn_num(number_format($course->enrollments_count))); ?> জন শিক্ষার্থী</span>
          <?php if($course->instructor): ?>
            <span><i class="bi bi-person-badge"></i> <?php echo e($course->instructor); ?><?php if($course->instructor_role): ?>, <?php echo e($course->instructor_role); ?><?php endif; ?></span>
          <?php endif; ?>
        </div>
        <div class="hero-media mb-4" style="box-shadow:0 20px 40px -20px rgba(10,26,61,.3);">
          <img src="<?php echo e($course->thumb_url); ?>" alt="<?php echo e($course->title); ?>" style="height:320px;">
        </div>

        <?php if($course->long_desc): ?>
          <div class="detail-block">
            <h2>কোর্স সম্পর্কে</h2>
            <p style="font-size:.95rem;color:var(--ink-soft);line-height:1.7;"><?php echo e($course->long_desc); ?></p>
          </div>
        <?php endif; ?>

        <?php if(! empty($course->outcomes)): ?>
          <div class="detail-block">
            <h2>যা যা শিখবেন</h2>
            <ul class="outcome-list list-unstyled">
              <?php $__currentLoopData = $course->outcomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><i class="bi bi-check-circle-fill"></i> <?php echo e($o); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if(! empty($course->curriculum)): ?>
          <div class="detail-block">
            <h2>কোর্স কারিকুলাম</h2>
            <ul class="curriculum-list list-unstyled">
              <?php $__currentLoopData = $course->curriculum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><i class="bi bi-play-circle"></i> মডিউল <?php echo e(bn_num($i + 1)); ?>: <?php echo e($m); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if($course->instructor): ?>
          <div class="detail-block">
            <h2>আপনার প্রশিক্ষক</h2>
            <div class="d-flex align-items-center gap-3">
              <div class="avatar-init" style="margin-bottom:0;"><?php echo e(mb_substr($course->instructor, 0, 1)); ?></div>
              <div>
                <div style="font-weight:700;"><?php echo e($course->instructor); ?></div>
                <div style="font-size:.85rem;color:var(--mint-dark);font-weight:600;"><?php echo e($course->instructor_role); ?></div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <?php if($courseSeminars->isNotEmpty()): ?>
          <div class="detail-block">
            <h2>এই কোর্সের ফ্রি সেমিনার</h2>
            <?php $__currentLoopData = $courseSeminars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 py-2" style="border-bottom:1px solid var(--line);">
                <div>
                  <div style="font-weight:600;"><?php echo e($s->date_text); ?> · <?php echo e($s->time_label); ?></div>
                  <div style="font-size:.84rem;color:var(--ink-soft);"><?php echo e($s->venue_label); ?> সেমিনার</div>
                </div>
                <a href="<?php echo e($s->register_url); ?>" class="btn-ghost-navy btn-sm">রেজিস্ট্রেশন করুন</a>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sticky sidebar -->
      <div class="col-lg-4">
        <div style="position:sticky;top:6.5rem;">
          <div class="sidebar-card">
            <div class="d-flex align-items-baseline gap-2 mb-1">
              <span style="font-size:1.7rem;font-weight:700;font-family:'Anek Bangla';"><?php echo e(bn_money($course->price)); ?></span>
              <?php if($course->old_price): ?>
                <span style="font-size:.9rem;color:#b3ae9f;text-decoration:line-through;"><?php echo e(bn_money($course->old_price)); ?></span>
              <?php endif; ?>
            </div>
            <div style="font-size:.8rem;color:var(--ink-soft);margin-bottom:1.2rem;">কিস্তিতে পেমেন্টের সুবিধা আছে</div>
            <a href="<?php echo e(route('apply', ['course' => $course->slug])); ?>" class="btn-mint w-100 text-center d-block mb-3">এই কোর্সে ভর্তি হোন</a>
            <?php if($course->duration): ?><div class="sidebar-row"><span>মেয়াদ</span><b><?php echo e($course->duration); ?></b></div><?php endif; ?>
            <?php if($course->classes): ?><div class="sidebar-row"><span>ক্লাস সংখ্যা</span><b><?php echo e($course->classes); ?></b></div><?php endif; ?>
            <?php if($course->mode): ?><div class="sidebar-row"><span>ক্লাসের ধরন</span><b><?php echo e($course->mode); ?></b></div><?php endif; ?>
            <?php if($course->certificate): ?><div class="sidebar-row"><span>সার্টিফিকেট</span><b style="text-align:right;max-width:160px;"><?php echo e($course->certificate); ?></b></div><?php endif; ?>
          </div>
          <div class="mt-3" style="font-size:.8rem;color:var(--ink-soft);text-align:center;">
            <i class="bi bi-telephone-fill"></i> কোনো প্রশ্ন? কল করুন <?php echo e(bn_num(setting('phone_display', '+880 9612 345 678'))); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= OTHER PROGRAMS ================= -->
<?php if($related->isNotEmpty()): ?>
  <section class="section-pad" style="background:var(--cream);padding-top:0;">
    <div class="container container-xl">
      <div class="eyebrow">আরও দেখুন</div>
      <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1.6rem;">আপনার পছন্দ হতে পারে এমন আরও কোর্স</h2>
      <div class="row g-4">
        <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php echo $__env->make('frontend.partials.course-card', ['c' => $c, 'hideFacts' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  </section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/infoitcom/public_html/resources/views/frontend/course-details.blade.php ENDPATH**/ ?>