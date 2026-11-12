
<?php if($paginator->total() > 0): ?>
<div class="table-foot">
  <div>Showing <?php echo e($paginator->firstItem()); ?>–<?php echo e($paginator->lastItem()); ?> of <?php echo e($paginator->total()); ?></div>
  <?php if($paginator->hasPages()): ?>
  <ul class="pager">
    <li><?php if($paginator->onFirstPage()): ?><span class="off"><i class="bi bi-chevron-left"></i></span><?php else: ?><a href="<?php echo e($paginator->previousPageUrl()); ?>"><i class="bi bi-chevron-left"></i></a><?php endif; ?></li>
    <?php $__currentLoopData = $paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <li><?php if($page == $paginator->currentPage()): ?><span class="on"><?php echo e($page); ?></span><?php else: ?><a href="<?php echo e($url); ?>"><?php echo e($page); ?></a><?php endif; ?></li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <li><?php if($paginator->hasMorePages()): ?><a href="<?php echo e($paginator->nextPageUrl()); ?>"><i class="bi bi-chevron-right"></i></a><?php else: ?><span class="off"><i class="bi bi-chevron-right"></i></span><?php endif; ?></li>
  </ul>
  <?php endif; ?>
</div>
<?php endif; ?>
<?php /**PATH /home/infoitcom/public_html/resources/views/admin/partials/pagination.blade.php ENDPATH**/ ?>