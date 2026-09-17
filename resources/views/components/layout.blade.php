<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'BillBatch' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Zalando+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-mist-950 text-mist-100 font-mono antialiased">
    {{ $slot }}

    <!-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    @livewireScripts
</body>
</html>