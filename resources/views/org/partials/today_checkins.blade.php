@php($vcOrgId = $vcOrgId ?? null)
@php
use Illuminate\Support\Facades\DB;
$rows = collect();
if ($orgId) {
  $today = Illuminate\Support\Carbon::now()->toDateString();
  $rows = DB::table('attendances as a')
     ->join('users as u','u.id','=','a.user_id')
     ->join('opportunities as o','o.id','=','a.opportunity_id')
     ->whereDate('a.check_in_at',$today)
     ->where('o.organization_id',$orgId)
     ->orderByDesc('a.check_in_at')
     ->selectRaw('u.name as user_name, o.title as opportunity_title, a.check_in_at, a.check_out_at, a.minutes')
     ->limit(15)->get();
}
@endphp
<div class="card shadow-sm mt-3">
  <div class="card-header"><strong>{{ __('Today’s Check-ins') }}</strong></div>
  <div class="table-responsive">
    <table class="table table-sm mb-0">
      <thead><tr><th>{{ __('Name') }}</th><th>{{ __('Opportunity') }}</th><th>{{ __('In') }}</th><th>{{ __('Out') }}</th><th>{{ __('Minutes') }}</th></tr></thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ $r->user_name }}</td>
            <td>{{ $r->opportunity_title }}</td>
            <td>{{ \Illuminate\Support\Illuminate\Support\Carbon::parse($r->check_in_at)->format('H:i') }}</td>
            <td>{{ $r->check_out_at ? \Illuminate\Support\Illuminate\Support\Carbon::parse($r->check_out_at)->format('H:i') : '—' }}</td>
            <td>{{ $r->minutes ?? 0 }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-muted text-center p-3">{{ __('No check-ins yet') }}</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
