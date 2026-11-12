
<div class="col-md-6 col-lg-4 course-item" data-category="<?php echo e($c->category); ?>">
  <div class="course-card">
    <div class="course-thumb" style="background-image:url('<?php echo e($c->thumb_url); ?>');">
      <span class="badge-cat"><?php echo e($c->category); ?></span>
    </div>
    <div class="course-body">
      <div class="course-meta-top">
        <span class="rating"><i class="bi bi-star-fill"></i> <?php echo e(bn_num($c->rating)); ?></span>
        <span><i class="bi bi-people"></i> <?php echo e(bn_num(number_format($c->enrollments_count ?? $c->enrollments()->count()))); ?></span>
      </div>
      <h3><?php echo e($c->title); ?></h3>
      <p class="desc"><?php echo e($c->short_desc); ?></p>
      <?php if (! ($hideFacts ?? false)): ?>
        <div class="course-facts">
          <?php if($c->duration): ?><span><i class="bi bi-calendar3"></i> <?php echo e($c->duration); ?></span><?php endif; ?>
          <?php if($c->classes): ?><span><i class="bi bi-collection-play"></i> <?php echo e($c->classes); ?></span><?php endif; ?>
          <?php if($c->instructor): ?><span><i class="bi bi-person"></i> <?php echo e($c->instructor); ?></span><?php endif; ?>
        </div>
      <?php endif; ?>
      <div class="course-price-row">
        <div>
          <span class="price-now"><?php echo e(bn_money($c->price)); ?></span>
          <?php if($c->old_price): ?><span class="price-old"><?php echo e(bn_money($c->old_price)); ?></span><?php endif; ?>
        </div>
        <a href="<?php echo e(route('courses.show', $c->slug)); ?>" class="btn-ghost-navy btn-sm">বিস্তারিত</a>
      </div>
    </div>
  </div>
</div>
<?php /**PATH /home/infoitcom/public_html/resources/views/frontend/partials/course-card.blade.php ENDPATH**/ ?>