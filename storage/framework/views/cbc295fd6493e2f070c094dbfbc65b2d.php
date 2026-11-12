<?php $__env->startSection('title', 'Transactions'); ?>
<?php $__env->startSection('subtitle', 'Track institute payments.'); ?>

<?php $__env->startSection('content'); ?>
<section class="page-sec show" id="page-transactions">
  <div class="page-header">
    <div><h2>Transactions</h2><p>Track every payment made to your institute.</p></div>
    <a class="btn btn-accent" href="<?php echo e(route('admin.transactions.create')); ?>"><i class="bi bi-plus-lg me-1"></i>Add Transaction</a>
  </div>
  <?php if($pendingCount > 0 && request('status') !== 'pending'): ?>
    <div class="flash mb-3" style="background:var(--warn-soft);color:var(--warn)">
      <i class="bi bi-hourglass-split"></i>
      <span><b><?php echo e($pendingCount); ?></b> payment(s) waiting for approval.</span>
      <a href="<?php echo e(route('admin.transactions.index', ['status' => 'pending'])); ?>" class="ms-auto fw-semibold" style="color:inherit">View pending <i class="bi bi-arrow-right"></i></a>
    </div>
  <?php endif; ?>
  <form class="filter-bar mb-3" method="GET" action="<?php echo e(route('admin.transactions.index')); ?>">
    <div class="row g-2 align-items-end">
      <div class="col-md-3"><label class="form-label">Search TRX ID / Student ID</label><input class="form-control" name="q" value="<?php echo e(request('q')); ?>" placeholder="TRX ID, Student ID, name, phone"></div>
      <div class="col-md-2"><label class="form-label">Payment Method</label>
        <select class="form-select" name="method">
          <option value="">All</option>
          <?php $__currentLoopData = \App\Models\Payment::METHODS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option <?php if(request('method') === $m): echo 'selected'; endif; ?>><?php echo e($m); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Status</label>
        <select class="form-select" name="status">
          <option value="">All</option>
          <?php $__currentLoopData = \App\Models\Payment::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($val); ?>" <?php if(request('status') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Date</label><input type="date" class="form-control" name="date" value="<?php echo e(request('date')); ?>"></div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search me-1"></i>Search</button>
        <a href="<?php echo e(route('admin.transactions.index')); ?>" class="btn btn-outline-accent flex-fill">Reset</a>
      </div>
    </div>
    <div class="text-end mt-2">
      <a href="<?php echo e(route('admin.transactions.export', request()->query())); ?>" class="btn btn-sm btn-outline-accent"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</a>
    </div>
  </form>
  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>Transaction ID</th><th>Payment Type</th><th>Account No.</th><th>Student</th><th>Course</th><th>Amount</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody id="txTableBody">
          <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="cell-sub"><?php echo e($t->trx_id); ?></td>
              <td><?php echo e($t->method); ?></td>
              <td class="cell-sub"><?php echo e($t->account_no ?: '—'); ?></td>
              <td><div class="cell-name"><?php echo e($t->user?->name ?? '—'); ?></div><?php if($t->user?->student_id): ?><div class="cell-sub small"><?php echo e($t->user->student_id); ?></div><?php endif; ?></td>
              <td><?php echo e($t->enrollment?->course?->title ?? '—'); ?></td>
              <td><?php echo e(money($t->amount)); ?></td>
              <td><?php echo e(status_pill($t->status_label)); ?></td>
              <td class="cell-sub"><?php echo e($t->paid_at?->format('Y-m-d')); ?></td>
              <td>
                <div class="d-flex gap-1">
                  <button class="act-btn" title="View" data-view="details" data-payload="<?php echo e(json_encode([
                    'title' => 'Transaction ' . $t->trx_id,
                    'rows' => [
                      ['Transaction ID', $t->trx_id], ['Payment Type', $t->method], ['Account No.', $t->account_no ?: '—'],
                      ['Student', $t->user?->name ?? '—'], ['Course', $t->enrollment?->course?->title ?? '—'], ['Amount', money($t->amount)],
                      ['Order', $t->enrollment?->order_no ?? '—'], ['Note', $t->note ?: '—'], ['Date', $t->paid_at?->format('Y-m-d')],
                    ],
                    'status' => $t->status_label,
                  ])); ?>"><i class="bi bi-eye"></i></button>
                  <?php if($t->status !== 'paid'): ?>
                  <button class="act-btn ok js-confirm" title="Approve" data-method="PATCH" data-status="paid"
                    data-title="Approve transaction?" data-text="Approving <?php echo e($t->trx_id); ?> will reduce <?php echo e($t->user?->name); ?>'s due by <?php echo e(money($t->amount)); ?>." data-label="Approve"
                    data-url="<?php echo e(route('admin.transactions.status', $t)); ?>"><i class="bi bi-check2"></i></button>
                  <?php endif; ?>
                  <?php if($t->status !== 'rejected'): ?>
                  <button class="act-btn danger js-confirm" title="Reject" data-method="PATCH" data-status="rejected"
                    data-title="Reject transaction?" data-text="If you reject <?php echo e($t->trx_id); ?>, the student will see it as Failed in their panel." data-label="Reject"
                    data-url="<?php echo e(route('admin.transactions.status', $t)); ?>"><i class="bi bi-x-lg"></i></button>
                  <?php endif; ?>
                  <button class="act-btn danger js-confirm" title="Delete" data-type="transaction" data-name="<?php echo e($t->trx_id); ?>"
                    data-url="<?php echo e(route('admin.transactions.destroy', $t)); ?>"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php echo $__env->make('admin.partials.empty-row', ['cols' => 9, 'text' => 'No transactions match your filters.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php echo $__env->make('admin.partials.pagination', ['paginator' => $transactions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/infoitcom/public_html/resources/views/admin/transactions/index.blade.php ENDPATH**/ ?>