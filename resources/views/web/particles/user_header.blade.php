 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="icon" href="{{URL::asset('images/favicon.png')}}" type="image/png" />
 <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


 <script src="{{URL::asset('commonFile/js/jquery-3.6.0.min.js')}}"></script>
 <script src="{{URL::asset('commonFile/js/jquery.validate.min.js')}}"></script>
 <script src="{{URL::asset('admin/plugins/ckeditor/ckeditor.js')}}"></script>
 <link rel="stylesheet" href="{{URL::asset('commonFile/css/toastr.min.css')}}" />
 <link rel="stylesheet" href="{{URL::asset('commonFile/css/style.css')}}" />
 <link rel="stylesheet" href="{{URL::asset('admin/plugins/jquery-confirm/jquery-confirm.min.css')}}" />
 <title> {{$title ?? ''}} | {{Constant::APP_NAME}} </title> 
 <!-- //Jquery confirmation  -->

 <script src="{{URL::asset('commonFile/js/toastr.min.js')}}"></script>
 <script src="{{URL::asset('commonFile/js/validate.js')}}"></script>
 <script src="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.js')}}"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 <script src="https://kit.fontawesome.com/ada339144b.js" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.css')}}" />
 <script>
     var SITE_URL = "{{url('/').'/'}}"; //https://acc.esignly.com/   
     var THEME_BASE = "/themes/frontend-theme";
     var prepare_doc_sel_show = "role_name";
     var FRONT_URL = "https://www.esignly.com/";
 </script>

 <style>
     .close_frame {
         margin-right: -11px !important;
         margin-top: -11px !important;
     }


 </style>