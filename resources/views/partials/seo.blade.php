@php
    $title = $title ?? config('app.name', 'Look at History');
    $description = $description ?? 'Blog sejarah dunia ringkas & terpercaya. Temukan artikel peradaban kuno, perang dunia, tokoh sejarah, dan peristiwa penting masa lalu.';
    $image = $image ?? asset('logo_LAH.jpg');
    $noindex = $noindex ?? false;

    // Canonical: gunakan URL tanpa query string untuk hindari duplikasi
    $canonicalUrl = rtrim(config('app.url'), '/') . '/' . ltrim(request()->path(), '/');
    // Normalize root URL (bukan "/")
    if ($canonicalUrl === rtrim(config('app.url'), '/') . '/') {
        $canonicalUrl = rtrim(config('app.url'), '/');
    }

    $url = $canonicalUrl;

    $type = $type ?? 'website';
    $publishedTime = $publishedTime ?? null;
    $modifiedTime = $modifiedTime ?? null;
    $author = $author ?? config('app.name', 'Look at History');
    $section = $section ?? null;
    $tags = $tags ?? [];
    $schema = $schema ?? null;
    $keywords = $keywords ?? ($tags ? implode(', ', $tags) : null);
    $isHomepage = $isHomepage ?? false;
@endphp

@if ($noindex)
<meta name="robots" content="noindex, nofollow">
@endif

<meta name="description" content="{{ $description }}">
@if ($keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="{{ $url }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ config('app.name', 'Look at History') }}">
<meta property="og:locale" content="id_ID">

@if ($publishedTime)
<meta property="article:published_time" content="{{ $publishedTime }}">
@endif

@if ($modifiedTime)
<meta property="article:modified_time" content="{{ $modifiedTime }}">
@endif

<meta property="article:author" content="{{ $author }}">

@if ($section)
<meta property="article:section" content="{{ $section }}">
@endif

@foreach ($tags as $tag)
<meta property="article:tag" content="{{ $tag }}">
@endforeach

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

<link rel="canonical" href="{{ $canonicalUrl }}">
<link rel="alternate" type="application/rss+xml" title="{{ config('app.name', 'Look at History') }} RSS" href="{{ route('feed') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

@if ($schema)
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif

@if ($isHomepage)
@php
    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => config('app.name', 'Look at History'),
        'url' => rtrim(config('app.url'), '/'),
        'description' => $description,
        'inLanguage' => 'id',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => rtrim(config('app.url'), '/') . '/cari?q={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => config('app.name', 'Look at History'),
            'url' => rtrim(config('app.url'), '/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('logo_LAH.jpg'),
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif