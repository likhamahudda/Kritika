@extends('layout.user_auth')
@section('content')
  
<section class="full login-page ">
  <div class="container-fluid">
   <div class="row">

 

     <div class="col-md-12 col-sm-12">
       <div class="card-login">
         
         <div class="login-card">
          <div class="login-logo" style=" text-align: center; ">
             <img src="{{URL::asset('images/login-logo.png')}}" alt="">
           </div>
           <div class="login-hedding">
             <h2>Generate New Password 🔒</h2>
             <p>We received your reset password request. Please enter your new password!</p>

             @if ($message = Session::get('success'))
            <strong>{{ $message }}</strong>
            @endif
            @if ($message = Session::get('error'))
            <strong style="color:red">{{ $message }}</strong>
            @endif 
             
           </div>
 
           {{ Form::open(['url' => route('user.password.update'), 'class' => 'form_submit', 'id'=>'formAuthentication' , 'method'=>'Post']) }}
              <div class="form-group">
                <label>Password</label>
                {{ Form::password('password',['class' =>'form-control', 'id'=>'password', 'placeholder' =>'New Password', 'required'=>'true' ]) }}

              </div>
              <div class="form-group">
                <label>Confirm Password</label>
                {{ Form::password('confirm_password',['class' =>'form-control', 'placeholder' =>'Confirm Password', 'required'=>'true' ]) }}
               </div>

               <input type="hidden" name="token" value="{{request()->token}}">
               <input type="hidden" name="user_type" value="{{request()->user_type}}">
              <div class="form-group">
        
              <input id="signup-btn" name="newpassword" value="Generate" class="btn btn-blue w-100 mt-4" type="submit"> 

                
              </div>          
              {{ Form::close() }}
            <p class="login-already text-center ">  <a id="open_signup_panel" href="{{route('user.login')}}"> Back to login</a></p>
          </div>
       </div>
     </div>



   </div>
  </div>
 </section>

<!-- /Forgot Password -->


@endsection
@push('scripts')
<!-- <script src="{{URL::asset('admin/js/jquery.min.js')}}"></script> -->
<script src="{{URL::asset('commonFile/js/validate.js')}}"></script>

    <script src="{{URL::asset('commonFile/js/toastr.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.form_submit').validate({
                rules: {
                    confirm_password : {
                        equalTo: "#password"
                    },
                    password : {
                        maxlength: 12,
                        minlength: 8
                    },
                },
                messages: {
                    confirm_password : {
                        equalTo: "The password and confirm password do not match."
                    },
                    password : {
                        maxlength: 'Please enter no more than 12 characters.',
                        minlength: 'Please enter at least 8 characters.'
                    }, 
                },
                errorElement: 'span',
                  errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-control').parent().append(error);
                  },
                  highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                  },
                  unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                  },
             /*  submitHandler: function (form) {
                    $('.loader').show();
              $( "#event_add" ).submit();
                    form.submit();

                } */
            submitHandler: function (form) {
                // $(".form_submit").submit(function(event) {
                //     event.preventDefault(); //prevent default action 
                // if ("" == grecaptcha.getResponse()) return $(".g-recaptcha iframe").css("border", "1px solid red"), !1;
                //         $(".g-recaptcha iframe").css("border", "1px solid #dadada");
                        var form_all = $(".form_submit")[0];
                    var old_text = $('button[type="submit"]').text();
                    // if ($(this).valid()) {
                    //     $('button[type="submit"]').prop('disabled', true);
                    //     $('button[type="submit"]').text('Please wait...');
                    // } else {
                    //     $('button[type="submit"]').prop('disabled', false);
                    //     $('button[type="submit"]').text(old_text);
                    // }

                    //if ($('.form_submit').valid()) {
                    $.ajax({
                        url: $(form_all).attr("action"),
                        type: $(form_all).attr("method"),
                        data: $(form_all).serialize(),
                        beforeSend: function(){
                            $('button[type="submit"]').prop('disabled', true);
                            $('button[type="submit"]').text('Please wait...');
                            //grecaptcha.reset();
                        },
                        success: function(data) {
                            if (data.status === true) {
                                if (data.redirect_url != '') {
                                    window.location.replace(data.redirect_url);
                                }
                                toastr.success(data.message);
                                $('button[type="submit"]').prop('disabled', false);
                                $('button[type="submit"]').text(old_text);

                            } else {
                                toastr.error(data.message);
                                $('button[type="submit"]').prop('disabled', false);
                                $('button[type="submit"]').text(old_text);
                            }
                        },
                        error: function(e) {
                            $('button[type="submit"]').prop('disabled', false);
                            $('button[type="submit"]').text(old_text);
                            toastr.error(e.responseJSON.message);
                        }
                    });
                //}
            //});
            }
        });
    });
    </script>
@endpush