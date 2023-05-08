@extends('layout.user_auth')
@section('content')
 


<section class="full login-page ">
  <div class="container-fluid">
   <div class="row">

   <div class="col-md-6 d-md-block d-none">
       <div class="login-bg-right">
         <div class="right-img text-center">
           <img src="{{URL::asset('images/login-right-img.png')}}" alt="">
         </div>
         <div class="login-text pl-md-5 pl-3 mt-5">
           <h3 class="text-white">For Seller</h3>
           <ul class="mt-3">
             <li>Structured Communication</li>
             <li>Collaboration</li>
             <li>Real Time Notifications</li>
             <li>Low Code Module Builder</li>            
           </ul>
         </div>
       </div>
     </div>

     <div class="col-md-6 col-sm-12">
       <div class="card-login">
         
         <div class="login-card">
          <div class="login-logo">
             <img src="{{URL::asset('images/login-logo.png')}}" alt="">
           </div>
           <div class="login-hedding">
             <h2>Reset Password</h2>
             <p>Please enter your email address to request a password reset.</p>

             @if ($message = Session::get('success'))
            <strong>{{ $message }}</strong>
            @endif
            @if ($message = Session::get('error'))
            <strong style="color:red">{{ $message }}</strong>
            @endif 
             
           </div>
 
           {{ Form::open(['url' => route('user.sendLink'), 'class' => 'form_submit', 'id'=>'formAuthentication' , 'method'=>'Post']) }} 
            
           <div class="select-type">
             <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="user_type" id="inlineRadio1" value="0" checked>
                <label class="form-check-label" for="inlineRadio1">Buyer Login</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="user_type" id="inlineRadio2" value="1">
                <label class="form-check-label" for="inlineRadio2">Sellers Login</label>
              </div>              
           </div>
           
           <div class="form-group">
                <label>Email Address</label>
                <input class="form-control" placeholder="Your Email" value="" name="email" type="email" required="">
              </div> 
              <div class="form-group">
                  <input value="Reset Password" class="btn btn-blue w-100 mt-4" type="submit" name="yt2">
                 
              </div>          
              {{ Form::close() }}
              <p class="login-already text-center ">Have an account? <a id="open_signup_panel" href="{{route('user.login')}}">Login</a></p>
          </div>
       </div>
     </div>



   </div>
  </div>
 </section>

  

    @endsection
    @push('scripts')
<!-- <script src="{{URL::asset('admin/js/jquery.min.js')}}"></script> -->
<script src="{{URL::asset('commonFile/js/validate.js')}}"></script>

    <script src="{{URL::asset('commonFile/js/toastr.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.form_submit').validate({
                rules: {
                    
                },
                messages: {
                   
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