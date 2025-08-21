@php($vcOrgId = $vcOrgId ?? null)
@php /* __BRANDING_PRELUDE__ */
if (!isset($settings)) {
    $settings = [];
    if ($orgId) {
        if (DB::getSchemaBuilder()->hasColumn('organizations','primary_color')) {
            $settings['primary_color'] = DB::table('organizations')->where('id',$orgId)->value('primary_color');
        }
        if (DB::getSchemaBuilder()->hasColumn('organizations','logo_path')) {
            $settings['logo_path'] = DB::table('organizations')->where('id',$orgId)->value('logo_path');
        }
        if (empty($settings['primary_color'])) {
            $settings['primary_color'] = DB::table('settings')->where('key',"org:{$orgId}:primary_color")->value('value');
        }
        if (empty($settings['logo_path'])) {
            $settings['logo_path'] = DB::table('settings')->where('key',"org:{$orgId}:logo_path")->value('value');
        }
    }
}
@endphp

<form method="POST" action="{{ route('org.settings.update') }}" enctype="multipart/form-data" class="card shadow-sm">
  @csrf
  <div class="card-body">
    <h2 class="h5 mb-3">{{ __('Branding') }}</h2>

    <div class="mb-3">
      <label class="form-label">{{ __('Primary Color') }}</label>
      <input type="color" name="primary_color" class="form-control form-control-color"
             value="{{ old('primary_color', $settings['primary_color'] ?? '#0d6efd') }}">
      <div class="form-text">{{ __('Used for dashboard accents.') }}</div>
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Logo') }}</label>
      <input type="file" name="logo" class="form-control" accept="image/*">
      @if(!empty($settings['logo_path']))
        <div class="mt-2">
          <img src="{{ $settings['logo_path'] }}" alt="Logo" style="height:48px">
        </div>
      @endif
    </div>

    <button class="btn btn-primary">{{ __('Save') }}</button>
  </div>
</form>
