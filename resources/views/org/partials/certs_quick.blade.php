@php($vcOrgId = $vcOrgId ?? null)
@php
use Illuminate\Support\Facades\DB;
$recentOpps = collect();
if ($orgId) {
  $recentOpps = DB::table('opportunities')
     ->where('organization_id',$orgId)
     ->orderByDesc('created_at')->limit(3)->get();
}
@endphp

<div class="card shadow-sm mt-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <strong>{{ __('Certificates – Quick Access') }}</strong>
    <a class="btn btn-sm btn-outline-secondary" href="{{ url('/org/opportunities') }}">{{ __('All Opportunities') }}</a>
  </div>
  <div class="card-body">
    @if($recentOpps->isEmpty())
      <div class="text-muted">{{ __('No opportunities yet.') }}</div>
    @else
      <div class="list-group">
        @foreach($recentOpps as $o)
          <div class="list-group-item d-flex justify-content-between align-items-center">
            <div class="me-2">{{ $o->title ?? ('#'.$o->id) }}</div>
            <div class="d-flex gap-2">
              <a class="btn btn-sm btn-primary" href="{{ route('org.certificates.index', ['opportunity'=>$o->id]) }}">{{ __('Certificates') }}</a>
              <a class="btn btn-sm btn-outline-primary" href="{{ route('org.certificates.export.csv', ['opportunity'=>$o->id]) }}">{{ __('Export CSV') }}</a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
