@extends('admin.layouts.app')

@section('title', 'Costing')
@section('subtitle', 'Institute expenses used in profit reports.')

@section('content')
<section class="page-sec show" id="page-costing">
  <div class="page-header">
    <div><h2>Costing</h2><p>Rent, salary, marketing and other institute costs.</p></div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card stat-card">
        <div class="stat-top"><div class="stat-ic" style="background:#FCEEDD;color:#B8650F"><i class="bi bi-receipt"></i></div></div>
        <div><div class="stat-num">{{ money_lakh($monthSum) }}</div><div class="stat-label">This Month</div></div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card stat-card">
        <div class="stat-top"><div class="stat-ic" style="background:#FBE7EB;color:#C5304A"><i class="bi bi-wallet2"></i></div></div>
        <div><div class="stat-num">{{ money_lakh($totalSum) }}</div><div class="stat-label">Total Costing</div></div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header"><div class="section-title">Add Costing</div></div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.costing.store') }}">
            @csrf
            <label class="form-label">Title</label>
            <input class="form-control mb-1 @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="e.g. Office Rent">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <label class="form-label mt-2">Amount ({{ currency_symbol() }})</label>
            <input type="number" step="0.01" class="form-control mb-1 @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" placeholder="15000">
            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <label class="form-label mt-2">Course <span class="fw-normal">(optional)</span></label>
            <select class="form-select mb-1" name="course_id">
              <option value="">General (all courses)</option>
              @foreach ($courses as $c)
                <option value="{{ $c->id }}" @selected(old('course_id') == $c->id)>{{ $c->title }}</option>
              @endforeach
            </select>
            <label class="form-label mt-2">Date</label>
            <input type="date" class="form-control @error('spent_at') is-invalid @enderror" name="spent_at" value="{{ old('spent_at', now()->toDateString()) }}">
            @error('spent_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <button type="submit" class="btn btn-accent w-100 mt-4"><i class="bi bi-plus-lg me-1"></i>Add Costing</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <form class="filter-bar mb-3" method="GET" action="{{ route('admin.costing.index') }}">
        <div class="row g-2 align-items-end">
          <div class="col-md-4"><label class="form-label">Search</label><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Title"></div>
          <div class="col-md-3"><label class="form-label">Course</label>
            <select class="form-select" name="course">
              <option value="">All</option>
              @foreach ($courses as $c)
                <option value="{{ $c->id }}" @selected(request('course') == $c->id)>{{ $c->title }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2"><label class="form-label">Date</label><input type="date" class="form-control" name="date" value="{{ request('date') }}"></div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search"></i></button>
            <a href="{{ route('admin.costing.index') }}" class="btn btn-outline-accent flex-fill">Reset</a>
          </div>
        </div>
      </form>
      <div class="card table-card">
        <div class="table-wrap">
          <table class="dtable" style="min-width:560px">
            <thead><tr><th>Title</th><th>Course</th><th>Amount</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
              @forelse ($expenses as $x)
                <tr>
                  <td class="cell-name">{{ $x->title }}</td>
                  <td>{{ $x->course?->title ?? 'General' }}</td>
                  <td>{{ money($x->amount) }}</td>
                  <td class="cell-sub">{{ $x->spent_at?->format('Y-m-d') }}</td>
                  <td><button class="act-btn danger js-confirm" title="Delete" data-type="costing" data-name="{{ $x->title }}" data-url="{{ route('admin.costing.destroy', $x) }}"><i class="bi bi-trash"></i></button></td>
                </tr>
              @empty
                @include('admin.partials.empty-row', ['cols' => 5, 'text' => 'No costing added yet.'])
              @endforelse
            </tbody>
          </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $expenses])
      </div>
    </div>
  </div>
</section>
@endsection
