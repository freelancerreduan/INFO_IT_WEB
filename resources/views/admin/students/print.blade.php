<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student List · {{ setting('institute_name') }}</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: 'Segoe UI', Arial, sans-serif; color: #1F2133; margin: 0; padding: 24px; font-size: 13px; background: #fff; }
  .head { display: flex; align-items: center; gap: 14px; border-bottom: 2px solid #1F2133; padding-bottom: 12px; margin-bottom: 14px; }
  .head img { height: 56px; width: auto; }
  .head h1 { font-size: 20px; margin: 0; }
  .head p { margin: 2px 0 0; color: #6B7089; }
  .meta { display: flex; flex-wrap: wrap; gap: 6px 22px; margin-bottom: 12px; color: #444; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: 1px solid #C9CBD6; padding: 6px 8px; text-align: left; vertical-align: top; }
  th { background: #F1F1F6; font-weight: 600; }
  td.n { text-align: center; width: 40px; }
  .toolbar { margin-bottom: 16px; display: flex; gap: 8px; }
  .toolbar button { padding: 8px 16px; border: 1px solid #4C3AE3; background: #4C3AE3; color: #fff; border-radius: 6px; cursor: pointer; font-size: 14px; }
  .toolbar button.alt { background: #fff; color: #4C3AE3; }
  .foot { margin-top: 14px; color: #6B7089; font-size: 11px; }
  @media print {
    body { padding: 0; }
    .toolbar { display: none; }
    tr { page-break-inside: avoid; }
    thead { display: table-header-group; }
  }
  @page { size: A4; margin: 12mm; }
</style>
</head>
<body>
  <div class="toolbar">
    <button onclick="window.print()">Print</button>
    <button class="alt" onclick="window.close()">Close</button>
  </div>

  <div class="head">
    <img src="{{ site_logo() }}" alt="">
    <div>
      <h1>{{ setting('institute_name') }}</h1>
      <p>Student List</p>
    </div>
  </div>

  <div class="meta">
    <span><b>Course:</b> {{ $course?->title ?? 'All courses' }}</span>
    @if (request('status'))<span><b>Status:</b> {{ \App\Models\User::STUDENT_STATUSES[request('status')] ?? request('status') }}</span>@endif
    @if (request('q'))<span><b>Search:</b> {{ request('q') }}</span>@endif
    @if (request('date'))<span><b>Date:</b> {{ request('date') }}</span>@endif
    <span><b>Total:</b> {{ $students->count() }} students</span>
    <span><b>Printed:</b> {{ now('Asia/Dhaka')->format('d M Y, h:i A') }}</span>
  </div>

  <table>
    <thead>
      <tr><th>SL</th><th>Student ID</th><th>Name</th><th>Phone</th><th>Course</th><th>Enrolled</th><th>Status</th></tr>
    </thead>
    <tbody>
      @forelse ($students as $i => $s)
        @php
          $enrolled = $s->first_enrolled_at ? \Illuminate\Support\Carbon::parse($s->first_enrolled_at)->format('d M Y') : $s->created_at?->format('d M Y');
          $courseName = $course?->title ?? $s->latestEnrollment?->course?->title ?? '—';
        @endphp
        <tr>
          <td class="n">{{ $i + 1 }}</td>
          <td>{{ $s->student_id ?: '—' }}</td>
          <td>{{ $s->name }}</td>
          <td>{{ $s->phone ?: '—' }}</td>
          <td>{{ $courseName }}</td>
          <td>{{ $enrolled }}</td>
          <td>{{ $s->status_label }}</td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center;padding:20px">No students match your filters.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="foot">© {{ date('Y') }} {{ setting('institute_name') }}</div>

  <script>window.addEventListener('load', () => setTimeout(() => window.print(), 300));</script>
</body>
</html>
