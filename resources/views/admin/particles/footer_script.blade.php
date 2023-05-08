    <script src="{{URL::asset('admin/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{URL::asset('admin/plugins/simplebar/js/simplebar.min.js')}}"></script>
    <script src="{{URL::asset('admin/plugins/metismenu/js/metisMenu.min.js')}}"></script>
    <script src="{{URL::asset('admin/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
    <script src="{{URL::asset('admin/js/app.js')}}"></script>

    <script src="{{URL::asset('commonFile/js/toastr.min.js')}}"></script> 
    <script src="{{URL::asset('commonFile/js/validate.js')}}"></script>
    <script src="{{URL::asset('admin/js/custom_function.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script>


   //Get panchayat_id by tehsils
   $('.notification_alert').click(function () {
       
       $.ajax({
           type: "GET",
           url: "{{ route('families.read-notification') }}",
           data: { status: 1 },
           dataType: "json",
           success: function (states) {
               $('.alert-count').text('0');
                
           }
       });
   });
    </script>
    

    @include('commonFile.message')









