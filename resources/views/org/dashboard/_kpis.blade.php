@php
    $orgDashMetrics = $orgDashMetrics ?? [
        'total_minutes' => 0, 'attendance_total' => 0, 'present' => 0,
        'no_show' => 0, 'present_rate' => 0, 'unfinalized' => 0
    ];
    $orgDashFrom = $orgDashFrom ?? now()->subDays(29)->toDateString();
    $orgDashTo   = $orgDashTo ?? now()->toDateString();
    $mins = (int)($orgDashMetrics['total_minutes'] ?? 0);
    $hours = intdiv($mins, 60); $rem = $mins % 60;
@endphp

<div class="row g-3 mb-3">
  <div class="col-12">
    <form method="GET" class="d-flex flex-wrap align-items-end gap-2">
      <div>
        <label class="form-label small mb-1">{{ __('From') }}</label>
        <input type="date" name="from" value="{{ $orgDashFrom }}" class="form-control form-control-sm" />
      </div>
      <div>
        <label class="form-label small mb-1">{{ __('To') }}</label>
        <input type="date" name="to" value="{{ $orgDashTo }}" class="form-control form-control-sm" />
      </div>
      <div class="ms-2">
        <button class="btn btn-sm btn-primary">{{ __('Apply') }}</button>
        <a href="{{ request()->url() }}?range=7d" class="btn btn-sm btn-outline-secondary">{{ __('Last 7d') }}</a>
        <a href="{{ request()->url() }}?range=30d" class="btn btn-sm btn-outline-secondary">{{ __('Last 30d') }}</a>
      </div>
    </form>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm"><div class="card-body">
      <div class="text-uppercase text-muted small">{{ __('Total Hours (range)') }}</div>
      <div class="h4 mb-0">{{ $hours }}h {{ $rem }}m</div>
      <small class="text-muted">{{ $orgDashFrom }} → {{ $orgDashTo }}</small>
    </div></div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm"><div class="card-body">
      <div class="text-uppercase text-muted small">{{ __('Attendance (range)') }}</div>
      <div class="h4 mb-0">{{ $orgDashMetrics['attendance_total'] }}</div>
      <small class="text-muted">{{ __('Present') }}: {{ $orgDashMetrics['present'] }} • {{ __('No-show') }}: {{ $orgDashMetrics['no_show'] }}</small>
    </div></div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm"><div class="card-body">
      <div class="text-uppercase text-muted small">{{ __('Present Rate') }}</div>
      <div class="h4 mb-0">{{ $orgDashMetrics['present_rate'] }}%</div>
      <small class="text-muted">{{ __('Of all attendance in range') }}</small>
    </div></div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm"><div class="card-body">
      <div class="text-uppercase text-muted small">{{ __('Pending Finalization') }}</div>
      <div class="h4 mb-0">{{ $orgDashMetrics['unfinalized'] }}</div>
      <small class="text-muted">{{ __('Rows needing finalize') }}</small>
    </div></div>
  </div>
</div>
