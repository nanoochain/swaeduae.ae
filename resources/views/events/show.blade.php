<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>{{ $event->title ?? $event->name ?? ('Event #'.$event->id) }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php $canonical = url('/events/'.($event->slug ?: $event->id)); @endphp
  <link rel="canonical" href="{{ $canonical }}">
  <style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:2rem;} code,pre{background:#f6f8fa;padding:.5rem;border-radius:.5rem;}</style>
</head>
<body>
  <h1>{{ $event->title ?? $event->name ?? ('Event #'.$event->id) }}</h1>
  @if(!empty($event->slug))<p><strong>Slug:</strong> {{ $event->slug }}</p>@endif
  @if(isset($event->starts_at))<p><strong>Starts at:</strong> {{ $event->starts_at }}</p>@endif
  @if(isset($event->ends_at))<p><strong>Ends at:</strong> {{ $event->ends_at }}</p>@endif
  <hr>
  <pre style="white-space:pre-wrap">{{ print_r($event->toArray(), true) }}</pre>
</body>
</html>
