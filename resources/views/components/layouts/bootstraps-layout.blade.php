<!DOCTYPE html>
<html lang="{{ Cookie::get('locale', 'id') }}">

<head>
    @include('partials.bootstrap')
</head>

<body class="dark:bg-linear-to-b min-h-screen bg-white antialiased dark:from-neutral-950 dark:to-neutral-900">
    {{ $slot }}

    @fluxScripts
</body>

</html>
