<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    {{-- Viteのアセット --}}
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
   
</head>

<body>
    @include('commons.navbar')

    <div class="container">
        @include('commons.error_messages')
        @yield('content')
    </div>
    
</body>

</html>
