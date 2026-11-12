<?php $__env->startSection('title', 'ভর্তির আবেদন — ' . setting('institute_name')); ?>

<?php $__env->startSection('content'); ?>
<?php $me = auth()->check() && ! auth()->user()->isAdmin() ? auth()->user() : null; ?>
<!-- ================= PAGE BANNER ================= -->
<header class="hero" style="padding:3rem 0 2.8rem;">
  <div class="container container-xl">
    <div class="eyebrow" style="color:var(--mint);">ভর্তি</div>
    <h1 style="font-size:2rem;font-weight:700;">ভর্তির আবেদন করুন</h1>
    <p style="color:rgba(255,255,255,.72);max-width:560px;margin-top:.6rem;font-size:.95rem;">নিচের ফর্মটি পূরণ করুন। আসন নিশ্চিত করতে আমাদের অ্যাডমিশন টিম এক কর্মদিবসের মধ্যে আপনাকে কল করবে।</p>
  </div>
</header>

<section class="section-pad">
  <div class="container container-xl">
    <div class="row g-5">

      <!-- Form -->
      <div class="col-lg-7">
        <form id="applyForm" method="POST" action="<?php echo e(route('apply.store')); ?>" enctype="multipart/form-data" novalidate>
          <?php echo csrf_field(); ?>
          <div id="formAlert">
            <?php if(session('apply_success')): ?>
              <div class="alert alert-success d-flex align-items-start gap-2" style="border-radius:10px;">
                <i class="bi bi-check-circle-fill mt-1"></i>
                <div><?php echo session('apply_success'); ?></div>
              </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
              <div class="alert alert-danger d-flex align-items-start gap-2" style="border-radius:10px;">
                <i class="bi bi-exclamation-circle-fill mt-1"></i>
                <div>আবেদনটি জমা হয়নি। নিচের ঘরগুলো আবার দেখুন।</div>
              </div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">কোর্স নির্বাচন করুন</label>
            <select class="form-select <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="courseSelect" name="course_id" required>
              <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c->id); ?>" <?php if(old('course_id', $selectedCourse?->id) == $c->id): echo 'selected'; endif; ?>><?php echo e($c->title); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">পূর্ণ নাম</label>
              <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name" value="<?php echo e(old('name', $me?->name)); ?>" placeholder="আপনার পূর্ণ নাম" required>
              <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">মোবাইল নম্বর</label>
              <input type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="phone" value="<?php echo e(old('phone', $me?->phone)); ?>" placeholder="০১XXXXXXXXX" required>
              <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">অভিভাবকের মোবাইল নম্বর</label>
              <input type="tel" class="form-control <?php $__errorArgs = ['parent_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="parent_phone" value="<?php echo e(old('parent_phone', $me?->parent_phone)); ?>" placeholder="০১XXXXXXXXX" required>
              <?php $__errorArgs = ['parent_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">জাতীয় পরিচয়পত্র (NID) নম্বর <span class="fw-normal text-muted">(ঐচ্ছিক)</span></label>
              <input type="text" class="form-control <?php $__errorArgs = ['nid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="nid" value="<?php echo e(old('nid', $me?->nid)); ?>" placeholder="NID / জন্ম নিবন্ধন নম্বর">
              <?php $__errorArgs = ['nid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">বর্তমান ঠিকানা</label>
              <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="address" rows="2" placeholder="বাড়ি, রোড, এলাকা, জেলা"><?php echo e(old('address', $me?->address)); ?></textarea>
              <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">ইমেইল (ঐচ্ছিক)</label>
              <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email', $me?->email)); ?>" placeholder="example@gmail.com">
              <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">ছবি (পাসপোর্ট সাইজ)</label>
              <input type="file" class="form-control <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="photo" accept="image/*">
              <div class="form-text">JPG অথবা PNG, ২MB-এর কম।</div>
              <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>

          <div class="form-check mt-3">
            <input class="form-check-input <?php $__errorArgs = ['agree'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="checkbox" name="agree" value="1" id="agreeCheck" required>
            <label class="form-check-label" for="agreeCheck" style="font-size:.85rem;color:var(--ink-soft);">
              আমি নিশ্চিত করছি যে উপরের সকল তথ্য সঠিক এবং <?php echo e(setting('institute_name')); ?> আমার সাথে যোগাযোগ করতে পারবে।
            </label>
          </div>

          <button type="submit" class="btn-navy mt-4 px-4">আবেদন জমা দিন</button>
        </form>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-5">
        <div id="selectedCourseCard">
          <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="sidebar-card course-info-card <?php echo e(($selectedCourse?->id ?? $courses->first()?->id) == $c->id ? '' : 'd-none'); ?>" data-course="<?php echo e($c->id); ?>">
              <div class="badge-cat" style="position:static;display:inline-block;background:var(--navy-800);margin-bottom:.8rem;"><?php echo e($c->category); ?></div>
              <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:.5rem;"><?php echo e($c->title); ?></h2>
              <p style="font-size:.85rem;color:var(--ink-soft);margin-bottom:1rem;"><?php echo e($c->short_desc); ?></p>
              <div class="sidebar-row"><span>কোর্স ফি</span><b><?php echo e(bn_money($c->price)); ?></b></div>
              <div class="sidebar-row"><span>মেয়াদ</span><b><?php echo e($c->duration); ?></b></div>
              <div class="sidebar-row"><span>ক্লাসের ধরন</span><b><?php echo e($c->mode); ?></b></div>
              <a href="<?php echo e(route('courses.show', $c->slug)); ?>" style="font-size:.8rem;font-weight:600;color:var(--navy-800);display:inline-block;margin-top:.8rem;">পূর্ণ সিলেবাস দেখুন <i class="bi bi-arrow-right"></i></a>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="sidebar-card mt-4">
          <h2 style="font-size:1.05rem;font-weight:700;margin-bottom:1rem;">সরাসরি আসুন অথবা কল করুন</h2>
          <div class="sidebar-row"><span><i class="bi bi-geo-alt-fill"></i> ক্যাম্পাস</span><b style="text-align:right;max-width:170px;"><?php echo e(setting('address', 'লেভেল ৩, বাড়ি ২৭, রোড ১১, বনানী, ঢাকা ১২১৩')); ?></b></div>
          <div class="sidebar-row"><span><i class="bi bi-telephone-fill"></i> ফোন</span><b><?php echo e(bn_num(setting('phone_display', '+880 9612 345 678'))); ?></b></div>
          <div class="sidebar-row"><span><i class="bi bi-envelope-fill"></i> ইমেইল</span><b><?php echo e(setting('support_email', 'info@infoittraining.com')); ?></b></div>
          <div class="sidebar-row"><span><i class="bi bi-clock-fill"></i> অফিস সময়</span><b><?php echo e(setting('office_hours', 'শনি–বৃহস্পতি, সকাল ৯টা–রাত ৯টা')); ?></b></div>
        </div>

        <div class="mt-4" style="font-size:.82rem;color:var(--ink-soft);">
          <div class="d-flex gap-2 mb-2"><i class="bi bi-shield-check" style="color:var(--mint-dark);"></i> আপনার তথ্য শুধুমাত্র ভর্তি প্রক্রিয়ার জন্য ব্যবহার করা হবে।</div>
          <div class="d-flex gap-2 mb-2"><i class="bi bi-clock-history" style="color:var(--mint-dark);"></i> অ্যাডমিশন টিম এক কর্মদিবসের মধ্যে যোগাযোগ করে।</div>
          <div class="d-flex gap-2"><i class="bi bi-credit-card" style="color:var(--mint-dark);"></i> ভর্তির কলের পর কিস্তিতে পেমেন্টের সুবিধা পাওয়া যাবে।</div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* কোর্স বদলালে পাশের কার্ডও বদলায় */
const select = document.getElementById('courseSelect');
function showSelectedCourse(){
  document.querySelectorAll('.course-info-card').forEach(card => {
    card.classList.toggle('d-none', card.dataset.course !== select.value);
  });
}
select.addEventListener('change', showSelectedCourse);
showSelectedCourse();

const flash = document.querySelector('#formAlert .alert');
if (flash) flash.scrollIntoView({ behavior: 'smooth', block: 'center' });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/infoitcom/public_html/resources/views/frontend/apply.blade.php ENDPATH**/ ?>