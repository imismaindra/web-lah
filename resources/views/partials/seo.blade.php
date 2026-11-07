@php
    $title = $title ?? config('app.name', 'Look at History');
    $description = $description ?? 'Blog sejarah dunia ringkas & terpercaya. Temukan artikel peradaban kuno, perang dunia, tokoh sejarah, dan peristiwa penting masa lalu.';
    $image = $image ?? asset('logo_LAH.jpg');
    $noindex = $noindex ?? false;
    $isPreview = $isPreview ?? false;

    // Prefer canonical URL explicitly supplied by the page (e.g. article permalink).
    // For paginated listing pages, keep only the page parameter; discard tracking/filter queries.
    $baseCanonicalUrl = rtrim(config('app.url'), '/') . '/' . ltrim(request()->path(), '/');
    if ($baseCanonicalUrl === rtrim(config('app.url'), '/') . '/') {
        $baseCanonicalUrl = rtrim(config('app.url'), '/');
    }

    $pageNumber = request()->query('page');
    if (! isset($url) && is_scalar($pageNumber) && ctype_digit((string) $pageNumber) && (int) $pageNumber > 1) {
        $baseCanonicalUrl .= '?page=' . (int) $pageNumber;
    }

    $canonicalUrl = $url ?? $baseCanonicalUrl;
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

<meta name="robots" content="{{ $noindex ? 'noindex, follow' : 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1' }}">

<meta name="description" content="{{ $description }}">
@if ($keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif

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

@php($ga4MeasurementId = config('services.google_analytics.measurement_id'))
@if (! $isPreview && $ga4MeasurementId)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ rawurlencode($ga4MeasurementId) }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', @json($ga4MeasurementId));

    window.trackGa4Event = function (eventName, parameters = {}) {
        if (typeof window.gtag === 'function') {
            window.gtag('event', eventName, parameters);
        }
    };

    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) return;

        const trigger = event.target.closest('[data-ga4-event]');
        if (!trigger) return;

        const parameters = {};
        if (trigger.dataset.ga4Method) parameters.method = trigger.dataset.ga4Method;
        if (trigger.dataset.ga4ContentType) parameters.content_type = trigger.dataset.ga4ContentType;
        if (trigger.dataset.ga4ItemId) parameters.item_id = trigger.dataset.ga4ItemId;

        window.trackGa4Event(trigger.dataset.ga4Event, parameters);
    });

    document.addEventListener('submit', (event) => {
        if (!(event.target instanceof HTMLFormElement)) return;
        if (!event.target.matches('[data-ga4-search]')) return;

        window.trackGa4Event('search');
    });
</script>
@endif

@if ($schema)
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endif

<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    '@id' => rtrim(config('app.url'), '/') . '#organization',
    'name' => config('app.name', 'Look at History'),
    'url' => rtrim(config('app.url'), '/'),
    'description' => 'Media sejarah yang menjelaskan mengapa peristiwa masa lalu masih relevan dengan dunia sekarang.',
    'logo' => [
        '@type' => 'ImageObject',
        'url' => asset('logo_LAH.jpg'),
    ],
    'sameAs' => [
        'https://www.instagram.com/lookathistory/',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>

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
            '@id' => rtrim(config('app.url'), '/') . '#organization',
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endif