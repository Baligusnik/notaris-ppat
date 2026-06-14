<!DOCTYPE html>
<html lang="id" data-lang="id" data-view="desktop">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kantor Notaris/PPAT Ni Luh Putu Surya Mira Yanti, SH., M.Kn.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/public-home.css') }}?v={{ filemtime(public_path('css/public-home.css')) }}">
</head>
<body>
    <x-public.header />
    <x-public.settings-menu />
    <x-public.login-modal />
    <x-public.register-modal />
    <main>
        <x-public.profile-section />
        <x-public.handled-documents-section />
        <x-public.services-section />
        <x-public.tracking-section />
        <x-public.contact-section />
        <x-public.address-section />
    </main>
    <x-public.footer />
    <script src="{{ asset('js/public-home.js') }}?v={{ filemtime(public_path('js/public-home.js')) }}" defer></script>
</body>
</html>
