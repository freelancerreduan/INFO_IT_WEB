<?php $editing = $student->exists; ?>

<?php $__env->startSection('title', $editing ? 'Edit Student' : 'Add Student'); ?>
<?php $__env->startSection('subtitle', $editing ? 'Update student information.' : 'Register a new student.'); ?>

<?php $__env->startSection('content'); ?>
<section class="page-sec show" id="page-add-student">
  <div class="page-header">
    <div>
      <h2><?php echo e($editing ? 'Edit Student' : 'Add Student'); ?></h2>
      <p><?php echo e($editing ? 'Update student details and assigned course.' : 'Register a new student and assign a course.'); ?></p>
    </div>
  </div>
  <div class="row g-3">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header"><div class="section-title">Student Information</div></div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data"
                action="<?php echo e($editing ? route('admin.students.update', $student) : route('admin.students.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if($editing): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Select Course</label>
                <select class="form-select <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="courseSelect" name="course_id" onchange="renderCourseInfo(this.value)">
                  <option value="">Choose a course…</option>
                  <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c['id']); ?>" <?php if(old('course_id', $currentCourseId) == $c['id']): echo 'selected'; endif; ?>><?php echo e($c['name']); ?></option>
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
              <div class="col-md-6">
                <label class="form-label">Student Name</label>
                <input type="text" name="name" value="<?php echo e(old('name', $student->name)); ?>" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Farhan Rahman">
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
                <label class="form-label">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email', $student->email)); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="student@email.com">
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
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" value="<?php echo e(old('phone', $student->phone)); ?>" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="01XXXXXXXXX">
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
                <label class="form-label">Parent / Guardian Number</label>
                <input type="text" name="parent_phone" value="<?php echo e(old('parent_phone', $student->parent_phone)); ?>" class="form-control <?php $__errorArgs = ['parent_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="01XXXXXXXXX">
                <?php $__errorArgs = ['parent_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
              <div class="<?php echo e($editing ? 'col-md-6' : 'col-12'); ?>">
                <label class="form-label">NID / Birth Registration Number</label>
                <input type="text" name="nid" value="<?php echo e(old('nid', $student->nid)); ?>" class="form-control <?php $__errorArgs = ['nid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Enter ID number">
                <?php $__errorArgs = ['nid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
              <?php if($editing): ?>
                <div class="col-md-6">
                  <label class="form-label">Status</label>
                  <select class="form-select" name="status">
                    <?php $__currentLoopData = \App\Models\User::STUDENT_STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($val); ?>" <?php if(old('status', $student->status ?? 'active') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
              <?php endif; ?>
              <div class="col-12">
                <label class="form-label">Passport Size Photo</label>
                <?php echo $__env->make('admin.partials.upload', [
                  'zone' => 'studentPhotoZone', 'name' => 'photo', 'icon' => 'bi-cloud-arrow-up',
                  'text' => 'Click to upload photo', 'hint' => 'PNG or JPG, up to 2MB',
                  'existing' => $editing && $student->photo ? $student->photo_url : null,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
              </div>
            </div>
            <?php if (! ($editing)): ?>
              <div class="cell-sub mt-3"><i class="bi bi-info-circle me-1"></i>A login password will be generated and shown after submit — the student can log in to the student dashboard with it.</div>
            <?php endif; ?>
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-accent px-4"><i class="bi bi-check2 me-1"></i><?php echo e($editing ? 'Update Student' : 'Submit Student'); ?></button>
              <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-outline-accent px-4">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card" id="courseInfoCard">
        <div class="empty-state">
          <i class="bi bi-mortarboard"></i>
          <p class="mt-3 mb-0">Select a course to view course information.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const COURSES = <?php echo json_encode($courses, 15, 512) ?>;

function renderCourseInfo(courseId){
  const card = document.getElementById('courseInfoCard');
  if(!courseId){
    card.innerHTML = `<div class="empty-state"><i class="bi bi-mortarboard"></i><p class="mt-3 mb-0">Select a course to view course information.</p></div>`;
    return;
  }
  const c = COURSES.find(x=>x.id===String(courseId));
  if(!c) return;
  card.innerHTML = `
    <img src="${esc(c.thumb)}" class="course-thumb" alt="${esc(c.name)}">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="fw-bold" style="font-family:'Sora';font-size:16px">${esc(c.name)}</div>
          <span class="badge rounded-pill" style="background:var(--accent-soft);color:var(--accent-ink);font-weight:600">${esc(c.category)}</span>
        </div>
        ${statusPill(c.status==='Active'?'active':'inactive')}
      </div>
      <p class="text-muted small mt-2 mb-3">${esc(c.desc)}</p>
      <div class="meta-row"><span>Course Price</span><span>${money(c.discount)} ${c.discount < c.price ? `<span class="text-muted text-decoration-line-through fw-normal">${money(c.price)}</span>` : ''}</span></div>
      <div class="meta-row"><span>Duration</span><span>${esc(c.duration)}</span></div>
      <div class="meta-row"><span>Total Modules</span><span>${esc(c.modules)}</span></div>
      <div class="meta-row"><span>Total Classes</span><span>${esc(c.classes)}</span></div>
      <div class="meta-row"><span>Instructor</span><span>${esc(c.instructor)}</span></div>
      <div class="meta-row"><span>Rating</span><span><i class="bi bi-star-fill text-warning"></i> ${esc(c.rating)}</span></div>
      <div class="meta-row"><span>Admission Status</span><span>${c.status==='Active'?'Open':'Closed'}</span></div>
    </div>`;
}
renderCourseInfo(document.getElementById('courseSelect').value);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/infoitcom/public_html/resources/views/admin/students/create.blade.php ENDPATH**/ ?>