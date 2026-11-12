@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Overview of your training institute.')

@section('content')
<section class="page-sec show" id="page-dashboard">
  <div class="page-header">
    <div>
      <h2>Dashboard</h2>
      <p>Overview of your training institute.</p>
    </div>
  </div>

  <div class="row g-3" id="statCards">
    @foreach ($stats as $s)
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
          <div class="stat-top">
            <div class="stat-ic" style="background:{{ $s['soft'] }};color:{{ $s['color'] }}"><i class="bi {{ $s['icon'] }}"></i></div>
            <span class="stat-change {{ $s['up'] ? 'up' : 'down' }}"><i class="bi bi-arrow-{{ $s['up'] ? 'up' : 'down' }}-short"></i>{{ $s['change'] }}</span>
          </div>
          <div>
            <div class="stat-num">{{ $s['value'] }}</div>
            <div class="stat-label">{{ $s['label'] }}</div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="row g-3 mt-1">
    <div class="col-xl-8">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div>
            <div class="section-title">Financial Overview</div>
            <div class="section-sub">Earnings, costing &amp; profit</div>
          </div>
          <div class="seg" id="finSeg">
            <button class="active" data-range="monthly">Monthly</button>
            <button data-range="yearly">Yearly</button>
          </div>
        </div>
        <div class="card-body"><div class="chart-box"><canvas id="financeChart"></canvas></div></div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card h-100">
        <div class="card-header">
          <div class="section-title">Student Growth</div>
          <div class="section-sub">New enrollments per month</div>
        </div>
        <div class="card-body"><div class="chart-box"><canvas id="growthChart"></canvas></div></div>
      </div>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-xl-7">
      <div class="card table-card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div>
            <div class="section-title">Recent Orders</div>
            <div class="section-sub">Latest course orders</div>
          </div>
          <a href="{{ route('admin.orders.index') }}" class="small fw-semibold" style="color:var(--accent)">View all</a>
        </div>
        <div class="table-wrap">
          <table class="dtable">
            <thead><tr><th>Student</th><th>Course</th><th>Amount</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody id="recentOrdersBody">
              @forelse ($recentOrders as $o)
                <tr>
                  <td class="cell-name">{{ $o->user?->name ?? '—' }}</td>
                  <td>{{ $o->course?->title ?? '—' }}</td>
                  <td>{{ money($o->fee) }}</td>
                  <td>{{ status_pill($o->order_status_label) }}</td>
                  <td class="cell-sub">{{ $o->enrolled_at?->format('Y-m-d') }}</td>
                  <td>
                    <button class="act-btn" title="View" data-view="details" data-payload="{{ json_encode([
                      'title' => 'Order ' . $o->order_no,
                      'rows' => [
                        ['Order ID', $o->order_no], ['Student', $o->user?->name ?? '—'], ['Course', $o->course?->title ?? '—'],
                        ['Price', money($o->fee)], ['Paid', money($o->paid_sum ?? 0)], ['Payment', $o->payment_label],
                        ['Date', $o->enrolled_at?->format('Y-m-d')],
                      ],
                      'status' => $o->order_status_label,
                    ]) }}"><i class="bi bi-eye"></i></button>
                  </td>
                </tr>
              @empty
                @include('admin.partials.empty-row', ['cols' => 6, 'text' => 'No orders yet.'])
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-5">
      <div class="card table-card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div>
            <div class="section-title">Recent Transactions</div>
            <div class="section-sub">Latest payments</div>
          </div>
          <a href="{{ route('admin.transactions.index') }}" class="small fw-semibold" style="color:var(--accent)">View all</a>
        </div>
        <div class="table-wrap">
          <table class="dtable">
            <thead><tr><th>ID</th><th>Method</th><th>Student</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
            <tbody id="recentTxBody">
              @forelse ($recentTransactions as $t)
                <tr>
                  <td class="cell-sub">{{ $t->trx_id }}</td>
                  <td>{{ $t->method }}</td>
                  <td class="cell-name">{{ $t->user?->name ?? '—' }}</td>
                  <td>{{ money($t->amount) }}</td>
                  <td>{{ status_pill($t->status_label) }}</td>
                  <td class="cell-sub">{{ $t->paid_at?->format('Y-m-d') }}</td>
                </tr>
              @empty
                @include('admin.partials.empty-row', ['cols' => 6, 'text' => 'No transactions yet.'])
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
const FIN = {
  monthly: { labels: @json($monthly['labels']), earnings: @json($monthly['earnings']), costing: @json($monthly['costing']), profit: @json($monthly['profit']) },
  yearly:  { labels: @json($yearly['labels']),  earnings: @json($yearly['earnings']),  costing: @json($yearly['costing']),  profit: @json($yearly['profit']) },
};

let financeChart;
function renderFinanceChart(range){
  const set = FIN[range];
  if(financeChart) financeChart.destroy();
  financeChart = new Chart(document.getElementById('financeChart'), {
    type:'line',
    data:{ labels:set.labels, datasets:[
      {label:'Earnings', data:set.earnings, borderColor:'#4C3AE3', backgroundColor:'rgba(76,58,227,.08)', fill:true, tension:.35, pointRadius:0, borderWidth:2.5},
      {label:'Costing', data:set.costing, borderColor:'#B8650F', backgroundColor:'rgba(184,101,15,.06)', fill:true, tension:.35, pointRadius:0, borderWidth:2.5},
      {label:'Profit', data:set.profit, borderColor:'#178A4C', backgroundColor:'rgba(23,138,76,.06)', fill:true, tension:.35, pointRadius:0, borderWidth:2.5},
    ]},
    options:{
      responsive:true, maintainAspectRatio:false,
      plugins:{legend:{position:'bottom', labels:{usePointStyle:true, boxWidth:7, padding:16, font:{size:11.5}}}},
      scales:{ y:{grid:{color:'#F0F0F5'}, ticks:{callback:v=>CURRENCY+(v/1000)+'k'}}, x:{grid:{display:false}} }
    }
  });
}
renderFinanceChart('monthly');
document.querySelectorAll('#finSeg button').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    document.querySelectorAll('#finSeg button').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    renderFinanceChart(btn.dataset.range);
  });
});

new Chart(document.getElementById('growthChart'), {
  type:'bar',
  data:{ labels: @json($monthly['labels']), datasets:[{ data:@json($monthly['students']), backgroundColor:'#4C3AE3', borderRadius:6, maxBarThickness:16 }] },
  options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{grid:{color:'#F0F0F5'}, ticks:{precision:0}}, x:{grid:{display:false}} } }
});
</script>
@endpush
