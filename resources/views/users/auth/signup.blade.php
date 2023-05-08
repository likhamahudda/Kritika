
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
             <h2>Sign Up</h2>
             <p>Sign Up to your account</p>

             @if ($message = Session::get('success'))
            <strong>{{ $message }}</strong>
            @endif
            @if ($message = Session::get('error'))
            <strong style="color:red">{{ $message }}</strong>
            @endif 
             
           </div>

          

        
           <form id="register-form" class="form_submit" action="{{route('user.do_register')}}" method="post" target="_top">
             

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
                <label>Name</label>
                 <input class="form-control" placeholder="Your Name" name="first_name" id="RegistrationForm_first_name" type="text" required="">  
           </div>

           <div class="form-group">
                <label>Email Address</label>
                 <input class="form-control" placeholder="Your Email" name="email" id="RegistrationForm_email" type="email" required="">  
           </div>
           <div class="form-group">
                <label>Phone No</label>
                 <input class="form-control" placeholder="Your Phone no" name="mobile" id="RegistrationForm_mobile" type="text" required=""   minlength="10" maxlength="10">  
           </div>

              <div class="form-group">
                <label>Password</label>
                 <input class="form-control" placeholder="Your Password" name="password" id="RegistrationForm_password" type="password" minlength="8" maxlength="12" required="">

               <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password" aria-hidden="true"></span>
              </div>
              
              <div class="form-group">
                
                 <input id="signup-btn" value="Sign Up" class="btn btn-blue w-100 mt-4" type="submit" name="yt2"> 
                
              </div>          
            </form>
            <p class="login-already text-center ">Have an account? <a id="open_signup_panel" href="{{route('user.login')}}">Login</a></p>
          </div>
       </div>
     </div>



   </div>
  </div>
 </section>


@endsection

@push('scripts')
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
                            $('button[type="submit"]').prop('disabled', true);
                            $('button[type="submit"]').text('Please wait...');
                        },
                        success: function(data) {
                            if (data.status === true) {
                                // if (data.redirect_url != '') {
                                //     window.location.replace(data.redirect_url);
                                // }
                                toastr.success(data.message);
                                $('button[type="submit"]').prop('disabled', false);
                                $('button[type="submit"]').text(old_text);
                                $('.live-text').html(data.html);
                                $('.login-form').remove();
                                $("#register-form")[0].reset();


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