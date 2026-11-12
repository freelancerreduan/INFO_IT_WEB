@extends('admin.layouts.app')

@section('title', 'Messages')
@section('subtitle', 'Messages sent from the website contact form.')

@section('content')
<section class="page-sec show" id="page-messages">
  <div class="page-header">
    <div><h2>Messages</h2><p>All messages from the website contact form (home page + contact page).</p></div>
    <a href="{{ route('admin.messages.export', request()->query()) }}" class="btn btn-outline-accent"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</a>
  </div>

  <div class="row g-3 mb-1">
    @foreach ([
      ['label' => 'Total Messages', 'value' => $counts['all'],     'icon' => 'bi-chat-left-text-fill', 'color' => '#4C3AE3', 'soft' => '#EEEBFC', 'status' => ''],
      ['label' => 'New',            'value' => $counts['new'],     'icon' => 'bi-envelope-fill',       'color' => '#B8650F', 'soft' => '#FCEEDD', 'status' => 'new'],
      ['label' => 'Read',           'value' => $counts['read'],    'icon' => 'bi-envelope-open-fill',  'color' => '#0F8B8D', 'soft' => '#E1F5F5', 'status' => 'read'],
      ['label' => 'Replied',        'value' => $counts['replied'], 'icon' => 'bi-reply-fill',          'color' => '#178A4C', 'soft' => '#E3F7EC', 'status' => 'replied'],
    ] as $c)
      <div class="col-12 col-sm-6 col-xl-3">
        <a class="text-decoration-none" href="{{ route('admin.messages.index', array_filter(['status' => $c['status']])) }}">
          <div class="card stat-card">
            <div class="stat-top"><div class="stat-ic" style="background:{{ $c['soft'] }};color:{{ $c['color'] }}"><i class="bi {{ $c['icon'] }}"></i></div></div>
            <div><div class="stat-num">{{ $c['value'] }}</div><div class="stat-label">{{ $c['label'] }}</div></div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  <form class="filter-bar my-3" method="GET" action="{{ route('admin.messages.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-4"><label class="form-label">Search</label><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Name, phone, email or message"></div>
      <div class="col-md-2"><label class="form-label">Status</label>
        <select class="form-select" name="status">
          <option value="">All</option>
          @foreach (\App\Models\ContactMessage::STATUSES as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Date</label><input type="date" class="form-control" name="date" value="{{ request('date') }}"></div>
      <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search me-1"></i>Search</button>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-accent flex-fill">Reset</a>
      </div>
    </div>
  </form>

  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>Name</th><th>Phone</th><th>Email</th><th>Message</th><th>From</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse ($messages as $m)
            <tr>
              <td class="cell-name">{{ $m->name }}</td>
              <td class="cell-sub">{{ $m->phone }}</td>
              <td class="cell-sub">{{ $m->email }}</td>
              <td style="max-width:320px">{{ Str::limit($m->message, 70) }}</td>
              <td class="cell-sub">{{ $m->source_label }}</td>
              <td>{{ status_pill($m->status_label) }}</td>
              <td class="text-nowrap" title="{{ local_time($m->created_at)?->format('d M Y, h:i A') }}">
                <div>{{ human_time($m->created_at) }}</div>
                <div class="cell-sub small">{{ local_time($m->created_at)?->format('d M Y, h:i A') }}</div>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <button class="act-btn" title="View" data-view="details" data-payload="{{ json_encode([
                    'title' => 'Message — ' . $m->name,
                    'rows' => [['Name', $m->name], ['Phone', $m->phone], ['Email', $m->email],
                               ['From', $m->source_label], ['Date', human_time($m->created_at) . ' (' . local_time($m->created_at)?->format('d M Y, h:i A') . ')'], ['Message', $m->message]],
                    'status' => $m->status_label,
                  ]) }}"><i class="bi bi-eye"></i></button>
                  @if ($m->email)
                  {{-- mailto: computer e mail app na thakle kaj kore na — tai Gmail compose notun tab e khule --}}
                  <a class="act-btn" title="Reply by email (Gmail)" target="_blank" rel="noopener"
                    href="https://mail.google.com/mail/?view=cm&fs=1&to={{ rawurlencode($m->email) }}&su={{ rawurlencode('Re: আপনার বার্তা — ' . setting('institute_name')) }}"><i class="bi bi-envelope"></i></a>
                  @endif
                  <a class="act-btn" title="Call" href="tel:{{ $m->phone }}"><i class="bi bi-telephone"></i></a>
                  <button class="act-btn js-confirm" title="Mark read" data-method="PATCH" data-status="read"
                    data-title="Mark as read?" data-text="{{ $m->name }}'s message will be marked as read." data-label="Mark read"
                    data-url="{{ route('admin.messages.update', $m) }}"><i class="bi bi-envelope-open"></i></button>
                  <button class="act-btn ok js-confirm" title="Mark replied" data-method="PATCH" data-status="replied"
                    data-title="Mark as replied?" data-text="{{ $m->name }}'s message will be marked as replied." data-label="Mark replied"
                    data-url="{{ route('admin.messages.update', $m) }}"><i class="bi bi-reply"></i></button>
                  <button class="act-btn danger js-confirm" title="Delete" data-type="message" data-name="{{ $m->name }}"
                    data-url="{{ route('admin.messages.destroy', $m) }}"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
          @empty
            @include('admin.partials.empty-row', ['cols' => 8, 'text' => 'No messages yet.'])
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $messages])
  </div>
</section>
@endsection
