<?php $__env->startSection('title', 'যোগাযোগ — ' . setting('institute_name')); ?>

<?php $__env->startPush('page-styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/contact-page.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- ================= PAGE BANNER ================= -->
<header class="hero" style="padding:3.2rem 0 3rem;">
  <div class="container container-xl">
    <div class="eyebrow" style="color:var(--mint);">যোগাযোগ</div>
    <h1 style="font-size:2.1rem;font-weight:700;max-width:640px;">আমাদের সাথে যোগাযোগ করুন</h1>
    <p style="color:rgba(255,255,255,.72);max-width:560px;margin-top:.8rem;font-size:.97rem;">কোর্স, ফি, ব্যাচের সময় বা ভর্তি নিয়ে যেকোনো প্রশ্ন থাকলে ফর্মে লিখে পাঠান, অথবা সরাসরি ক্যাম্পাসে চলে আসুন।</p>
  </div>
</header>

<!-- ================= CONTACT ================= -->
<section class="section-pad contact-page">
  <div class="container container-xl">
    <div class="row g-4 align-items-stretch">

      <!-- বাম পাশ: প্রতিষ্ঠানের তথ্য -->
      <div class="col-lg-5">
        <div class="ci-panel">
          <a href="<?php echo e(route('home')); ?>" class="ci-logo"><img src="<?php echo e(asset('img/logo.jpg')); ?>" alt="<?php echo e(setting('institute_name')); ?>"></a>
          <h2><?php echo e(setting('institute_name')); ?></h2>
          <p>দক্ষতা শিখুন, ক্যারিয়ার গড়ুন — <?php echo e(setting('city', 'ঢাকার বনানীতে')); ?> আমাদের ক্যাম্পাসে আপনাকে স্বাগতম।</p>

          <div class="ci-item">
            <span class="ci-ic"><i class="bi bi-geo-alt-fill"></i></span>
            <div><span class="lbl">ঠিকানা</span><span class="val"><?php echo e(setting('address', 'লেভেল ৩, বাড়ি ২৭, রোড ১১, বনানী, ঢাকা ১২১৩')); ?></span></div>
          </div>
          <div class="ci-item">
            <span class="ci-ic"><i class="bi bi-telephone-fill"></i></span>
            <div><span class="lbl">ফোন নম্বর</span><a class="val" href="tel:<?php echo e(setting('phone', '+8801723296130')); ?>"><?php echo e(bn_num(setting('phone_display', '+880 9612 345 678'))); ?></a></div>
          </div>
          <div class="ci-item">
            <span class="ci-ic"><i class="bi bi-envelope-fill"></i></span>
            <div><span class="lbl">ইমেইল</span><a class="val" href="mailto:<?php echo e(setting('support_email', 'info@infoittraining.com')); ?>"><?php echo e(setting('support_email', 'info@infoittraining.com')); ?></a></div>
          </div>
          <div class="ci-item">
            <span class="ci-ic"><i class="bi bi-clock-fill"></i></span>
            <div><span class="lbl">অফিস সময়</span><span class="val"><?php echo e(setting('office_hours', 'শনি – বৃহস্পতি, সকাল ৯টা – রাত ৯টা')); ?><br><small style="font-weight:500;color:rgba(255,255,255,.55);"><?php echo e(setting('office_note', 'শুক্রবার বন্ধ (সেমিনারের দিন ছাড়া)')); ?></small></span></div>
          </div>

          <div class="ci-social">
            <a href="<?php echo e(setting('facebook', '#')); ?>" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="https://wa.me/<?php echo e(setting('whatsapp', '8801712345678')); ?>" aria-label="WhatsApp" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i></a>
            <a href="<?php echo e(setting('youtube', '#')); ?>" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            <a href="<?php echo e(setting('linkedin', '#')); ?>" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>

      <!-- ডান পাশ: যোগাযোগ ফর্ম (হোম পেজের মতো একই ফিল্ড) -->
      <div class="col-lg-7">
        <div class="cf-card">
          <div class="cf-card-head">
            <h2>বার্তা পাঠান</h2>
            <p>নিচের ফর্মটি পূরণ করুন — আমাদের টিম এক কর্মদিবসের মধ্যে আপনার সাথে যোগাযোগ করবে।</p>
          </div>
          <?php echo $__env->make('frontend.partials.contact-form', ['prefix' => 'cp', 'source' => 'contact', 'rows' => 6], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
      </div>
    </div>

    <!-- ম্যাপ -->
    <div class="map-wrap mt-4">
      <iframe src="https://www.google.com/maps?q=<?php echo e(urlencode(setting('map_query', 'Banani, Dhaka 1213'))); ?>&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php echo e(setting('institute_name')); ?> ক্যাম্পাসের ম্যাপ"></iframe>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/infoitcom/public_html/resources/views/frontend/contact.blade.php ENDPATH**/ ?>