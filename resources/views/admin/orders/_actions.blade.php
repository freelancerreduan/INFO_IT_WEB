{{-- Order action buttons: Enroll / Reject / Cancel / Delete (+ Restore / Delete permanently for deleted) --}}
@php $st = $o->order_status; @endphp
@if ($st === 'deleted')
  <button class="act-btn ok js-confirm" title="Restore (Pending)" data-method="PATCH" data-status="pending"
    data-title="Restore order?" data-text="{{ $o->order_no }} will go back to Pending." data-label="Restore"
    data-url="{{ route('admin.orders.status', $o) }}"><i class="bi bi-arrow-counterclockwise"></i></button>
  <button class="act-btn danger js-confirm" title="Delete permanently" data-type="order" data-name="{{ $o->order_no }}"
    data-url="{{ route('admin.orders.destroy', $o) }}"><i class="bi bi-trash3-fill"></i></button>
@else
  @if ($o->isOpen() && $o->due() > 0)
    <a class="act-btn" title="Add payment" href="{{ route('admin.payments.create', ['order' => $o->id]) }}"><i class="bi bi-plus-circle"></i></a>
  @endif
  @if ($st !== 'completed')
    <button class="act-btn ok js-confirm" title="Enroll" data-method="PATCH" data-status="completed"
      data-title="Enroll student?" data-text="{{ $o->order_no }} will be Enrolled and the course will count on the student's dashboard." data-label="Enroll"
      data-url="{{ route('admin.orders.status', $o) }}"><i class="bi bi-check2-circle"></i></button>
  @endif
  @if ($st !== 'rejected')
    <button class="act-btn danger js-confirm" title="Reject" data-method="PATCH" data-status="rejected"
      data-title="Reject order?" data-text="Mark {{ $o->order_no }} as rejected." data-label="Reject"
      data-url="{{ route('admin.orders.status', $o) }}"><i class="bi bi-x-lg"></i></button>
  @endif
  @if ($st !== 'canceled')
    <button class="act-btn js-confirm" title="Cancel" data-method="PATCH" data-status="canceled"
      data-title="Cancel order?" data-text="Mark {{ $o->order_no }} as canceled." data-label="Cancel order"
      data-url="{{ route('admin.orders.status', $o) }}"><i class="bi bi-slash-circle"></i></button>
  @endif
  <button class="act-btn danger js-confirm" title="Delete" data-method="DELETE"
    data-title="Delete order?" data-text="{{ $o->order_no }} will be marked Deleted (the student's order history will show 'Deleted'). You can restore or permanently delete it later from the 'Deleted' filter." data-label="Delete"
    data-url="{{ route('admin.orders.destroy', $o) }}"><i class="bi bi-trash"></i></button>
@endif
