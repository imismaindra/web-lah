@php
    $title = $title ?? config('app.name', 'Look at History');
    $description = $description ?? 'Blog sejarah dunia ringkas & terpercaya. Temukan artikel peradaban kuno, perang dunia, tokoh sejarah, dan peristiwa penting masa lalu.';
    $image = $image ?? asset('logo_LAH.jpg');
    $noindex = $noindex ?? false;

    $canonicalUrl = rtrim(config('app.url'), '/') . '/' . ltrim(request()->path(), '/');

    $url = $canonicalUrl;

    $type = $type ?? 'website';
    $publishedTime = $publishedTime ?? null;
    $modifiedTime = $modifiedTime ?? null;
    $author = $author ?? config('app.name', 'Look at History');
    $section = $section ?? null;
    $tags = $tags ?? [];
    $schema = $schema ?? null;
@endphp

@if ($noindex)
<meta name="robots" content="noindex, nofollow">
@endif

<meta name="description" content="{{ $description }}">
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

@if ($schema)
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif