@php($vcOrgId = $vcOrgId ?? null)
@php
  // Prefer injected from View Composer
  if (!empty($brandColor)) {
    $color = $brandColor;
  } else {
    // Fallback minimal queries
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    $color = null;
    if ($orgId) {
      if (DB::getSchemaBuilder()->hasColumn('organizations','primary_color')) {
        $color = DB::table('organizations')->where('id',$orgId)->value('primary_color');
      }
      if (!$color) {
        $color = DB::table('settings')->where('key',"org:{$orgId}:primary_color")->value('value');
      }
    }
  }
@endphp

@if(!empty($color))
<style>
:root { --org-primary: {{ $color }}; }
.btn-primary, .bg-primary { background-color: var(--org-primary) !important; border-color: var(--org-primary) !important; }
a, .text-primary { color: var(--org-primary) !important; }
.form-check-input:checked { background-color: var(--org-primary) !important; border-color: var(--org-primary) !important; }
.badge.bg-primary { background-color: var(--org-primary) !important; }
</style>
@endif
