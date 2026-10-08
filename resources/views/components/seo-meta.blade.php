@php
    $seo = app(\App\Services\Seo\SeoService::class);

    // Yield overrides if specific Blade views use legacy sections
    $yieldTitle = trim($__env->yieldContent('title'));
    $yieldDesc = trim($__env->yieldContent('description'));
    $yieldRobots = trim($__env->yieldContent('robots'));
    $yieldCanonical = trim($__env->yieldContent('canonical'));

    $finalTitle = !empty($yieldTitle) ? $yieldTitle : $seo->getTitle();
    $finalDesc = !empty($yieldDesc) ? $yieldDesc : $seo->getDescription();
    $finalRobots = !empty($yieldRobots) ? $yieldRobots : $seo->getRobots();
    $finalCanonical = !empty($yieldCanonical) ? $yieldCanonical : $seo->getCanonical();
    $ogImage = $seo->getOgImage();
    $ogType = $seo->getOgType();
    $siteName = config('seo.site_name', 'ShopPulss');
    $locale = config('seo.default_locale', 'en_PK');
@endphp

{{-- Standard Primary Meta Tags --}}
<title>{{ $finalTitle }}</title>
<meta name="title" content="{{ $finalTitle }}">
<meta name="description" content="{{ $finalDesc }}">
<meta name="robots" content="{{ $finalRobots }}">
<link rel="canonical" href="{{ $finalCanonical }}">

{{-- Open Graph / Facebook Protocol --}}
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $finalCanonical }}">
<meta property="og:title" content="{{ $finalTitle }}">
<meta property="og:description" content="{{ $finalDesc }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:locale" content="{{ $locale }}">

{{-- Twitter / X Cards --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $finalCanonical }}">
<meta name="twitter:title" content="{{ $finalTitle }}">
<meta name="twitter:description" content="{{ $finalDesc }}">
<meta name="twitter:image" content="{{ $ogImage }}">

{{-- Structured Data (JSON-LD) --}}
@foreach($seo->getSchemas() as $schema)
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endforeach

@stack('jsonld')
