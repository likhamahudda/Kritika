<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{URL::asset('images/favicon.png')}}" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="{{URL::asset('commonFile/css/toastr.min.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('commonFile/css/style.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/jquery-confirm/jquery-confirm.min.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.css')}}" />
    <script src="{{URL::asset('commonFile/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{URL::asset('commonFile/js/jquery.validate.min.js')}}"></script>
  

    <title> {{$title ?? ''}} | {{Constant::APP_NAME}} </title>

    @stack('css')
</head>

<body>
    @include('web.particles.user_nav')

    @yield('content')


    <footer class="full">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="copright">ⒸCopyright 2023 Kodago. All reserved</div>
                </div>
            </div>
        </div>
    </footer>



    <script src="{{URL::asset('admin/plugins/ckeditor/ckeditor.js')}}"></script>

    <script src="https://static.esignly.com/frontend-theme/js/jquery-migrate/jquery-migrate-1.3.0.min.js"></script>
    <script src="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('admin/plugins/jquery-confirm/jquery-confirm.min.js')}}"></script>
    <script src="{{URL::asset('commonFile/js/toastr.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/ada339144b.js" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>


    <script type="text/javascript">
        $(document).on('click', '.confirm_logout', function(event) {
            event.preventDefault(); //prevent default action 
            $.confirm({
                title: 'LogOut',
                content: 'Are you sure you want logout!',
                boxWidth: '30%',
                useBootstrap: false,
                buttons: {
                    //btnClass: 'btn-blue',
                    confirm: function() {

                        window.location = "{{route('user.logout')}}";
                    },
                    cancel: function() {

                    },
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            if ((Object.keys(jQuery.browser)[0] == 'chrome' || (Object.keys(jQuery.browser)[0] == 'webkit')) && ($(window).width() < 780)) {

                $.getScript("https://code.jquery.com/ui/1.10.3/jquery-ui.js");
                $.getScript("{{URL::asset('esign_example/frontend-theme/js/user_theme.js')}}");
                $.getScript("{{URL::asset('esign_example/frontend-theme/js/user_theme2.js')}}");
            }
            if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                $('.hidepricingios').hide();
            }
        });
    </script>
    <!-- Modal -->
    <div class="fullmodal modal fade" id="add_sign_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_frame" data-dismiss="modal" aria-hidden="true">&times;</button>

            </div>
            <div class="modal-body">
                Loading......
            </div>
        </div>
        <!-- /.modal-content -->

    </div>
    <!-- /.modal -->
    <div class="modal fade" id="user_sign_signup_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <a type="button" class="close_out" data-dismiss="modal" aria-hidden="true"></a>

                    <div class="inner_d_panel">
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <script>
        var coreframe = "/info/selPayment/?is_iframe=1&is_from_pricing=1";
        var coreframeTeam = "/info/addLicense/?is_iframe=1&is_from_pricing=0";
        var coreframeTeamDistribute = "/info/TeamdistributeLicense/?is_iframe=1&is_from_pricing=0";
        var upgradeRequestinTeam = "/info/selPayment/?is_iframe=1&is_from_pricing=0";
        $(document).ready(function() {
            $('#userflashes').delay(1000).modal('show');
        });
    </script>

    <div class="cust_alert text-muted" id="cust_alert"></div>

    <div class="popup_align" style="display: inline-block; vertical-align: middle;"></div>
    <!-- Modal box for push message -->
    <div class="modal fade hide" id="push_message" style="max-height: 580px; overflow-y: auto; display:none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-message">Message From Admin Desk</h4>
                </div>
                <div id="push_modal_body" class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        /*<![CDATA[*/
        var createTemplate = 0;
        var temp_title = ' Document ';
        /*]]>*/
    </script>

    <style>
        .card-header.card-header-fixed {
            bottom: 4.5%;
        }

        div.loader {
            position: fixed;
            display: block;
            z-index: 1111;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
        }

        .loader img {
            left: 0;
            top: 0;
            position: absolute;
            right: 0;
            ;
            bottom: 0;
            margin: auto;
            width: 150px;
            z-index: 1111;
        }


        div.loader:after {
            position: absolute;
            background: rgba(0, 0, 0, 0.6);
            content: '';
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
        .business_image {
    height: 130px;
    width: 150px;
    overflow: hidden;
    background: #eee;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    padding: 0 7px;
    margin-right: 20px;
}
    </style>
    <div class="loader" style="display:none;">
        <img src="<?php echo url("images/loader.jpg"); ?>" alt="Loader">
    </div>


    @stack('scripts')
</body>

</html>