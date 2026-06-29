<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo
        :title="$page->meta_title ?? $page->title"
        :description="$page->meta_description"
        :canonical="route('page.show', $page)"
    />
    <link rel="stylesheet" href="/build/css/main.css">
</head>
<body>
    <main>
        <h1>{{ $page->title }}</h1>
        <div class="page-body">
            {!! $page->body !!}
        </div>
    </main>
</body>
</html>
