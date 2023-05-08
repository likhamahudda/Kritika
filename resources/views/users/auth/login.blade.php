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
             <h2>Sign in</h2>
             <p>Sign in to your account</p>

             @if ($message = Session::get('success'))
            <strong>{{ $message }}</strong>
            @endif
            @if ($message = Session::get('error'))
            <strong style="color:red">{{ $message }}</strong>
            @endif 
             
           </div>

          

       
           <form id="signin-form" class="form_submit" action="{{route('user.do_login')}}" target="_top" method="post">
            
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
                <input class="form-control" placeholder="Your Email" value="{{ (isset($user['email']) ? $user['email'] : '') }}" name="email" id="LoginForm_email" type="email" required="">

              </div>
              <div class="form-group">
                <label>Password</label>
                <input value="{{ (isset($user['password']) ? $user['password'] : '' ) }}" class="form-control" placeholder="Your Password" name="password" id="LoginForm_password" type="password" required="" minlength="8" maxlength="12">
               <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password" aria-hidden="true"></span>
              </div>
              <div class="for-pass text-right"> 
                <a type="button" href="{{route('user.forget.password')}}" data-type="toggle-login-form">Forgot password?</a>
              </div>
              <div class="form-group">
                 <input value="Login" class="btn btn-blue w-100 mt-4" type="submit" name="yt2"> 
                
              </div>          
            </form>
            <p class="login-already text-center ">Don't have an account? <a id="open_signup_panel" href="{{route('user.signup')}}">Sign Up</a></p>
          </div>
       </div>
     </div>



   </div>
  </div>
 </section>


@endsection

@push('scripts')
<script type="text/javascript">
  $(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>

<script type="text/javascript">
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
                            $('.loader').show();
                            $('button[type="submit"]').prop('disabled', true);
                            $('button[type="submit"]').text('Please wait...');
                        },
                        success: function(data) {
                          $('.loader').hide();

                            if (data.status === true) {
                                if (data.redirect_url != '') {
                                    window.location.replace(data.redirect_url);
                                }
                                toastr.success(data.message);
                                $('button[type="submit"]').prop('disabled', false);
                                $('button[type="submit"]').text(old_text);

                            } else {
                                // /grecaptcha.reset();
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


@endpush