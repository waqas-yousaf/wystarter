@props([
    'title'       => config('app.name'),
    'description' => null,
    'image'       => null,
    'canonical'   => null,
])

<title>{{ $title }} — {{ config('app.name') }}</title>
@if ($description)
    <meta name="description" content="{{ $description }}">
@endif

<meta property="og:title" content="{{ $title }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:type" content="website">
@if ($description)
    <meta property="og:description" content="{{ $description }}">
@endif
@if ($image)
    <meta property="og:image" content="{{ $image }}">
@endif
@if ($canonical)
    <meta property="og:url" content="{{ $canonical }}">
    <link rel="canonical" href="{{ $canonical }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
@if ($description)
    <meta name="twitter:description" content="{{ $description }}">
@endif
@if ($image)
    <meta name="twitter:image" content="{{ $image }}">
@endif
