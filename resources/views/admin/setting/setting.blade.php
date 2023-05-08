@extends('layout.admin')
@section('content')

<div class="modal-header model_loader">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
   <!--  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
</div>
{{ Form::open(['url' => route('admin.update.setting'), 'class' => 'form_submit_setting', 'method'=>'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <h6>Company Details</h6><br>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Name')}}*</label>
                    {{ Form::text('company_name',$array['company_name'] ,['class' =>'form-control', 'placeholder' =>'Enter company name', 'required' => true]) }}
                </div>
                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Email')}}*</label>
                    {{ Form::email('company_email',$array['company_email'] ,['class' =>'form-control', 'placeholder' =>'Enter company email', 'required' => true]) }}
                </div>
                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Phone No.')}}*</label>
                    {{ Form::number('phone_no',$array['phone_no'] ,['class' =>'form-control', 'placeholder' =>'Enter company phone no', 'required' => true]) }}
                </div>
                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Address')}}*</label>
                    {{ Form::text('address',$array['address'] ,['class' =>'form-control', 'placeholder' =>'Enter company address', 'required' => true]) }}
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Logo')}}*</label>
                    {{ Form::file('logo' ,['class' =>'form-control', 'id' => 'fileControl', 'accept' =>'.png, .jpg, .jpeg']) }}
                </div>
                <div class="col-md-4 mb-3">
                    <img id="imagePreview" src="{{getSettingValue('logo') ? url('uploads/logo').'/'.getSettingValue('logo') : URL::asset('images/logo.png')}}" width="70" alt="logo icon">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <h6>Social Media Links</h6><br>
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Twitter Link')}}*</label>
                    {{ Form::text('twitter_link',$array['twitter_link'] ,['class' =>'form-control', 'placeholder' =>'Enter twitter link', 'required' => true]) }}
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('LinkedIn Link')}}*</label>
                    {{ Form::text('linkedin_link',$array['linkedin_link'] ,['class' =>'form-control', 'placeholder' =>'Enter linkedin link', 'required' => true]) }}
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('FaceBook Link')}}*</label>
                    {{ Form::text('facebook_link',$array['facebook_link'] ,['class' =>'form-control', 'placeholder' =>'Enter facebook link', 'required' => true]) }}
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('YouTube Link')}}*</label>
                    {{ Form::text('youtube_link',$array['youtube_link'] ,['class' =>'form-control', 'placeholder' =>'Enter youtube_link', 'required' => true]) }}
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <h6>SMTP Config</h6><br>
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Hostname')}}*</label>
                    {{ Form::text('smtp_host',$array['smtp_host'] ,['class' =>'form-control', 'placeholder' =>'SMTP Host Name', 'required' => true]) }}
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Username')}}*</label>
                    {{ Form::text('smtp_username',$array['smtp_username'] ,['class' =>'form-control', 'placeholder' =>'SMTP User Name', 'required' => true]) }}
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Password')}}*</label>
                    <input class="form-control" value="{{$array['smtp_username']}}" name="smtp_password" type="password" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Port')}}*</label>
                    {{ Form::text('smtp_port',$array['smtp_port'] ,['class' =>'form-control', 'placeholder' =>'SMTP Port', 'required' => true]) }}
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('no-reply mail')}}*</label>
                    {{ Form::text('smtp_noreply',$array['smtp_noreply'] ,['class' =>'form-control', 'placeholder' =>'no-reply mail', 'required' => true]) }}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <h6>Google Recaptch Config</h6><br>
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Site Key')}}*</label>
                    {{ Form::text('site_key',$array['site_key'] ,['class' =>'form-control', 'placeholder' =>'Enter google recaptch site key', 'required' => true]) }}
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Secret Key')}}*</label>
                    {{ Form::text('secret_key',$array['secret_key'] ,['class' =>'form-control', 'placeholder' =>'Enter google recaptch secret key', 'required' => true]) }}
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <h6>Tutorial videos Management</h6><br>
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Client Testimonials')}}*</label>
                    <div class="input-group">
                        {{ Form::text('client_testimonials_url',$array['client_testimonials_url'] ,['class' =>'form-control', 'placeholder' =>'Enter client testimonials video url', 'required' => true]) }}
                        <span class="input-group-text" id="basic-addon2"><a target="_blank" href="{{$array['client_testimonials_url']}}" title="View"><i class="bx bx-show"></i></a> </span>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('How it works')}}*</label>
                    <div class="input-group">
                        {{ Form::text('how_it_works_url',$array['how_it_works_url'] ,['class' =>'form-control', 'placeholder' =>'Enter how it works video url', 'required' => true]) }}
                        <span class="input-group-text" id="basic-addon2"><a target="_blank" href="{{$array['how_it_works_url']}}" title="View"><i class="bx bx-show"></i></a> </span>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <h6>Renewal Settings</h6><br>
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Trial Expiry Days')}}*</label>
                    <div class="input-group">
                        {{ Form::number('expiry_days',$array['expiry_days'] ,['class' =>'form-control', 'min'=>'1', 'placeholder' =>'0', 'required' => true]) }}
                        <span class="input-group-text" id="basic-addon2">Days</span>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Notification sent to user for renewal')}}*</label>
                    <div class="input-group">
                        {{ Form::number('nft_send_days',$array['nft_send_days'] ,['class' =>'form-control', 'min'=>'1', 'placeholder' =>'0', 'required' => true]) }}
                        <span class="input-group-text" id="basic-addon2">Days</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <h6>Invitation Settings</h6><br>
            <div class="row">

                <div class="col-md-8 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Invitation Message')}}*</label>
                        {{ Form::textarea('invitation_msg',$array['invitation_msg'] ,['class' =>'form-control', 'placeholder' =>'Invitation Message','rows' => '3', 'required' => true]) }}
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <!-- <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Close
    </button> -->
    <button type="submit" class="btn btn-primary submit_btn_setting"> Submit </button>
</div>
{{ Form::close() }}

<script type="text/javascript">
    //image picker and preview 
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#fileControl").change(function() {
        readURL(this);
    });

$.validator.addMethod("yt_url", function(value, element) {
    if (value != undefined || value != '') {
            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
            var match = value.match(regExp);
            if (match && match[2].length == 11) {
                return true;
            }
            else {
                return false;
            }
        }
}, 'Please enter a valid youtube url');

$.validator.addMethod("extension", function (value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, "Only .jpg, .png & .jpeg file types are allowed.");

$('.form_submit_setting').validate({
    rules: {
        client_testimonials_url: {
            url : true
        },
        how_it_works_url: {
            url : true
        },
        phone_no : {
            maxlength: 12,
            minlength: 6
        },
        logo : {
            extension : true
        }
    },
    messages: {
       phone_no : {
            maxlength: 'Please enter no more than 12 digits.',
            minlength: 'Please enter at least 6 digits.'
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
        var button = $('.submit_btn_setting');
        // $(".form_submit_setting").unbind().submit(function (event) {
        //     event.preventDefault(); //prevent default action 
         var form_all = $(".form_submit_setting")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_setting').text();

            //if ($('.form_submit_setting').valid()) {
                // $(button).prop('disabled', true);
                // $(button).text('Please wait...');
                // //model loader add 
                // $('#model_loader').removeClass('model_loader_class');
                // $('#model_loader').addClass('loader_image_heder');

                $.ajax({
                    url: $(form_all).attr("action"),
                    type: $(form_all).attr("method"),
                    data: formData,
                    contentType: false, //this is requireded please see answers above
                    processData: false,
                    beforeSend: function(){
                        $(button).prop('disabled', true);
                        $(button).text('Please wait...');
                        //model loader add 
                        $('#model_loader').removeClass('model_loader_class');
                        $('#model_loader').addClass('loader_image_heder');
                    },
                    success: function (data) {
                        if (data.status === true) {
                            if (typeof (data.redirect_url) != "undefined" && data.redirect_url !== null) {
                                window.location.replace(data.redirect_url);
                            }
                            $('#model_loader').addClass('model_loader_class');
                            $('#model_loader').removeClass('loader_image_heder');
                            toastr.success(data.message);
                            $('#manage_data_model').modal('hide');
                            //console.log($('#data_table').length);
                            if($('#data_table').length>0){
                            $('#data_table').DataTable().ajax.reload(); }
                            $(button).prop('disabled', false);
                            $(button).text(old_text);
                            $("#fileControl").val('');
                            $('#site_logo').attr('src',data.src);
                        } else {
                            toastr.error(data.message);
                            $(button).prop('disabled', false);
                            $(button).text(old_text);
                            $('#model_loader').addClass('model_loader_class');
                            $('#model_loader').removeClass('loader_image_heder');
                            $(button).prop('disabled', false);
                            $(button).text(old_text);
                        }
                    },
                    error: function (e) {
                        $(button).prop('disabled', false);
                        $(button).text(old_text);
                        $('#model_loader').addClass('model_loader_class');
                        $('#model_loader').removeClass('loader_image_heder');
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