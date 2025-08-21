@php
    $site = config('app.name', 'Swaed UAE');
    $title = trim(($title ?? '') ?: ($pageTitle ?? '') ?: $site);
    $desc  = trim(($description ?? '') ?: 'Volunteer opportunities across the UAE. Join and make an impact.');
    $canonical = ($canonical ?? '') ?: url()->current();
    $image = ($image ?? '') ?: asset('images/og-default.jpg');

    $locales = ['ar','en'];
    $alt = [];
    foreach ($locales as $loc) { $alt[$loc] = request()->fullUrlWithQuery(['lang' => $loc]); }
    $xDefault = url()->current();

    $ld_json = $ld_json ?? null;
    $breadcrumbs = $breadcrumbs ?? null;

    $ld_breadcrumbs = null;
    if (is_array($breadcrumbs) && count($breadcrumbs)) {
        $items = [];
        $pos = 1;
        foreach ($breadcrumbs as $bc) {
            $items[] = ['@type'=>'ListItem','position'=>$pos++,'name'=>(string)($bc['name']??''),'item'=>(string)($bc['url']??'')];
        }
        $ld_breadcrumbs = ['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>$items];
    }
@endphp
<title>{{ $title }}</title>
<meta name="description" content="{{ $desc }}">
<link rel="canonical" href="{{ $canonical }}" />
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:site_name" content="{{ $site }}">
<meta property="og:image" content="{{ $image }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $desc }}">
<meta name="twitter:image" content="{{ $image }}">
@foreach($alt as $loc => $url)
<link rel="alternate" href="{{ $url }}" hreflang="{{ $loc }}">
@endforeach
<link rel="alternate" href="{{ $xDefault }}" hreflang="x-default">
@if(!empty($ld_json))
<script type="application/ld+json">{!! json_encode($ld_json, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}</script>
@endif
@if(!empty($ld_breadcrumbs))
<script type="application/ld+json">{!! json_encode($ld_breadcrumbs, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}</script>
@endif
