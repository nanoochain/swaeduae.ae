@php($vcOrgId = $vcOrgId ?? null)
@php
use Illuminate\Support\Facades\DB;

$oppIds = $orgId
  ? DB::table('opportunities')->where('organization_id', $orgId)->pluck('id')->all()
  : [];

$activities = [];

if (!empty($oppIds)) {
  // Opportunities
  $a1 = DB::table('audit_logs')
        ->where('entity_type','opportunity')
        ->whereIn('entity_id',$oppIds)
        ->orderByDesc('created_at')
        ->limit(20)
        ->get()
        ->map(function($r){ $r->label = __('Opportunity'); return $r; })->all();

  // Applications → via opportunity_id
  $appIds = DB::table('applications')->whereIn('opportunity_id',$oppIds)->pluck('id')->all();
  $a2 = [];
  if (!empty($appIds)) {
    $a2 = DB::table('audit_logs')
          ->where('entity_type','application')
          ->whereIn('entity_id',$appIds)
          ->orderByDesc('created_at')
          ->limit(20)
          ->get()
          ->map(function($r){ $r->label = __('Application'); return $r; })->all();
  }

  // Attendances → via opportunity_id
  $attIds = DB::table('attendances')->whereIn('opportunity_id',$oppIds)->pluck('id')->all();
  $a3 = [];
  if (!empty($attIds)) {
    $a3 = DB::table('audit_logs')
          ->where('entity_type','attendance')
          ->whereIn('entity_id',$attIds)
          ->orderByDesc('created_at')
          ->limit(20)
          ->get()
          ->map(function($r){ $r->label = __('Attendance'); return $r; })->all();
  }

  $activities = array_merge($a1,$a2,$a3);
  usort($activities, fn($x,$y) => strcmp($y->created_at, $x->created_at));
  $activities = array_slice($activities, 0, 10);
}
@endphp

<div class="card shadow-sm mt-4">
  <div class="card-header">
    <strong>{{ __('Recent Activity') }}</strong>
  </div>
  <div class="card-body p-0">
    @if(empty($activities))
      <div class="p-3 text-muted">{{ __('No recent activity yet.') }}</div>
    @else
      <ul class="list-group list-group-flush">
        @foreach($activities as $act)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <span class="badge bg-secondary me-2">{{ $act->label }}</span>
              <span class="text-muted">{{ $act->action ?? 'event' }}</span>
              @if(!empty($act->note)) <span class="ms-1">— {{ Str::limit($act->note, 80) }}</span> @endif
            </div>
            <small class="text-muted">{{ \Illuminate\Support\Illuminate\Support\Carbon::parse($act->created_at)->diffForHumans() }}</small>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</div>
