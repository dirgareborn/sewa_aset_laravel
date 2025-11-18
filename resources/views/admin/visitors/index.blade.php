@extends('admin.layout.app')

@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card clearfix">
              <div class="card-header clearfix">
                <h3 class="card-title">Daftar Pengunjung Website</h3>
              </div>
    <div class="card-body">

    <p>Total Visitors: {{ $totalVisitors }}</p>
    <p>Today: {{ $todayVisitors }}</p>

    <canvas id="visitorChart" height="100"></canvas>

    <h4 class="mt-4">Visitors by City</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>City</th>
                <th>Total Visitors</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitorsByCity as $city)
            <tr>
                <td>{{ $city->city ?? '-' }}</td>
                <td>{{ $city->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('visitorChart').getContext('2d');
const visitorChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json(array_column($visitorsChart, 'date')),
        datasets: [{
            label: 'Visitors Last 7 Days',
            data: @json(array_column($visitorsChart, 'count')),
            backgroundColor: 'rgba(0, 191, 142, 0.2)',
            borderColor: 'rgba(0, 31, 77, 1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                stepSize: 1
            }
        }
    }
});
</script>
@endpush
