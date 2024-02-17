<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}" class="scrollbar">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel')  }}</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="antialiased bg-slate-900 text-slate-200">
@inertia
</body>
</html>
