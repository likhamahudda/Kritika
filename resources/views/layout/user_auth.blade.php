<!doctype html>
<html lang="en">
<head>
    @include('web.particles.header_links')
    @stack('css')
</head>

<body class="bg-login">
    
  @yield('content') 

    @stack('scripts')
    
</body>

</html>