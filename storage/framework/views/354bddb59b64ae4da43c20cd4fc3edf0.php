<?php $__env->startSection('title', setting('institute_name', 'INFO IT TRAINING CENTER') . ' — দক্ষতা শিখুন, ক্যারিয়ার গড়ুন'); ?>

<?php $__env->startPush('page-styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/home.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- ================= HERO ================= -->
    <header class="hero">
      <div class="container container-xl">
        <div class="row align-items-center gy-5">
          <div class="col-lg-6">

            <div class="hero-badge">
              <span class="dot"></span>
              ভর্তি চলছে · নতুন ব্যাচ শীঘ্রই শুরু
            </div>
            <style>
.hero h1 {
    font-weight: 700;
    line-height: 1.12;
    letter-spacing: -1.8px;
    color: #f8fafc;
    margin-bottom: 22px;
}

/* Main career message */
.hero h1 span {
    font-weight: 800;

    background: linear-gradient(
        90deg,
        #67e8f9,
        #60a5fa,
        #a78bfa
    );

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* INFO-IT brand highlight */
.hero h1 strong {
    font-weight: 900;
    letter-spacing: 0.5px;

    background: linear-gradient(
        90deg,
        #22d3ee,
        #38bdf8
    );

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;

    filter: drop-shadow(0 0 12px rgba(34, 211, 238, 0.25));
}

/* Paragraph */
.hero .lead-copy {
    color: rgba(226, 232, 240, 0.68);
    line-height: 1.75;
    letter-spacing: 0.1px;
}

.hero .lead-copy strong {
    color: #e2e8f0;
    font-weight: 700;
}
            </style>
<h1>
    দক্ষতা শিখুন।
    <span>ক্যারিয়ার গড়ুন।</span>
    আয় শুরু করুন <strong>ইনফো আইটি</strong>&#8288;-এর&nbsp;সাথে
</h1>

<p class="lead-copy">
    <strong><?php echo e(setting('institute_name')); ?></strong>-এ অভিজ্ঞ মেন্টরদের কাছে শিখুন চাকরি ও
    ফ্রিল্যান্সিংয়ের উপযোগী ব্যবহারিক দক্ষতা। রিয়েল প্রজেক্টে কাজ করে অভিজ্ঞতা ও আত্মবিশ্বাস
    অর্জন করুন, আর নিজেকে প্রস্তুত করুন একটি সফল আইটি ক্যারিয়ারের জন্য।
</p>


            <div class="d-flex flex-wrap gap-3">
              <a href="<?php echo e(route('apply')); ?>" class="btn-mint">ভর্তির আবেদন করুন <i class="bi bi-arrow-right"></i></a>
              <a href="<?php echo e(route('courses.index')); ?>" class="btn-outline-navy">কোর্সগুলো দেখুন</a>
            </div>
            <div class="hero-stats">
              <div class="stat"><b><?php echo e(bn_num(number_format($stats['students']))); ?>+</b><span>প্রশিক্ষিত শিক্ষার্থী</span></div>
              <div class="stat"><b><?php echo e(bn_num($stats['trainers'])); ?>+</b><span>অভিজ্ঞ প্রশিক্ষক</span></div>
              <div class="stat"><b><?php echo e(bn_num($stats['courses'])); ?>+</b><span>প্রফেশনাল কোর্স</span></div>
              <div class="stat"><b><?php echo e(bn_num($stats['satisfaction'])); ?>%</b><span>শিক্ষার্থী সন্তুষ্টি</span></div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hero-media">
              <video class="w-100 h-100" autoplay muted loop playsinline controls>
                <source src="<?php echo e($heroVideo); ?>" type="video/mp4">
                আপনার ব্রাউজার ভিডিও সাপোর্ট করে না।
              </video>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- ================= COURSES ================= -->
    <section id="courses" class="section-pad" style="background:var(--cream);">
      <div class="container container-xl">
        <div class="section-head">
          <div>
            <div class="eyebrow">কোর্সসমূহ</div>
            <h2>প্রতিটি প্রোগ্রামের লক্ষ্য একটাই: চাকরি পাওয়ার মতো দক্ষতা</h2>
            <p>প্রতিটি কোর্সে ক্যাপস্টোন প্রজেক্ট, ভেরিফায়েবল সার্টিফিকেট ও কিস্তিতে পেমেন্টের সুবিধা।</p>
          </div>
          <a href="<?php echo e(route('courses.index')); ?>" class="btn-ghost-navy">সকল কোর্স <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-4">
          <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('frontend.partials.course-card', ['c' => $course], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12"><p class="text-center" style="color:var(--ink-soft);">এখনও কোনো কোর্স যুক্ত করা হয়নি।</p></div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- ================= UPCOMING SEMINAR ================= -->
    <section id="seminar" class="section-pad">
      <div class="container container-xl">
        <div class="seminar-panel" id="seminarPanel">
          <div class="row g-4 align-items-stretch">
            <div class="col-lg-8">
              <h2 class="seminar-title">আপকামিং সেমিনার সময়সূচী</h2>
              <p class="seminar-sub">যেকোনো কোর্সে ভর্তির আগে ফ্রি সেমিনারে এসে সরাসরি প্রশিক্ষকের সাথে কথা বলুন।</p>

              <div class="seminar-table-wrap">
                <table class="seminar-table">
                  <thead>
                    <tr>
                      <th>নাম / কোর্স</th>
                      <th>তারিখ</th>
                      <th>সময়</th>
                      <th>রেজিস্ট্রেশন</th>
                      <th>ভেন্যু</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $seminars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                      <tr class="<?php echo \Illuminate\Support\Arr::toCssClasses(['sem-extra' => ! $s->is_featured]); ?>">
                        <td class="td-course" data-label="কোর্স">
                          <a href="<?php echo e($s->course ? route('courses.show', $s->course->slug) : route('courses.index')); ?>" class="sem-course"><?php echo e($s->title); ?></a>
                        </td>
                        <td data-label="তারিখ"><?php echo e($s->date_text); ?></td>
                        <td data-label="সময়"><?php echo e($s->time_label); ?></td>
                        <td class="td-reg" data-label="রেজিস্ট্রেশন"><a href="<?php echo e($s->register_url); ?>" class="btn-register">রেজিস্ট্রেশন করুন</a></td>
                        <td data-label="ভেন্যু">
                          <?php if($s->venue === 'online'): ?>
                            <span class="venue-pill venue-online"><span class="dot"></span>অনলাইন</span>
                          <?php else: ?>
                            <span class="venue-pill venue-offline"><i class="bi bi-geo-alt-fill"></i>অফলাইন</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                      <tr>
                        <td colspan="5" style="text-align:center;color:rgba(255,255,255,.6);padding:2rem 1rem;">এই মুহূর্তে কোনো সেমিনার নির্ধারিত নেই। শীঘ্রই নতুন সময়সূচী জানানো হবে।</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <div class="seminar-actions">
                <button type="button" class="btn-white" id="toggleSeminars" aria-expanded="false">
                  <i class="bi bi-calendar-week"></i> <span>সকল সেমিনার দেখুন</span>
                </button>
                <?php if($liveSeminarLink): ?>
                  <a href="<?php echo e($liveSeminarLink); ?>" class="btn-live" target="_blank" rel="noopener">
                    <span class="live-dot"></span> আজকের লাইভ সেমিনারে জয়েন করুন
                  </a>
                <?php endif; ?>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="seminar-poster">
                <img src="<?php echo e(asset('img/logo.jpg')); ?>" alt="<?php echo e(setting('institute_name')); ?>" class="poster-logo">
                <div class="poster-head">
                  <span class="l1">ইনফো আইটি-তে আপকামিং</span>
                  <span class="l2">ফ্রি সেমিনার</span>
                </div>
                <div class="poster-tags">
                  <span>অফলাইন</span>
                  <span>অনলাইন <b class="live-tag">LIVE</b></span>
                </div>
                <ul class="poster-topics">
                  <?php $__empty_1 = true; $__currentLoopData = $posterTopics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li><i class="bi bi-check-circle-fill"></i> <?php echo e($topic); ?></li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li><i class="bi bi-check-circle-fill"></i> সকল প্রফেশনাল কোর্স</li>
                  <?php endif; ?>
                </ul>
                <div class="poster-free"><i class="bi bi-ticket-perforated-fill"></i> অংশগ্রহণ সম্পূর্ণ ফ্রি — আসন সীমিত</div>
                <div class="poster-foot">
                  <span><i class="bi bi-globe2"></i> <?php echo e(setting('website', 'www.infoittraining.com')); ?></span>
                  <span><i class="bi bi-telephone"></i> <?php echo e(bn_num(setting('phone_short', '০৯৬১২-৩৪৫৬৭৮'))); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ================= CLASS VIDEOS ================= -->
    <section id="videos" class="section-pad" style="background:var(--cream);">
      <div class="container container-xl">
        <div class="section-head">
          <div>
            <div class="eyebrow">ক্লাস ভিডিও</div>
            <h2>আমাদের ক্লাসরুমে কেমন শেখানো হয় — নিজেই দেখুন</h2>
            <p><?php echo e(setting('institute_name')); ?>-এর বাস্তব ক্লাসের কিছু অংশ। ভর্তির আগে দেখে নিন প্রশিক্ষকরা কীভাবে হাতে-কলমে শেখান।</p>
          </div>
          <span class="video-count" id="videoCount"></span>
        </div>

        <div class="row g-4" id="videoGrid"></div>
        <nav class="video-pager" id="videoPager" aria-label="ভিডিও পেজ"></nav>
      </div>
    </section>

    <!-- ভিডিও প্লেয়ার মডাল -->
    <div class="modal fade video-modal" id="videoModal" tabindex="-1" aria-labelledby="videoModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="videoModalTitle"></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="বন্ধ করুন"></button>
          </div>
          <div class="ratio ratio-16x9" id="videoModalBody"></div>
        </div>
      </div>
    </div>

    <!-- ================= CONTACT (HOME) ================= -->
    <section id="contact" class="section-pad contact-home">
      <div class="container container-xl">
        <div class="contact-home-head">
          <div class="eyebrow">যোগাযোগ</div>
          <h2>প্রশ্ন লিখে পাঠান, আমরাই আপনাকে কল করব</h2>
          <p>কোন কোর্স আপনার জন্য উপযুক্ত, ফি কত বা নতুন ব্যাচ কবে — ফর্মটি পূরণ করুন, আমাদের অ্যাডমিশন টিম এক কর্মদিবসের মধ্যে যোগাযোগ করবে।</p>
        </div>
        <div class="cf-card">
          <?php echo $__env->make('frontend.partials.contact-form', ['prefix' => 'home', 'source' => 'home'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="contact-quick">
          <span>অথবা সরাসরি:</span>
          <a href="tel:<?php echo e(setting('phone', '+8801723296130')); ?>"><i class="bi bi-telephone-fill"></i> <?php echo e(bn_num(setting('phone_display', '+880 9612 345 678'))); ?></a>
          <a href="https://wa.me/<?php echo e(setting('whatsapp', '8801712345678')); ?>" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i> হোয়াটসঅ্যাপে মেসেজ</a>
          <a href="<?php echo e(route('contact')); ?>"><i class="bi bi-geo-alt-fill"></i> ক্যাম্পাসের ঠিকানা</a>
        </div>
      </div>
    </section>

    <!-- ================= BATCHES ================= -->
    <?php if($batches->isNotEmpty()): ?>
      <section class="section-pad" style="background:var(--cream);">
        <div class="container container-xl">
          <div class="eyebrow">আসন্ন ব্যাচ</div>
          <h2 style="font-size:1.9rem;font-weight:700;max-width:640px;">আসন, সময়সূচি ও ভর্তির শেষ তারিখ</h2>
          <p style="color:var(--ink-soft);font-size:.95rem;max-width:560px;margin-top:.7rem;">চাকরিজীবী ও বিশ্ববিদ্যালয়ের শিক্ষার্থীদের কথা মাথায় রেখে সন্ধ্যা ও রাতের ব্যাচ।</p>

          <div class="row g-4 mt-3">
            <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-md-6 col-lg-4">
                <div class="batch-card">
                  <div class="batch-top">
                    <span class="batch-id"><?php echo e($b->batch_no); ?></span>
                    <span class="status-pill <?php echo e($b->batch_status === 'খোলা' ? 'status-open' : 'status-almost'); ?>"><?php echo e($b->batch_status); ?></span>
                  </div>
                  <h3><?php echo e($b->title); ?></h3>
                  <div class="batch-line"><i class="bi bi-calendar-event"></i> শুরু: <?php echo e($b->batch_start); ?></div>
                  <div class="batch-line"><i class="bi bi-clock"></i> <?php echo e($b->batch_schedule); ?></div>
                  <div class="batch-line"><i class="bi bi-hourglass-split"></i> আবেদনের শেষ তারিখ: <?php echo e($b->batch_deadline); ?></div>
                  <div class="progress-thin">
                    <div class="bar" style="width:<?php echo e($b->seat_progress); ?>%"></div>
                  </div>
                  <div class="seat-txt">
                    <span><?php echo e(bn_num($b->total_seats)); ?>টি আসনের মধ্যে <?php echo e(bn_num($b->seats_left)); ?>টি বাকি</span>
                    <span><?php echo e(bn_money($b->price)); ?></span>
                  </div>
                  <a href="<?php echo e(route('apply', ['course' => $b->slug])); ?>" class="btn-navy w-100 text-center d-block">আসন নিশ্চিত করুন</a>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  /* ভিডিওগুলো অ্যাডমিন প্যানেল থেকে আসে */
  const CLASS_VIDEOS = <?php echo json_encode($videos, 15, 512) ?>;

  function toBn(v){ return String(v).replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]); }

  /* ---------- সেমিনার: সকল সেমিনার দেখুন ---------- */
  (function () {
    const panel = document.getElementById('seminarPanel');
    const btn = document.getElementById('toggleSeminars');
    if (!panel.querySelector('.sem-extra')) { btn.hidden = true; return; }
    btn.addEventListener('click', () => {
      const open = panel.classList.toggle('show-all');
      btn.setAttribute('aria-expanded', open);
      btn.querySelector('span').textContent = open ? 'কম দেখুন' : 'সকল সেমিনার দেখুন';
    });
  })();

  /* ---------- ক্লাস ভিডিও: প্রতি পেজে ৯টি + পরবর্তী বাটন ---------- */
  (function () {
    const PER_PAGE = 9;
    const grid = document.getElementById('videoGrid');
    const pager = document.getElementById('videoPager');
    const section = document.getElementById('videos');
    const totalPages = Math.max(1, Math.ceil(CLASS_VIDEOS.length / PER_PAGE));
    let page = 1;

    document.getElementById('videoCount').innerHTML =
      `<i class="bi bi-collection-play"></i> মোট ${toBn(CLASS_VIDEOS.length)}টি ভিডিও`;

    function esc(v){ return String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

    const thumb = v => v.poster
      ? `<img src="${esc(v.poster)}" alt="" loading="lazy">`
      : (v.youtube
        ? `<img src="https://img.youtube.com/vi/${esc(v.youtube)}/hqdefault.jpg" alt="" loading="lazy">`
        : `<video src="${esc(v.src)}#t=1.5" preload="metadata" muted playsinline tabindex="-1" aria-hidden="true"></video>`);

    function render() {
      if (!CLASS_VIDEOS.length) {
        grid.innerHTML = `<div class="col-12"><p class="text-center" style="color:var(--ink-soft);">এখনও কোনো ক্লাস ভিডিও যুক্ত করা হয়নি।</p></div>`;
        pager.innerHTML = '';
        return;
      }
      const start = (page - 1) * PER_PAGE;
      grid.innerHTML = CLASS_VIDEOS.slice(start, start + PER_PAGE).map((v, i) => `
        <div class="col-md-6 col-lg-4">
          <button type="button" class="video-card" data-idx="${start + i}" aria-label="${esc(v.title)} ভিডিওটি চালান">
            <div class="video-thumb">
              ${thumb(v)}
              ${v.batch ? `<span class="video-batch">${esc(v.batch)}</span>` : ''}
              <span class="play-btn"><i class="bi bi-play-fill"></i></span>
              ${v.duration ? `<span class="video-dur">${esc(v.duration)}</span>` : ''}
            </div>
            <div class="video-body">
              <span class="video-course">${esc(v.course || '')}</span>
              <h3>${esc(v.title)}</h3>
              ${v.date ? `<div class="video-meta"><i class="bi bi-calendar3"></i> ${esc(v.date)}</div>` : ''}
            </div>
          </button>
        </div>`).join('');

      if (totalPages <= 1) { pager.innerHTML = ''; return; }
      let nums = '';
      for (let p = 1; p <= totalPages; p++) {
        nums += `<button type="button" class="pager-btn num ${p === page ? 'current' : ''}" data-page="${p}" ${p === page ? 'aria-current="page"' : ''}>${toBn(p)}</button>`;
      }
      pager.innerHTML = `
        <button type="button" class="pager-btn" data-page="${page - 1}" ${page === 1 ? 'disabled' : ''}><i class="bi bi-chevron-left"></i> পূর্ববর্তী</button>
        ${nums}
        <button type="button" class="pager-btn next" data-page="${page + 1}" ${page === totalPages ? 'disabled' : ''}>পরবর্তী <i class="bi bi-chevron-right"></i></button>
        <div class="pager-info">পৃষ্ঠা ${toBn(page)} / ${toBn(totalPages)}</div>`;
    }

    pager.addEventListener('click', e => {
      const b = e.target.closest('[data-page]');
      if (!b || b.disabled) return;
      page = Math.min(totalPages, Math.max(1, +b.dataset.page));
      render();
      window.scrollTo({ top: section.offsetTop - 70, behavior: 'smooth' });
    });

    const modalEl = document.getElementById('videoModal');
    const modal = new bootstrap.Modal(modalEl);
    const body = document.getElementById('videoModalBody');
    grid.addEventListener('click', e => {
      const card = e.target.closest('.video-card');
      if (!card) return;
      const v = CLASS_VIDEOS[card.dataset.idx];
      document.getElementById('videoModalTitle').textContent = v.title;
      body.innerHTML = v.youtube
        ? `<iframe src="https://www.youtube.com/embed/${v.youtube}?autoplay=1&rel=0" title="${v.title}" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe>`
        : `<video src="${v.src}" controls autoplay playsinline></video>`;
      modal.show();
    });
    modalEl.addEventListener('hidden.bs.modal', () => { body.innerHTML = ''; });

    render();
  })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/infoitcom/public_html/resources/views/frontend/home.blade.php ENDPATH**/ ?>