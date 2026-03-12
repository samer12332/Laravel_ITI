<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'ITI Blog' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 font-sans text-slate-800">
    <nav class="bg-slate-800 text-white shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center gap-8 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('posts.index') }}" class="text-2xl font-semibold tracking-tight">ITI Blog</a>
            <a href="{{ route('posts.index') }}" class="text-sm font-medium text-slate-100 transition hover:text-white">
                All Posts
            </a>
            <a href="{{ route('posts.create') }}" class="text-sm font-medium text-slate-100 transition hover:text-white">
                Create Post
            </a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
</body>

</html>
