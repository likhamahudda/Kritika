@extends('layout.admin_auth')
<script src='https://www.google.com/recaptcha/api.js' async defer ></script>
@section('content')
<div class="container-fluid">
    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
        <div class="col mx-auto">
            <!-- <div class="mb-4 text-center">
                <img src="{{URL::asset('images/logo.png')}}" width="180" alt="" />
            </div> -->
            <div class="card">
                <div class="card-body">
                    <div class="border p-4 rounded"> 
                        <div class="text-center">
                            <h4 class="mb-2">Welcome to {{Constant::APP_NAME}}! </h4>
                            <p>Please sign-in to your account...
                            </p>
                        </div>
                        <div class="d-grid">
                        </div>
                        <div class="login-separater text-center mb-4"> <span>OR SIGN IN WITH EMAIL</span>
                            <hr />
                        </div>
                        <div class="form-body">
                            <!-- <form class="row g-3"> -->
                            {{ Form::open(['url' => route('admin.do_login'), 'class' => 'form_submit', 'method'=>'Post']) }}
                            <div class="col-12">
                                <label for="inputEmailAddress" class="form-label">Email Address</label>
                                {{ Form::email('email', $user['email'] ,['class' =>'form-control', 'placeholder' =>'Enter Email', 'required'=>true ]) }}
                            </div>
                            <div class="col-12 mt-2">
                                <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                <div class="input-group" id="show_hide_password">
                                    <input class="form-control border-end-0" placeholder="Password" id="inputChoosePassword" required="" name="password" type="password" value="{{$user['password']}}" aria-required="true" aria-invalid="false"><a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                              <div class="g-recaptcha" data-sitekey="{{getSettingValue('site_key')}}"></div>
                            </div>
                            
                            <div class="row mt-2 mb-4">
                                <div class="col-md-7">

                                    <input type="checkbox" @if($user['remember_me']==1) checked @endif value="1" name="remember_me">
                                    <label class="form-check-label" for="flexSwitchCheckChecked"> Remember me </label>
                                </div>

                                <div class="col-md-5 text-end"> <a href="{{route('forget.password')}}">Forgot Password ?</a>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
                                </div>
                            </div>
                            <!-- </form> -->
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
@endsection

@push('scripts')
<!-- <script src="{{URL::asset('admin/js/jquery.min.js')}}"></script> -->
<script src="{{URL::asset('commonFile/js/validate.js')}}"></script>

    <script src="{{URL::asset('commonFile/js/toastr.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
        
    });
</script>
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
                if ("" == grecaptcha.getResponse()) return $(".g-recaptcha iframe").css("border", "1px solid red"), !1;
                        $(".g-recaptcha iframe").css("border", "1px solid #dadada");
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
                                if (data.redirect_url != '') {
                                    window.location.replace(data.redirect_url);
                                }
                                toastr.success(data.message);
                                $('button[type="submit"]').prop('disabled', false);
                                $('button[type="submit"]').text(old_text);

                            } else {
                                grecaptcha.reset();
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