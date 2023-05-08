@extends('layout.user')
@section('content')


<section class="full rfq-page ">
    <div class="container">

        <div class="create-rfq-form profile-info">
            <h2>Profile Update</h2>

            <div class="profile-info-inner">



                <form name="editprofile" action="{{route('user.updateProfile')}}" method="POST" class="form_submit_profile" id="editprofileform">

                    <div class="pro-update">
                        <div class="profile-img">
                            @if($user->image)
                            <img src="{{url('uploads/profile_img/')}}/{{$user->image}}" alt="">
                            @else
                            <img src="{{url('images/dummy-user.png')}}" alt="">
                            @endif
                        </div>
                        <div class="input--file">
                            <i class="fa-solid fa-camera"></i>
                            <input name="image" type="file" />
                        </div>
                    </div>
                    <div class="profile-list">

                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" type="text" name="first_name" required value="{{$user->first_name}}" placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="form-control" readonly type="text" name="email" required value="{{$user->email}}" placeholder="Eamil">
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="number" minlength="6" maxlength="12" class="form-control" type="text" required name="mobile" value="{{$user->mobile}}" placeholder="Phone Number">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-blue w-100 mt-4">Update</button>
                        </div>

                    </div>

                </form>


            </div>


        </div>


    </div>
</section>


<script type="text/javascript">
    jQuery.validator.addMethod("alpha", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /^[A-Za-z]+$/.test(value);
    }, 'Only alphabets are allowed.');
    $('.form_submit_profile').validate({

        rules: {
            first_name: {
                required: true,
            },
            mobile: {
                required: true,
            }
        },
        messages: {

        },

        submitHandler: function(form) {
            var button = $('.submit_btn_profile');
            var form_all = $(".form_submit_profile")[0];
            var formData = new FormData(form_all);
            var old_text = $(button).closest('.submit_btn_profile').text();

            $.ajax({
                url: $(form_all).attr("action"),
                type: $(form_all).attr("method"),
                data: formData,
                contentType: false, //this is requireded please see answers above
                processData: false,
                beforeSend: function() {
                    $(button).prop('disabled', true);
                    $(button).text('Please wait...');
                    //model loader add 
                },
                success: function(data) {
                    if (data.status === true) {
                        $('.update_name_submit').text(data.name);
                        $('.update_date').text(data.date);
                        toastr.success(data.message);

                        $(button).prop('disabled', false);
                        $(button).text(old_text);
                        location.reload();
                    } else {

                        toastr.error(data.message);
                        $(button).prop('disabled', false);
                        $(button).text(old_text);
                    }
                },
                error: function(e) {
                    $(button).prop('disabled', false);
                    $(button).text(old_text);
                    toastr.error(e.responseJSON.message);
                }
            });
            //}
            //});
        }
    });
    $('.form_submit_changepass').validate({

        rules: {
            password: {
                required: true,
                minlength: 8,
                maxlength: 12,
            },
            confirm_password: {
                equalTo: '#password'
            }
        },
        messages: {

        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-control').parent().append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        /*  submitHandler: function (form) {
               $('.loader').show();
         $( "#event_add" ).submit();
               form.submit();

           } */
        submitHandler: function(form) {
            var button = $('.submit_btn_changepass');
            var form_all = $(".form_submit_changepass")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_changepass').text();

            $.ajax({
                url: $(form_all).attr("action"),
                type: $(form_all).attr("method"),
                data: formData,
                contentType: false, //this is requireded please see answers above
                processData: false,
                beforeSend: function() {
                    $(button).prop('disabled', true);
                    $(button).text('Please wait...');
                    //model loader add 
                },
                success: function(data) {
                    if (data.status === true) {
                        $('.update_name_submit').text(data.name);

                        toastr.success(data.message);
                        $(".form_submit_changepass")[0].reset();
                        $(button).prop('disabled', false);
                        $(button).text(old_text);
                    } else {
                        toastr.error(data.message);
                        $(button).prop('disabled', false);
                        $(button).text(old_text);
                    }
                },
                error: function(e) {
                    $(button).prop('disabled', false);
                    $(button).text(old_text);
                    toastr.error(e.responseJSON.message);
                }
            });
            //}
            //});
        }
    });
</script>

@endsection