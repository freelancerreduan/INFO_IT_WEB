@extends('layouts.user')
@section('title', 'Add Payment')
@section('subtitle', 'Select a course, check the due and submit your payment.')

@section('content')
<section class="page-sec show">
  <div class="page-header">
    <div><h2>Add Payment</h2><p>A submitted payment stays <b>Pending</b>. The course due goes down once the admin approves it.</p></div>
    <a href="{{ route('user.payments.index') }}" class="btn btn-outline-accent"><i class="bi bi-clock-history me-1"></i>Payment History</a>
  </div>

  @if ($courses->isEmpty())
    <div class="card"><div class="card-body">
      <div class="empty-state"><i class="bi bi-bag"></i><p class="mt-2 mb-2">You have no active course orders.</p><a href="{{ route('courses.index') }}" class="btn btn-sm btn-accent">Browse courses</a></div>
    </div></div>
  @else
  <div class="row g-3">
    {{-- ===== Step 1: course ===== --}}
    <div class="col-lg-5">
      <div class="card h-100">
        <div class="card-header"><div class="section-title">1. Select Course</div><div class="section-sub">Search by course name or order ID</div></div>
        <div class="card-body">
          <div class="position-relative mb-2">
            <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
            <input class="form-control" id="courseSearch" placeholder="Search course…" style="padding-left:34px" autocomplete="off">
          </div>
          <div class="pick-list" id="courseList"></div>
        </div>
      </div>
    </div>

    {{-- ===== Step 2: payment ===== --}}
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header"><div class="section-title">2. Payment Details</div></div>
        <div class="card-body">
          <form method="POST" action="{{ route('user.payments.store') }}" id="payForm">
            @csrf
            <input type="hidden" name="enrollment_id" id="orderId" value="{{ $selected }}">

            <div id="selectedCourse" class="d-flex align-items-center gap-2 mb-3 p-2 rounded" style="background:#FAFAFD;border:1px dashed var(--line)">
              <i class="bi bi-journal-bookmark fs-4" style="color:var(--muted)"></i><span class="cell-sub">No course selected</span>
            </div>
            @error('enrollment_id')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

            <div class="row g-2 mb-2">
              <div class="col-4"><div class="due-box"><div class="stat-label mt-0">Course Price</div><div class="v" id="boxFee">{{ money(0) }}</div></div></div>
              <div class="col-4"><div class="due-box" style="background:var(--ok-soft)"><div class="stat-label mt-0">Paid</div><div class="v" style="color:var(--ok)" id="boxPaid">{{ money(0) }}</div></div></div>
              <div class="col-4"><div class="due-box" style="background:var(--warn-soft)"><div class="stat-label mt-0">Due</div><div class="v" style="color:var(--warn)" id="boxDue">{{ money(0) }}</div></div></div>
            </div>
            <div class="cell-sub mb-3" id="pendingNote"></div>

            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">Amount ({{ currency_symbol() }})</label>
                <input type="number" step="0.01" min="1" name="amount" id="amountInput" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" placeholder="0" disabled>
                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="amountErr"></div>
                <div class="cell-sub mt-1" id="maxText"></div>
              </div>
              <div class="col-md-6"><label class="form-label">Payment Type</label>
                <select class="form-select @error('method') is-invalid @enderror" name="method">
                  @foreach (\App\Models\Payment::STUDENT_METHODS as $m)
                    <option @selected(old('method', 'bKash') === $m)>{{ $m }}</option>
                  @endforeach
                </select>
                @error('method')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Transaction Number</label>
                <input name="trx_id" value="{{ old('trx_id') }}" class="form-control @error('trx_id') is-invalid @enderror" placeholder="e.g. 8N7A6D5F4G" maxlength="60" required>
                @error('trx_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Sender Number <span class="fw-normal">(optional)</span></label>
                <input name="account_no" value="{{ old('account_no') }}" class="form-control @error('account_no') is-invalid @enderror" placeholder="01XXXXXXXXX" maxlength="30">
                @error('account_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12"><label class="form-label">Note <span class="fw-normal">(optional)</span></label>
                <input name="note" value="{{ old('note') }}" class="form-control" maxlength="300" placeholder="e.g. 1st, 2nd, 5th installment, Admission Fee">
              </div>
            </div>
            <button type="submit" class="btn btn-accent px-4 mt-4" id="payBtn" disabled><i class="bi bi-send me-1"></i>Submit Payment</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endif
</section>
@endsection

@push('scripts')
<script>
const COURSES  = @json($courses);
const PRESEL   = @json($selected);
const OLD_AMT  = @json(old('amount'));
const byId     = id => document.getElementById(id);
const courseEl = byId('courseList');

function renderCourses(q){
  if(!courseEl) return;
  q = (q || '').toLowerCase();
  const rows = COURSES.filter(c => !q || c.course.toLowerCase().includes(q) || String(c.order_no).toLowerCase().includes(q));
  if(!rows.length){ courseEl.innerHTML = '<div class="empty-state py-4"><i class="bi bi-search"></i><p class="mt-2 mb-0 small">No course found.</p></div>'; return; }
  const cur = byId('orderId').value;
  courseEl.innerHTML = rows.map(c => `
    <div class="pick-item ${String(c.id)===cur?'on':''}" data-id="${c.id}">
      <img src="${esc(c.thumb)}" alt="" style="border-radius:7px">
      <div class="flex-fill" style="min-width:0">
        <div class="cell-name text-truncate">${esc(c.course)}</div>
        <div class="cell-sub text-truncate">${esc(c.order_no)} · ${esc(c.status)}</div>
      </div>
      ${c.due > 0 ? `<span class="status-pill status-pending">Due ${money(c.due)}</span>` : '<span class="status-pill status-paid">Paid</span>'}
    </div>`).join('');
}

function selectCourse(id, keepAmount){
  const c = COURSES.find(x => String(x.id) === String(id));
  if(!c) return;
  byId('orderId').value = c.id;
  document.querySelectorAll('#courseList .pick-item').forEach(i => i.classList.toggle('on', i.dataset.id === String(c.id)));
  byId('selectedCourse').innerHTML = `
    <img src="${esc(c.thumb)}" style="width:46px;height:34px;border-radius:7px;object-fit:cover">
    <div><div class="cell-name">${esc(c.course)}</div><div class="cell-sub">${esc(c.order_no)} · ${esc(c.status)}</div></div>`;
  byId('boxFee').textContent  = money(c.fee);
  byId('boxPaid').textContent = money(c.paid);
  byId('boxDue').textContent  = money(c.due);
  byId('pendingNote').innerHTML = c.pending > 0
    ? `<i class="bi bi-hourglass-split me-1"></i><b>${money(c.pending)}</b> of payments for this course is waiting for approval.` : '';
  const amt = byId('amountInput');
  amt.disabled = c.payable <= 0;
  amt.max = c.payable;
  amt.value = keepAmount && OLD_AMT ? OLD_AMT : '';
  byId('maxText').innerHTML = c.payable > 0 ? `You can pay up to <b>${money(c.payable)}</b>.` : 'There is no due to pay for this course right now.';
  checkAmount();
}

function checkAmount(){
  const c   = COURSES.find(x => String(x.id) === String(byId('orderId').value));
  const amt = byId('amountInput');
  const v   = Number(amt.value || 0);
  let msg = '';
  if(c && v > c.payable) msg = 'Cannot be more than ' + money(c.payable) + '.';
  amt.classList.toggle('is-invalid', !!msg);
  byId('amountErr').textContent = msg; byId('amountErr').style.display = msg ? 'block' : '';
  if(c && v > 0 && !msg) byId('maxText').innerHTML = `Due after approval: <b>${money(Math.max(0, c.due - v))}</b>`;
  else if(c) byId('maxText').innerHTML = c.payable > 0 ? `You can pay up to <b>${money(c.payable)}</b>.` : 'There is no due to pay for this course right now.';
  byId('payBtn').disabled = !(c && v >= 1 && !msg);
}

if(courseEl){
  courseEl.addEventListener('click', e => { const it = e.target.closest('.pick-item'); if(it) selectCourse(it.dataset.id); });
  byId('courseSearch').addEventListener('input', function(){ renderCourses(this.value.trim()); });
  byId('amountInput').addEventListener('input', checkAmount);
  renderCourses('');
  const first = PRESEL || (COURSES.find(c => c.payable > 0) || {}).id;
  if(first) selectCourse(first, true);
}
</script>
@endpush
