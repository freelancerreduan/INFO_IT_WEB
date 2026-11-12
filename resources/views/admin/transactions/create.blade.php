@extends('admin.layouts.app')

@section('title', 'Add Transaction')
@section('subtitle', 'Record a payment against an order.')

@section('content')
<section class="page-sec show" id="page-add-transaction">
  <div class="page-header">
    <div><h2>Add Transaction</h2><p>Search and select a student, then select their course. The due amount will show, and you can enter up to the due amount.</p></div>
    <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-accent"><i class="bi bi-list-ul me-1"></i>All Transactions</a>
  </div>
  <div class="row g-3">
    {{-- ===== Step 1: student ===== --}}
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header"><div class="section-title">1. Select Student</div><div class="section-sub">Search by Student ID, name, email, phone or order ID</div></div>
        <div class="card-body">
          <div class="position-relative mb-2">
            <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
            <input class="form-control" id="studentSearch" placeholder="Search student…" style="padding-left:34px" autocomplete="off">
          </div>
          <div class="pick-list" id="studentList"></div>
        </div>
      </div>
    </div>

    {{-- ===== Step 2: course + payment info ===== --}}
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header"><div class="section-title">2. Payment Information</div></div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.transactions.store') }}">
            @csrf
            <input type="hidden" name="user_id" id="userId" value="{{ old('user_id', $selectedUser['id'] ?? '') }}">

            <div id="selectedStudent" class="d-flex align-items-center gap-2 mb-3 p-2 rounded" style="background:#FAFAFD;border:1px dashed var(--line)">
              <i class="bi bi-person-circle fs-4" style="color:var(--muted)"></i><span class="cell-sub">No student selected</span>
            </div>
            @error('user_id')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

            <div class="row g-3">
              <div class="col-12"><label class="form-label">Course (Order)</label>
                <select class="form-select @error('enrollment_id') is-invalid @enderror" name="enrollment_id" id="orderSelect" disabled>
                  <option value="">Select a student first…</option>
                </select>
                @error('enrollment_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="row g-2 my-3">
              <div class="col-4"><div class="due-box"><div class="stat-label mt-0">Course Price</div><div class="v" id="boxFee">{{ money(0) }}</div></div></div>
              <div class="col-4"><div class="due-box" style="background:var(--ok-soft)"><div class="stat-label mt-0">Paid</div><div class="v" style="color:var(--ok)" id="boxPaid">{{ money(0) }}</div></div></div>
              <div class="col-4"><div class="due-box" style="background:var(--warn-soft)"><div class="stat-label mt-0">Due</div><div class="v" style="color:var(--warn)" id="boxDue">{{ money(0) }}</div></div></div>
            </div>
            <div class="cell-sub mb-3" id="pendingNote"></div>

            <div class="row g-3">
              <div class="col-md-4"><label class="form-label">Amount ({{ currency_symbol() }})</label>
                <input type="number" step="0.01" min="1" name="amount" id="amountInput" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" placeholder="0" disabled>
                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="amountErr"></div>
                <div class="cell-sub mt-1" id="afterDue"></div>
              </div>
              <div class="col-md-4"><label class="form-label">Payment Method</label>
                <select class="form-select" name="method">
                  @foreach (\App\Models\Payment::METHODS as $m)
                    <option @selected(old('method', 'bKash') === $m)>{{ $m }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4"><label class="form-label">Status</label>
                <select class="form-select" name="status" id="statusSelect">
                  @foreach (\App\Models\Payment::STATUSES as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', 'paid') === $val)>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4"><label class="form-label">Account No. <span class="fw-normal">(last 4 digit)</span></label>
                <input name="account_no" value="{{ old('account_no') }}" class="form-control @error('account_no') is-invalid @enderror" placeholder="e.g. 6130" maxlength="60">
                @error('account_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4"><label class="form-label">Transaction ID <span class="fw-normal">(optional)</span></label>
                <input name="trx_id" value="{{ old('trx_id') }}" class="form-control @error('trx_id') is-invalid @enderror" placeholder="Auto generate">
                @error('trx_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4"><label class="form-label">Date</label>
                <input type="date" name="paid_at" value="{{ old('paid_at', now()->toDateString()) }}" class="form-control @error('paid_at') is-invalid @enderror">
                @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12"><label class="form-label">Note</label>
                <textarea name="note" rows="2" class="form-control" placeholder="e.g. 1st, 2nd, 5th installment, Admission Fee">{{ old('note') }}</textarea>
              </div>
            </div>
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-accent px-4" id="payBtn" disabled><i class="bi bi-check2 me-1"></i>Save Transaction</button>
              <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-accent px-4">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
const TX = {
  /* relative URL — http/https ba www mismatch e AJAX block hoy na */
  searchUrl: @json(route('admin.payments.students', [], false)),
  ordersUrl: @json(route('admin.payments.student-orders', ['user' => '__ID__'], false)),
  initial:   @json($students),
  selected:  @json($selectedUser),
  order:     @json($selectedOrder ? (string) $selectedOrder : null),
  oldAmount: @json(old('amount')),
};
let ORDERS = [];
const listEl = document.getElementById('studentList');
const byId = id => document.getElementById(id);

function renderStudents(rows){
  if(!rows.length){ listEl.innerHTML = '<div class="empty-state py-4"><i class="bi bi-person-x"></i><p class="mt-2 mb-0 small">No student found.</p></div>'; return; }
  const cur = byId('userId').value;
  listEl.innerHTML = rows.map(s => `
    <div class="pick-item ${String(s.id)===cur?'on':''}" data-id="${s.id}" data-json='${esc(JSON.stringify(s))}'>
      <img src="${esc(s.photo)}" alt="">
      <div class="flex-fill" style="min-width:0">
        <div class="cell-name text-truncate">${esc(s.name)}</div>
        <div class="cell-sub text-truncate">${s.sid ? '<b>'+esc(s.sid)+'</b> · ' : ''}${esc(s.email)}${s.phone ? ' · '+esc(s.phone) : ''}</div>
      </div>
      ${s.orders ? `<span class="status-pill status-pending">${s.orders} open</span>` : ''}
    </div>`).join('');
}
listEl.addEventListener('click', e=>{
  const it = e.target.closest('.pick-item'); if(!it) return;
  byId('amountInput').value = '';
  selectStudent(JSON.parse(it.dataset.json));
});

let _st;
byId('studentSearch').addEventListener('input', function(){
  clearTimeout(_st);
  const q = this.value.trim();
  _st = setTimeout(()=>{
    if(!q){ renderStudents(TX.initial); return; }
    listEl.innerHTML = '<div class="cell-sub p-3"><span class="spinner-border spinner-border-sm me-2"></span>Searching…</div>';
    fetchJSON(TX.searchUrl + '?q=' + encodeURIComponent(q))
      .then(renderStudents)
      .catch(err => { listEl.innerHTML = `<div class="empty-state py-4 text-danger"><i class="bi bi-exclamation-triangle"></i><p class="mt-2 mb-0 small">${esc(err.message)}</p></div>`; });
  }, 250);
});

function selectStudent(s, preOrder){
  byId('userId').value = s.id;
  document.querySelectorAll('.pick-item').forEach(i=>i.classList.toggle('on', i.dataset.id === String(s.id)));
  byId('selectedStudent').innerHTML = `
    <img src="${esc(s.photo)}" style="width:40px;height:40px;border-radius:50%;object-fit:cover">
    <div><div class="cell-name">${esc(s.name)}</div><div class="cell-sub">${s.sid ? '<b>'+esc(s.sid)+'</b> · ' : ''}${esc(s.email)}${s.phone ? ' · '+esc(s.phone) : ''}</div></div>`;
  const sel = byId('orderSelect');
  sel.disabled = true; sel.innerHTML = '<option value="">Loading…</option>';
  fetchJSON(TX.ordersUrl.replace('__ID__', s.id))
    .then(rows=>{
      ORDERS = rows;
      if(!rows.length){ sel.innerHTML = '<option value="">This student has no orders</option>'; showOrder(); return; }
      sel.innerHTML = '<option value="">Choose a course…</option>' + rows.map(o =>
        `<option value="${o.id}" ${o.open ? '' : 'disabled'}>${esc(o.course)} — ${esc(o.order_no)} · ${o.open ? (o.due > 0 ? 'Due '+money(o.due) : 'Full paid') : esc(o.status)}</option>`).join('');
      sel.disabled = false;
      const want = preOrder || (rows.find(o => o.open && o.due > 0) || {}).id;
      if(want && rows.some(o => String(o.id) === String(want) && o.open)) sel.value = want;
      showOrder();
    })
    .catch(err => { ORDERS = []; sel.innerHTML = `<option value="">${esc(err.message)}</option>`; showOrder(); });
}

function curOrder(){ return ORDERS.find(x => String(x.id) === String(byId('orderSelect').value)); }

function showOrder(){
  const o = curOrder();
  const amt = byId('amountInput');
  byId('boxFee').textContent  = money(o ? o.fee : 0);
  byId('boxPaid').textContent = money(o ? o.paid : 0);
  byId('boxDue').textContent  = money(o ? o.due : 0);
  byId('pendingNote').innerHTML = o && o.pending > 0 ? `<i class="bi bi-hourglass-split me-1"></i><b>${money(o.pending)}</b> of payments for this course is waiting for approval (Pending).` : '';
  amt.disabled = !(o && o.due > 0);
  amt.max = o ? o.due : '';
  if(o && o.due > 0 && !amt.value) amt.value = TX.oldAmount || '';
  if(!o || o.due <= 0) amt.value = '';
  checkAmount();
}
function checkAmount(){
  const o   = curOrder();
  const amt = byId('amountInput');
  const v   = Number(amt.value || 0);
  const err = byId('amountErr');
  const paidStatus = byId('statusSelect').value === 'paid';
  let msg = '';
  if(o && paidStatus && v > o.due) msg = 'Cannot be more than the due (' + money(o.due) + ').';
  amt.classList.toggle('is-invalid', !!msg);
  err.textContent = msg; err.style.display = msg ? 'block' : '';
  byId('afterDue').innerHTML = o && v > 0 && !msg && paidStatus ? `Due after this payment: <b>${money(Math.max(0, o.due - v))}</b>` : (o && o.due <= 0 ? 'This course is fully paid.' : '');
  byId('payBtn').disabled = !(o && v >= 1 && !msg);
}
byId('orderSelect').addEventListener('change', ()=>{ byId('amountInput').value=''; showOrder(); });
byId('amountInput').addEventListener('input', checkAmount);
byId('statusSelect').addEventListener('change', checkAmount);

renderStudents(TX.initial);
if(TX.selected) selectStudent(TX.selected, TX.order);
</script>
@endpush
