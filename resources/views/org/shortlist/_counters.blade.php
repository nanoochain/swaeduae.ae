@php($vcOrgId = $vcOrgId ?? null)
@php
use Illuminate\Support\Facades\DB;

$oppId = isset($opportunity) ? ($opportunity->id ?? null) : ($opportunity_id ?? null);
$cap = null;

if ($oppId) {
  // Prefer column if present
  if (DB::getSchemaBuilder()->hasColumn('opportunities','slot_cap')) {
    $cap = DB::table('opportunities')->where('id',$oppId)->value('slot_cap');
  }
  // Fallback to settings
  if (!$cap) {
    $cap = DB::table('settings')->where('key',"opp:{$oppId}:slot_cap")->value('value');
  }

  $shortlisted = DB::table('applications')->where('opportunity_id',$oppId)->where('status','shortlisted')->count();
  $pending     = DB::table('applications')->where('opportunity_id',$oppId)->where('status','pending')->count();
  $approved    = DB::table('applications')->where('opportunity_id',$oppId)->where('status','approved')->count();
} else {
  $shortlisted = $pending = $approved = 0;
}

@endphp

<div class="row g-3 align-items-stretch mb-3">
  <div class="col-6 col-md-3">
    <div class="card shadow-sm h-100"><div class="card-body">
      <div class="text-muted small">{{ __('Slot Cap') }}</div>
      <div class="h4 mb-0">{{ $cap ?: '—' }}</div>
    </div></div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm h-100"><div class="card-body">
      <div class="text-muted small">{{ __('Shortlisted') }}</div>
      <div class="h4 mb-0">{{ $shortlisted }}</div>
    </div></div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm h-100"><div class="card-body">
      <div class="text-muted small">{{ __('Approved') }}</div>
      <div class="h4 mb-0">{{ $approved }}</div>
    </div></div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm h-100"><div class="card-body">
      <div class="text-muted small">{{ __('Pending') }}</div>
      <div class="h4 mb-0">{{ $pending }}</div>
    </div></div>
  </div>
</div>
