<?php $__env->startSection('title', 'Student List'); ?>
<?php $__env->startSection('subtitle', 'Manage enrolled students.'); ?>

<?php $__env->startSection('content'); ?>
<section class="page-sec show" id="page-student-list">
  <div class="page-header">
    <div><h2>Student List</h2><p>All enrolled students in your institute.</p></div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-accent" href="<?php echo e(route('admin.students.print', request()->query())); ?>" target="_blank" rel="noopener"><i class="bi bi-printer me-1"></i>Print</a>
      <a class="btn btn-accent" href="<?php echo e(route('admin.students.create')); ?>"><i class="bi bi-plus-lg me-1"></i>Add Student</a>
    </div>
  </div>

  <form class="filter-bar mb-3" method="GET" action="<?php echo e(route('admin.students.index')); ?>">
    <div class="row g-2 align-items-end">
      <div class="col-md-3"><label class="form-label">Search Student</label><input class="form-control" placeholder="Student ID, name, email or phone" id="stuSearch" name="q" value="<?php echo e(request('q')); ?>"></div>
      <div class="col-md-2"><label class="form-label">Course</label>
        <select class="form-select" id="stuCourseFilter" name="course">
          <option value="">All courses</option>
          <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($c->id); ?>" <?php if(request('course') == $c->id): echo 'selected'; endif; ?>><?php echo e($c->title); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Status</label>
        <select class="form-select" id="stuStatusFilter" name="status">
          <option value="">All status</option>
          <?php $__currentLoopData = \App\Models\User::STUDENT_STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($val); ?>" <?php if(request('status') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Date</label><input type="date" class="form-control" id="stuDateFilter" name="date" value="<?php echo e(request('date')); ?>"></div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search me-1"></i>Search</button>
        <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-outline-accent flex-fill">Reset</a>
      </div>
    </div>
  </form>

  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>SL</th><th>Student ID</th><th>Student</th><th>Email</th><th>Phone</th><th>Course</th><th>Enrolled</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody id="studentTableBody">
          <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $enrolled = $s->first_enrolled_at ? \Illuminate\Support\Carbon::parse($s->first_enrolled_at)->format('Y-m-d') : $s->created_at?->format('Y-m-d');
              // Course filter dile oi course er naam-i dekhabe
              $courseName = (request('course') ? $courses->firstWhere('id', (int) request('course'))?->title : null)
                  ?? $s->latestEnrollment?->course?->title ?? '—';
            ?>
            <tr>
              <td class="cell-sub"><?php echo e($students->firstItem() + $loop->index); ?></td>
              <td class="cell-sub text-nowrap fw-semibold"><?php echo e($s->student_id ?: '—'); ?></td>
              <td><div class="d-flex align-items-center gap-2"><img src="<?php echo e($s->photo_url); ?>" class="row-photo"><span class="cell-name"><?php echo e($s->name); ?></span></div></td>
              <td class="cell-sub"><?php echo e($s->email); ?></td>
              <td class="cell-sub"><?php echo e($s->phone ?: '—'); ?></td>
              <td><?php echo e($courseName); ?></td>
              <td class="cell-sub"><?php echo e($enrolled); ?></td>
              <td><?php echo e(status_pill($s->status ?? 'active')); ?></td>
              <td>
                <div class="d-flex gap-1">
                  <button class="act-btn" title="View" data-view="student" data-payload="<?php echo e(json_encode([
                    'sid' => $s->student_id ?: '—', 'name' => $s->name, 'email' => $s->email, 'phone' => $s->phone ?: '—', 'parent' => $s->parent_phone ?: '—',
                    'nid' => $s->nid ?: '—', 'course' => $courseName, 'date' => $enrolled,
                    'status' => $s->status_label, 'photo' => $s->photo_url,
                  ])); ?>"><i class="bi bi-eye"></i></button>
                  <a class="act-btn" title="Edit" href="<?php echo e(route('admin.students.edit', $s)); ?>"><i class="bi bi-pencil"></i></a>
                  <button class="act-btn danger js-confirm" title="Delete" data-type="student" data-name="<?php echo e($s->name); ?>" data-url="<?php echo e(route('admin.students.destroy', $s)); ?>"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php echo $__env->make('admin.partials.empty-row', ['cols' => 9, 'text' => 'No students match your filters.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php echo $__env->make('admin.partials.pagination', ['paginator' => $students], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/infoitcom/public_html/resources/views/admin/students/index.blade.php ENDPATH**/ ?>