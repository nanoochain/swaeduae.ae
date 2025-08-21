@php($vcOrgId = $vcOrgId ?? null)
@php
  use Illuminate\Support\Facades\DB;
  if (isset($kpi) && is_array($kpi)) {
    $upcoming      = $kpi['upcoming']      ?? 0;
    $appsTotal     = $kpi['appsTotal']     ?? 0;
    $appsApproved  = $kpi['appsApproved']  ?? 0;
    $appsPending   = $kpi['appsPending']   ?? 0;
    $checkinsToday = $kpi['checkinsToday'] ?? 0;
  } else {
    // Fallback (kept for safety if composer not loaded)
    $now   = Illuminate\Support\Carbon::now();

    $upcoming = DB::table('opportunities')->where('organization_id',$orgId)->where('start_at','>=',$now)->count();
    $appsTotal= DB::table('applications')->whereIn('opportunity_id', function($q) use($orgId){ $q->from('opportunities')->select('id')->where('organization_id',$orgId); })->count();
    $appsApproved = DB::table('applications')->where('status','approved')->whereIn('opportunity_id', function($q) use($orgId){ $q->from('opportunities')->select('id')->where('organization_id',$orgId); })->count();
    $appsPending  = DB::table('applications')->where('status','pending')->whereIn('opportunity_id', function($q) use($orgId){ $q->from('opportunities')->select('id')->where('organization_id',$orgId); })->count();
    $checkinsToday= DB::table('attendances')->whereDate('check_in_at', $now->toDateString())
                     ->whereIn('opportunity_id', function($q) use($orgId){ $q->from('opportunities')->select('id')->where('organization_id',$orgId); })->count();
  }
@endphp

<div class="row g-3">
  <div class="col-6 col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small mb-1">{{ __('Upcoming Opps') }}</div>
        <div class="h3 mb-0">{{ $upcoming }}</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small mb-1">{{ __('Applicants') }}</div>
        <div class="h3 mb-0">{{ $appsTotal }}</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small mb-1">{{ __('Approved') }}</div>
        <div class="h3 mb-0">{{ $appsApproved }}</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small mb-1">{{ __('Pending') }}</div>
        <div class="h3 mb-0">{{ $appsPending }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small mb-1">{{ __('Today Check-ins') }}</div>
        <div class="h3 mb-0">{{ $checkinsToday }}</div>
      </div>
    </div>
  </div>
</div>

<div class="mt-3 d-flex gap-2 flex-wrap">
  <a class="btn btn-primary" href="{{ route('org.opportunities.create') }}">{{ __('Create Opportunity') }}</a>
  <a class="btn btn-outline-primary" href="{{ url('/org/applicants') }}">{{ __('View Applicants') }}</a>
  <a class="btn btn-outline-primary" href="{{ url('/org/attendance') }}">{{ __('Show QR Panel') }}</a>
  <a class="btn btn-outline-secondary" href="{{ route('org.settings.edit') }}">{{ __('Settings') }}</a>
</div>
