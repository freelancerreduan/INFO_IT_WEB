@php
  $USER_NAV = [
    ['label' => 'Dashboard',       'icon' => 'bi-grid-1x2',         'url' => route('user.dashboard'),         'active' => ['user.dashboard']],
    ['label' => 'New Order',       'icon' => 'bi-bag-plus',         'url' => route('courses.index'),          'active' => [], 'class' => 'new-order'],
    ['label' => 'My Courses',      'icon' => 'bi-journal-bookmark', 'url' => route('user.enrollments.index'), 'active' => ['user.enrollments.*']],
    ['label' => 'Order History',   'icon' => 'bi-receipt',          'url' => route('user.orders.index'),      'active' => ['user.orders.*']],
    ['label' => 'Add Payment',     'icon' => 'bi-wallet2',          'url' => route('user.payments.create'),   'active' => ['user.payments.create']],
    ['label' => 'Payment History', 'icon' => 'bi-cash-coin',        'url' => route('user.payments.index'),    'active' => ['user.payments.index']],
    ['label' => 'Profile',         'icon' => 'bi-person-circle',    'url' => route('user.profile.edit'),      'active' => ['user.profile.*']],
  ];
@endphp
<div class="nav-label">Menu</div>
@foreach ($USER_NAV as $n)
  <a class="side-link {{ $n['class'] ?? '' }} {{ $n['active'] && request()->routeIs(...$n['active']) ? 'active' : '' }}" href="{{ $n['url'] }}">
    <i class="bi {{ $n['icon'] }} side-ic"></i>{{ $n['label'] }}
  </a>
@endforeach
