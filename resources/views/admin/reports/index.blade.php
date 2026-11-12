@extends('admin.layouts.app')

@section('title', 'Reports')
@section('subtitle', 'Performance & financial reports.')

@section('content')
<section class="page-sec show" id="page-reports">
  <div class="page-header"><div><h2>Reports</h2><p>Financial and enrollment performance reports.</p></div></div>

  <form class="filter-bar mb-3" method="GET" action="{{ route('admin.reports.index') }}" id="reportFilter">
    <div class="row g-2 align-items-end">
      <div class="col-md-2"><label class="form-label">Report Type</label>
        <select class="form-select" name="type">
          @foreach (['Monthly', 'Yearly'] as $t)
            <option @selected($filters['type'] === $t)>{{ $t }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Month</label>
        <select class="form-select" name="month">
          <option value="">All months</option>
          @foreach ($months as $num => $name)
            <option value="{{ $num }}" @selected($filters['month'] === $num)>{{ $name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Year</label>
        <select class="form-select" name="year">
          @foreach ($years as $y)
            <option @selected($filters['year'] === $y)>{{ $y }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Course</label>
        <select class="form-select" id="reportCourseFilter" name="course_id">
          <option value="">All courses</option>
          @foreach ($courses as $c)
            <option value="{{ $c->id }}" @selected($filters['course_id'] == $c->id)>{{ $c->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Payment Type</label>
        <select class="form-select" name="method">
          <option>All</option>
          @foreach (\App\Models\Payment::METHODS as $m)
            <option @selected($filters['method'] === $m)>{{ $m }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Status</label>
        <select class="form-select" name="status">
          @foreach (['All', 'Completed', 'Pending'] as $st)
            <option @selected($filters['status'] === $st)>{{ $st }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="row g-2 align-items-end mt-1">
      <div class="col-md-4"><label class="form-label">Date Range</label>
        <div class="d-flex gap-2"><input type="date" class="form-control" name="from" value="{{ $filters['from'] }}"><input type="date" class="form-control" name="to" value="{{ $filters['to'] }}"></div>
      </div>
      <div class="col-md-8 d-flex gap-2 justify-content-md-end">
        <button type="submit" class="btn btn-accent"><i class="bi bi-funnel me-1"></i>Filter</button>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-accent">Reset</a>
        <a href="{{ route('admin.reports.export', request()->query()) }}" class="btn btn-outline-accent"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</a>
      </div>
    </div>
  </form>

  <div class="row g-3" id="reportStatCards">
    @foreach ($cards as $s)
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
          <div class="stat-top"><div class="stat-ic" style="background:{{ $s['soft'] }};color:{{ $s['color'] }}"><i class="bi {{ $s['icon'] }}"></i></div></div>
          <div><div class="stat-num">{{ $s['value'] }}</div><div class="stat-label">{{ $s['label'] }}</div></div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="row g-3 mt-1">
    <div class="col-lg-6">
      <div class="card"><div class="card-header"><div class="section-title">Revenue</div></div>
        <div class="card-body"><div class="chart-box sm"><canvas id="reportRevenueChart"></canvas></div></div></div>
    </div>
    <div class="col-lg-6">
      <div class="card"><div class="card-header"><div class="section-title">Costing</div></div>
        <div class="card-body"><div class="chart-box sm"><canvas id="reportCostingChart"></canvas></div></div></div>
    </div>
  </div>
  <div class="row g-3 mt-1">
    <div class="col-lg-6">
      <div class="card"><div class="card-header"><div class="section-title">Student Enrollment</div></div>
        <div class="card-body"><div class="chart-box sm"><canvas id="reportStudentChart"></canvas></div></div></div>
    </div>
    <div class="col-lg-6">
      <div class="card table-card">
        <div class="card-header"><div class="section-title">Detailed Report</div></div>
        <div class="table-wrap">
          <table class="dtable">
            <thead><tr><th>{{ $filters['type'] === 'Yearly' ? 'Year' : 'Month' }}</th><th>Earnings</th><th>Costing</th><th>Profit</th><th>Students</th></tr></thead>
            <tbody id="reportTableBody">
              @forelse ($rows as $r)
                <tr><td>{{ $r['label'] }}</td><td>{{ money($r['earnings']) }}</td><td>{{ money($r['costing']) }}</td><td>{{ money($r['profit']) }}</td><td>{{ $r['students'] }}</td></tr>
              @empty
                @include('admin.partials.empty-row', ['cols' => 5, 'text' => 'No data for the selected filters.'])
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
const REPORT = @json($chart);
const barOpts = { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{grid:{color:'#F0F0F5'}}, x:{grid:{display:false}} } };

new Chart(document.getElementById('reportRevenueChart'), {
  type:'bar',
  data:{ labels: REPORT.labels, datasets:[{ data:REPORT.earnings, backgroundColor:'#4C3AE3', borderRadius:6, maxBarThickness:26 }] },
  options: barOpts
});
new Chart(document.getElementById('reportCostingChart'), {
  type:'bar',
  data:{ labels: REPORT.labels, datasets:[{ data:REPORT.costing, backgroundColor:'#B8650F', borderRadius:6, maxBarThickness:26 }] },
  options: barOpts
});
new Chart(document.getElementById('reportStudentChart'), {
  type:'line',
  data:{ labels: REPORT.labels, datasets:[{ data:REPORT.students, borderColor:'#178A4C', backgroundColor:'rgba(23,138,76,.08)', fill:true, tension:.35, pointRadius:3, borderWidth:2.5 }] },
  options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{grid:{color:'#F0F0F5'}, ticks:{precision:0}}, x:{grid:{display:false}} } }
});
</script>
@endpush
