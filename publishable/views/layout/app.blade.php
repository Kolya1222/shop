<!DOCTYPE html>
<html lang="ru">

<head>
    @include('parts.head')
    @yield('styles')
</head>

<body>
    @include('parts.header')
    <main>
        @yield('content')
    </main>
    <div class="container">
        @include('parts.newsletter')
    </div>
    @include('parts.footer')
    @yield('scripts')
</body>

</html>
