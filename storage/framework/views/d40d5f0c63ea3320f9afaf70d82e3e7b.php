
<?php $prefix = $prefix ?? 'home'; $source = $source ?? 'home'; ?>

<form class="js-contact-form" method="POST" action="<?php echo e(route('contact.store')); ?>" novalidate>
  <?php echo csrf_field(); ?>
  <input type="hidden" name="source" value="<?php echo e($source); ?>">
  <input type="hidden" name="website" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">

  <div class="cf-alert" aria-live="polite">
    <?php if(session('contact_success') && session('contact_source') === $source): ?>
      <div class="alert alert-success d-flex align-items-start gap-2 mb-4" role="status" style="border-radius:12px;">
        <i class="bi bi-check-circle-fill mt-1"></i>
        <div><?php echo session('contact_success'); ?></div>
      </div>
    <?php endif; ?>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <label class="cf-label" for="<?php echo e($prefix); ?>Name">আপনার নাম<span class="req">*</span></label>
      <div class="cf-input-wrap">
        <i class="bi bi-person"></i>
        <input class="cf-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> cf-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($prefix); ?>Name" name="name" type="text"
               value="<?php echo e(old('name')); ?>" placeholder="যেমন: রাকিব হাসান" autocomplete="name" required>
        <div class="cf-error"><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php else: ?> অনুগ্রহ করে আপনার নাম লিখুন।<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
      </div>
    </div>
    <div class="col-md-6">
      <label class="cf-label" for="<?php echo e($prefix); ?>Phone">মোবাইল নম্বর<span class="req">*</span></label>
      <div class="cf-input-wrap">
        <i class="bi bi-phone"></i>
        <input class="cf-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> cf-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($prefix); ?>Phone" name="phone" type="tel"
               inputmode="tel" value="<?php echo e(old('phone')); ?>" placeholder="০১XXXXXXXXX" autocomplete="tel" required>
        <div class="cf-error"><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php else: ?> সঠিক মোবাইল নম্বর দিন (যেমন: ০১৭১২৩৪৫৬৭৮)।<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
      </div>
    </div>
    <div class="col-12">
      <label class="cf-label" for="<?php echo e($prefix); ?>Email">ইমেইল<span class="req">*</span></label>
      <div class="cf-input-wrap">
        <i class="bi bi-envelope"></i>
        <input class="cf-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> cf-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($prefix); ?>Email" name="email" type="email"
               value="<?php echo e(old('email')); ?>" placeholder="example@gmail.com" autocomplete="email" required>
        <div class="cf-error"><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php else: ?> সঠিক ইমেইল ঠিকানা দিন।<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
      </div>
    </div>
    <div class="col-12">
      <label class="cf-label" for="<?php echo e($prefix); ?>Message">আপনার বার্তা<span class="req">*</span></label>
      <div class="cf-input-wrap">
        <i class="bi bi-chat-left-text"></i>
        <textarea class="cf-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> cf-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($prefix); ?>Message" name="message"
                  rows="<?php echo e($rows ?? 5); ?>" placeholder="কোন কোর্স নিয়ে জানতে চান, বা আপনার প্রশ্নটি লিখুন..." required><?php echo e(old('message')); ?></textarea>
        <div class="cf-error"><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php else: ?> অনুগ্রহ করে আপনার বার্তা লিখুন।<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
      </div>
    </div>
  </div>
  <div class="cf-foot">
    <span class="cf-note"><i class="bi bi-shield-lock-fill"></i> আপনার তথ্য সম্পূর্ণ গোপন রাখা হবে।</span>
    <button type="submit" class="btn-mint cf-submit">বার্তা পাঠান <i class="bi bi-send-fill"></i></button>
  </div>
</form>
<?php /**PATH /home/infoitcom/public_html/resources/views/frontend/partials/contact-form.blade.php ENDPATH**/ ?>