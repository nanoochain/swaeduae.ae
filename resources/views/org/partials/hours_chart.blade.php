<div class="card shadow-sm mt-3">
  <div class="card-header"><strong>{{ __('Hours by Event (90 days)') }}</strong></div>
  <div class="card-body">
    <canvas id="hoursByEvent" height="120"></canvas>
    @php $labels = $hoursChart['labels'] ?? []; $data = $hoursChart['data'] ?? []; @endphp
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" integrity="sha384-GO" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const ctx = document.getElementById('hoursByEvent');
  if (!ctx) return;
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: @json($labels),
      datasets: [{ label: '{{ __("Hours") }}', data: @json($data) }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });
});
</script>
