@extends('admin.layouts.app')

@section('title', 'Add Order')
@section('subtitle', 'Manual order — student + course + received payment.')

@section('content')
<section class="page-sec show" id="page-add-order">
  <div class="page-header">
    <div><h2>Add Order</h2><p>If no account exists for the student's email, a new account is created (Email + Password = email).</p></div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-accent"><i class="bi bi-arrow-left me-1"></i>Orders</a>
  </div>

  <form method="POST" action="{{ route('admin.orders.store') }}" id="orderForm">
    @csrf
    <div class="row g-3">
      <div class="col-lg-8">
        <div class="card mb-3">
          <div class="card-header"><div class="section-title">Course</div></div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-8"><label class="form-label">Select Course</label>
                <select class="form-select @error('course_id') is-invalid @enderror" name="course_id" id="orderCourse" onchange="fillFee(true)">
                  <option value="">Choose a course…</option>
                  @foreach ($courses as $c)
                    <option value="{{ $c->id }}" data-price="{{ (float) $c->price }}" data-duration="{{ $c->duration }}" @selected(old('course_id') == $c->id)>{{ $c->title }} — {{ money($c->price) }}</option>
                  @endforeach
                </select>
                @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="cell-sub mt-1" id="courseDuration"></div>
              </div>
              <div class="col-md-4"><label class="form-label">Order Status</label>
                <select class="form-select @error('order_status') is-invalid @enderror" name="order_status">
                  <option value="completed" @selected(old('order_status', 'completed') === 'completed')>Enrolled</option>
                  <option value="pending" @selected(old('order_status') === 'pending')>Pending</option>
                </select>
                @error('order_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div><div class="section-title">Student Information</div><div class="section-sub">For an existing student, select from the list — fields fill in automatically</div></div>
            <select class="form-select" style="max-width:280px" id="existingStudent" onchange="fillStudent()">
              <option value="">— Existing student (optional) —</option>
              @foreach ($students as $s)
                <option value="{{ $s->id }}" data-json="{{ json_encode($s->only(['name', 'email', 'phone', 'nid', 'parent_phone'])) }}">{{ $s->name }} — {{ $s->email }}</option>
              @endforeach
            </select>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">Name</label>
                <input name="name" id="f_name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Student full name">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Email <span class="fw-normal">(login email + password)</span></label>
                <input type="email" name="email" id="f_email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="student@email.com" oninput="showLogin()">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Phone Number</label>
                <input name="phone" id="f_phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="01XXXXXXXXX">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Parent Number</label>
                <input name="parent_phone" id="f_parent_phone" value="{{ old('parent_phone') }}" class="form-control @error('parent_phone') is-invalid @enderror" placeholder="01XXXXXXXXX">
                @error('parent_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12"><label class="form-label">NID / Birth Reg. Number</label>
                <input name="nid" id="f_nid" value="{{ old('nid') }}" class="form-control @error('nid') is-invalid @enderror" placeholder="NID number">
                @error('nid')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><div class="section-title">Payment</div><div class="section-sub">The Payment Received amount is added to the student's payment history</div></div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-4"><label class="form-label">Course Price ({{ currency_symbol() }})</label>
                <input type="number" step="0.01" min="0" name="fee" id="orderFee" value="{{ old('fee') }}" class="form-control @error('fee') is-invalid @enderror" oninput="calcDue()">
                @error('fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4"><label class="form-label">Payment Received ({{ currency_symbol() }})</label>
                <input type="number" step="0.01" min="0" name="received" id="orderReceived" value="{{ old('received', 0) }}" class="form-control @error('received') is-invalid @enderror" oninput="calcDue()">
                @error('received')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="receivedErr">Cannot be more than the course price.</div>
              </div>
              <div class="col-md-4"><label class="form-label">Due ({{ currency_symbol() }})</label>
                <input type="text" id="orderDue" class="form-control" value="0" disabled>
              </div>
              <div class="col-md-4"><label class="form-label">Payment Method</label>
                <select class="form-select" name="method">
                  @foreach (\App\Models\Payment::METHODS as $m)
                    <option @selected(old('method', 'Cash') === $m)>{{ $m }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4"><label class="form-label">Account No. <span class="fw-normal">(optional)</span></label>
                <input name="account_no" value="{{ old('account_no') }}" class="form-control" placeholder="01XXXXXXXXX">
              </div>
              <div class="col-md-4"><label class="form-label">Order / Payment Date</label>
                <input type="date" name="order_date" value="{{ old('order_date', now()->toDateString()) }}" class="form-control @error('order_date') is-invalid @enderror">
                @error('order_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12"><label class="form-label">Note</label>
                <input name="note" value="{{ old('note') }}" class="form-control" placeholder="e.g. 1st, 2nd, 5th installment, Admission Fee">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card" style="position:sticky;top:84px">
          <div class="card-header"><div class="section-title">Summary</div></div>
          <div class="card-body">
            <div class="meta-row"><span>Course Price</span><span id="sumFee">{{ money(0) }}</span></div>
            <div class="meta-row"><span>Received</span><span id="sumPaid" style="color:var(--ok)">{{ money(0) }}</span></div>
            <div class="meta-row"><span>Due</span><span id="sumDue" style="color:var(--warn)">{{ money(0) }}</span></div>
            <div class="flash mt-3 mb-0" style="background:var(--accent-soft);color:var(--accent-ink);align-items:flex-start">
              <i class="bi bi-person-lock mt-1"></i>
              <div class="small">Login for a new student:<br>Email: <b id="loginEmail">—</b><br>Password: <b id="loginPass">—</b></div>
            </div>
            <button type="submit" class="btn btn-accent w-100 mt-3" id="orderSubmit"><i class="bi bi-check2 me-1"></i>Create Order</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-accent w-100 mt-2">Cancel</a>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
@endsection

@push('scripts')
<script>
function fillFee(force){
  const o = document.getElementById('orderCourse').selectedOptions[0];
  const fee = document.getElementById('orderFee');
  if(o && o.value){
    if(force || !fee.value) fee.value = o.dataset.price;
    document.getElementById('courseDuration').textContent = o.dataset.duration ? 'Duration: ' + o.dataset.duration : '';
  } else {
    document.getElementById('courseDuration').textContent = '';
  }
  calcDue();
}
function calcDue(){
  const fee  = Number(document.getElementById('orderFee').value || 0);
  const recI = document.getElementById('orderReceived');
  const rec  = Number(recI.value || 0);
  const bad  = rec > fee;
  recI.classList.toggle('is-invalid', bad);
  document.getElementById('receivedErr').style.display = bad ? 'block' : '';
  document.getElementById('orderSubmit').disabled = bad;
  const due = Math.max(0, fee - rec);
  document.getElementById('orderDue').value = due.toLocaleString('en-US');
  document.getElementById('sumFee').textContent  = money(fee);
  document.getElementById('sumPaid').textContent = money(Math.min(rec, fee));
  document.getElementById('sumDue').textContent  = money(due);
}
function fillStudent(){
  const o = document.getElementById('existingStudent').selectedOptions[0];
  if(!o || !o.value) return;
  const s = JSON.parse(o.dataset.json);
  ['name','email','phone','nid','parent_phone'].forEach(k => { document.getElementById('f_'+k).value = s[k] || ''; });
  showLogin();
}
function showLogin(){
  const e = document.getElementById('f_email').value.trim().toLowerCase();
  document.getElementById('loginEmail').textContent = e || '—';
  document.getElementById('loginPass').textContent  = e || '—';
}
fillFee(false); showLogin();
</script>
@endpush
