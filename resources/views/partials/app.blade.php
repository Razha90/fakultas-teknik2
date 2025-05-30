<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ config('app.name') }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<link rel="icon" href="{{ asset('img/unimed.png') }}" type="image/x-icon">
<meta name="robots" content="index, follow">

@vite(['resources/css/app.css', 'resources/js/app.js'])
<meta name="author" content="Universitas Negeri Medan">
<meta name="theme-color" content="#5fcf80">

<meta property="og:title" content="Beranda - Universitas Negeri Medan">
<meta property="og:description"
    content="Temukan informasi lengkap tentang Unimed, termasuk program studi, berita, dan layanan mahasiswa.">
<meta property="og:image" content="{{ asset('img/unimed.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Beranda - Unimed">
<meta name="twitter:description" content="Website resmi Unimed, info kuliah, berita kampus, dan lebih.">
<meta name="twitter:image" content="{{ asset('img/unimed.png') }}">

@stack('meta')
