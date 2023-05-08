@extends('layout.user')
@section('content')


<section class="full rfq-page ">
    <div class="container"> 
        <div class="create-rfq-form profile-info">
            <h2>Change Password</h2>
            <div class="profile-info-inner">

                <div class="profile-list-ch">
                    <form id="changepwd" action="{{route('user.update_password')}}" method="POST" class="form_submit_changepass">

                          <div class="form-group">
                            <label class="control-label" for="">Current Password:</label>

                            <div class="controls">
                                <input class="span12 form-control" id="current_password" required type="password" name="current_password" value="" placeholder="Current Password">
                            </div>
                        </div>  

                        <div class="form-group">
                            <label class="control-label" for="">New Password:</label>

                            <div class="controls">
                                <input class="span12 form-control" id="password" required type="password" name="password" value="" placeholder="New Password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="">Confirm Password:</label>

                            <div class="controls">
                                <input class="span12 form-control" id="confirm_password" required type="password" name="confirm_password" value="" placeholder="Confirm New Password">
                            </div>
                        </div>
 

                        <div class="form-group">
                            <button id="change_pwd_btn" type="submit" class="btn btn-blue w-100 mt-4  submit_btn_changepass">Save</button>
                        </div>
                    </form>
                </div>
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