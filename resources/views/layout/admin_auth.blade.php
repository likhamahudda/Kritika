<!doctype html>
<html lang="en">
<head>
    @include('admin.particles.header')
    @stack('css')
</head>

<body class="bg-login">
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            @yield('content')
        </div>
    </div>


    @stack('scripts')
    
</body>

</html>