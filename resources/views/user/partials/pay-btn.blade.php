{{-- usage: @include('user.partials.pay-btn', ['o' => $order, 'full' => true]) — $o->payments loaded thakte hobe --}}
@php
  $payload = [
    'title'    => 'Payments · ' . $o->order_no,
    'course'   => $o->course?->title ?? '—',
    'fee'      => (float) $o->fee,
    'paid'     => $o->paidTotal(),
    'due'      => $o->due(),
    'payments' => $o->payments->map(fn ($p) => [
      'date'   => $p->paid_at?->format('d M Y'),
      'trx'    => $p->trx_id,
      'method' => $p->method,
      'amount' => (float) $p->amount,
      'status' => $p->student_status_label,
    ])->values(),
  ];
@endphp
@if (! empty($full))
  <button type="button" class="btn btn-sm btn-outline-accent" data-pay-history="{{ json_encode($payload) }}"><i class="bi bi-wallet2 me-1"></i>Payments ({{ $o->payments->count() }})</button>
@else
  <button type="button" class="act-btn" title="Payment history" data-pay-history="{{ json_encode($payload) }}"><i class="bi bi-wallet2"></i></button>
@endif
