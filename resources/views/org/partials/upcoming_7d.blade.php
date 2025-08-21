@php($vcOrgId = $vcOrgId ?? null)
@php
use Illuminate\Support\Facades\DB;
$list = collect();
if ($orgId) {
  $now = Illuminate\Support\Carbon::now();
  $soon= $now->copy()->addDays(7);
  $list = DB::table('opportunities')
      ->where('organization_id',$orgId)
      ->whereBetween('start_at', [$now, $soon])
      ->orderBy('start_at')->limit(10)->get();
}
@endphp
<div class="card shadow-sm mt-3">
  <div class="card-header"><strong>{{ __('Upcoming (7 days)') }}</strong></div>
  <div class="table-responsive">
    <table class="table table-sm mb-0">
      <thead><tr><th>{{ __('Date') }}</th><th>{{ __('Title') }}</th><th></th></tr></thead>
      <tbody>
      @forelse($list as $o)
        <tr>
          <td>{{ \Illuminate\Support\Illuminate\Support\Carbon::parse($o->start_at)->format('Y-m-d H:i') }}</td>
          <td>{{ $o->title ?? ('#'.$o->id) }}</td>
          <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('org.certificates.index', ['opportunity'=>$o->id]) }}">{{ __('Certificates') }}</a></td>
        </tr>
      @empty
        <tr><td colspan="3" class="text-muted text-center p-3">{{ __('No upcoming items') }}</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
