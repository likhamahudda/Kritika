<!doctype html>
<html lang="en" class="{{Session::get('theme_mode')}}">
<head>
    @include('admin.particles.header')
    @stack('css')
</head>

<body>
    <div class="wrapper" id="full_menu">
        @include('admin.particles.nav')
        @include('admin.particles.sidebar')
        
        <div class="page-wrapper">
            <div class="page-content">
            @yield('content')
            </div>
        </div>
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!-- <footer class="page-footer">
            <p class="mb-0"> Copyright © 2022 | All Rights Reserved
.</p>
        </footer> -->
    </div>

<!-- Gloval model response  Modal -->
<div class="modal fade" id="manage_data_model" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ajax_response">
        </div>
    </div>
</div>


    @include('admin.particles.theme_style')
    @include('admin.particles.footer_script')
    @stack('scripts')
</body>

</html>