@php
  /*
   | NAVIGATION CONFIG (same as original NAV array)
   | 'active' = route name patterns that mark the link as active
   */
  // Finance menu er badge: pending order + approve er opekkhay thaka payment
  $pendingOrders   = \App\Models\Enrollment::where('order_status', 'pending')->count();
  $pendingPayments = \App\Models\Payment::where('status', 'pending')->count();

  $NAV = [
    ['type' => 'link', 'id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-1x2', 'route' => 'admin.dashboard', 'active' => ['admin.dashboard']],
    ['type' => 'group', 'id' => 'student', 'label' => 'Student', 'icon' => 'bi-people', 'items' => [
      ['label' => 'Add Student',  'route' => 'admin.students.create', 'active' => ['admin.students.create']],
      ['label' => 'Student List', 'route' => 'admin.students.index',  'active' => ['admin.students.index', 'admin.students.edit']],
      ['label' => 'Applications', 'route' => 'admin.applications.index', 'active' => ['admin.applications.*']],
    ]],
    ['type' => 'group', 'id' => 'finance', 'label' => 'Finance', 'icon' => 'bi-cash-coin', 'badge' => $pendingOrders + $pendingPayments, 'items' => [
      ['label' => 'Transactions', 'route' => 'admin.transactions.index', 'active' => ['admin.transactions.*'], 'badge' => $pendingPayments],
      ['label' => 'Add Order',    'route' => 'admin.orders.create',      'active' => ['admin.orders.create']],
      ['label' => 'Orders',       'route' => 'admin.orders.index',       'active' => ['admin.orders.index', 'admin.orders.show'], 'badge' => $pendingOrders],
      ['label' => 'Add Payment',  'route' => 'admin.payments.create',    'active' => ['admin.payments.*']],
      ['label' => 'Costing',      'route' => 'admin.costing.index',      'active' => ['admin.costing.*']],
    ]],
    ['type' => 'group', 'id' => 'course', 'label' => 'Course', 'icon' => 'bi-journal-bookmark', 'items' => [
      ['label' => 'Add Course',  'route' => 'admin.courses.create', 'active' => ['admin.courses.create']],
      ['label' => 'Course List', 'route' => 'admin.courses.index',  'active' => ['admin.courses.index', 'admin.courses.edit']],
    ]],
    ['type' => 'group', 'id' => 'seminar', 'label' => 'Seminar', 'icon' => 'bi-megaphone', 'items' => [
      ['label' => 'Add Seminar',  'route' => 'admin.seminars.create', 'active' => ['admin.seminars.create']],
      ['label' => 'Seminar List', 'route' => 'admin.seminars.index',  'active' => ['admin.seminars.index', 'admin.seminars.edit']],
    ]],
    ['type' => 'group', 'id' => 'video', 'label' => 'Class Video', 'icon' => 'bi-play-btn', 'items' => [
      ['label' => 'Add Video',  'route' => 'admin.videos.create', 'active' => ['admin.videos.create']],
      ['label' => 'Video List', 'route' => 'admin.videos.index',  'active' => ['admin.videos.index', 'admin.videos.edit']],
    ]],
    ['type' => 'group', 'id' => 'gallery', 'label' => 'Gallery', 'icon' => 'bi-images', 'items' => [
      ['label' => 'Add Gallery',  'route' => 'admin.gallery.create', 'active' => ['admin.gallery.create']],
      ['label' => 'Gallery List', 'route' => 'admin.gallery.index',  'active' => ['admin.gallery.index', 'admin.gallery.edit']],
    ]],
    ['type' => 'link', 'id' => 'messages', 'label' => 'Messages', 'icon' => 'bi-chat-left-text', 'route' => 'admin.messages.index', 'active' => ['admin.messages.*'], 'badge' => \App\Models\ContactMessage::where('status', 'new')->count()],
    ['type' => 'link', 'id' => 'reports',  'label' => 'Reports',  'icon' => 'bi-bar-chart-line', 'route' => 'admin.reports.index',  'active' => ['admin.reports.*']],
    ['type' => 'link', 'id' => 'settings', 'label' => 'Settings', 'icon' => 'bi-gear',           'route' => 'admin.settings.index', 'active' => ['admin.settings.*']],
  ];
@endphp

@foreach ($NAV as $n)
  @if ($n['type'] === 'link')
    <a class="side-link {{ request()->routeIs(...$n['active']) ? 'active' : '' }}" href="{{ route($n['route']) }}">
      <i class="bi {{ $n['icon'] }} side-ic"></i>{{ $n['label'] }}
      @if (! empty($n['badge'])) <span class="side-badge">{{ $n['badge'] }}</span> @endif
    </a>
  @else
    @php $open = collect($n['items'])->contains(fn ($it) => request()->routeIs(...$it['active'])); @endphp
    <div class="side-link" data-toggle-group="{{ $n['id'] }}" aria-expanded="{{ $open ? 'true' : 'false' }}" onclick="toggleGroup('{{ $n['id'] }}',this)">
      <i class="bi {{ $n['icon'] }} side-ic"></i>{{ $n['label'] }}
      @if (! empty($n['badge'])) <span class="side-badge">{{ $n['badge'] }}</span> @endif
      <i class="bi bi-chevron-right chev {{ ! empty($n['badge']) ? 'ms-2' : '' }}"></i>
    </div>
    <div class="collapse side-sub {{ $open ? 'show' : '' }}" id="grp-{{ $n['id'] }}-{{ $navKey }}">
      @foreach ($n['items'] as $it)
        <a class="side-link {{ request()->routeIs(...$it['active']) ? 'active' : '' }}" href="{{ route($it['route']) }}">{{ $it['label'] }}
          @if (! empty($it['badge'])) <span class="side-badge" title="Pending">{{ $it['badge'] }}</span> @endif
        </a>
      @endforeach
    </div>
  @endif
@endforeach
