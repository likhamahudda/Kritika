@extends('layout.admin')
@section('content')
<div class="d-flex justify-content-between">
    <div class="modal-header model_loader">
        <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
        <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
    </div>
    <div class="ms-auto">
        <div class="ms-auto"> {!! backAction(route('admin.index') , 'Back') !!} </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-12">
                
                                {{ Form::open(['url' => route('profile.update'), 'class' => 'form_submit_profile', 'method'=>'post' , 'files' => 'yes']) }}
                                {!! Form::hidden('id', $data->id ?? '') !!}

                                
                                <div class="row">
                                    <label for="nameBackdrop" class="form-label">{{__('Profile Image(1000X600)')}}*</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                            <label for="imageUpload" style="font-size: 23px;"> <i class="fadeIn animated bx bx-edit-alt"></i> </label>
                                        </div>
                                        <div class="avatar-preview" style="border-radius: 0;">
                                            @if(isset($data->image))
                                            <div id="imagePreview" style="background-image: url('{{url('uploads')}}/admin_profile/{{$data->image}}'); border-radius: 0px; ">
                                            </div>
                                            @else
                                            <div id="imagePreview" style="background-image: url({{URL::asset('admin/images/default_user.png')}}); border-radius: 0px;">
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="row">


                                    <div class="col mb-3">
                                        <label for="nameBackdrop" class="form-label">{{__('First Name')}}*</label>
                                        {{ Form::text('first_name',$data->first_name ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter First Name', 'required' => true]) }}
                                    </div>
                                    <div class="col mb-3">
                                        <label for="nameBackdrop" class="form-label">{{__('Last Name')}}*</label>
                                        {{ Form::text('last_name',$data->last_name ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Last Name', 'required' => true]) }}
                                    </div>
                                </div>







                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="nameBackdrop" class="form-label">{{__('Mobile')}}*</label>
                                        {{ Form::number('mobile',$data->mobile ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Mobile Number', 'required' => true]) }}
                                    </div>
                                    <div class="col mb-3">
                                        <label for="nameBackdrop" class="form-label">{{__('Email')}}*</label>
                                        {{ Form::email('email',$data->email ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Email', 'required' => true]) }}
                                    </div>
                                </div>

                                
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="nameBackdrop" class="form-label">{{__('Address')}}*</label>
                                        {{ Form::text('address',$data->address ?? '' ,['class' =>'form-control', 'placeholder' =>'Address', 'required' => true]) }}
                                    </div>

                                    <div class="col mb-3">
                                        <label for="nameBackdrop" class="form-label">{{__('Zip Code')}}*</label>
                                        {{ Form::text('zip_code',$data->zip_code ?? '' ,['class' =>'form-control', 'placeholder' =>'Zip Code', 'required' => true]) }}
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary submit_btn_profile"> Submit </button>
                                {{ Form::close() }}
                            
            </div>
        </div>
    </div>
</div>
<script>
    //image picker and preview 
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });

$('.form_submit_profile').validate({
    rules: {
        mobile : {
            maxlength: 12,
            minlength: 6
        },
    },
    messages: {
       mobile : {
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
        var button = $('.submit_btn_profile');
        // $(".form_submit_profile").unbind().submit(function (event) {
        //     event.preventDefault(); //prevent default action 
        var form_all = $(".form_submit_profile")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_profile').text();

            // if ($('.form_submit_profile').valid()) {
            //     $(button).prop('disabled', true);
            //     $(button).text('Please wait...');
            //     //model loader add 
            //     $('#model_loader').removeClass('model_loader_class');
            //     $('#model_loader').addClass('loader_image_heder');

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
                            $('#admin_profile').attr('src',data.src);
                            $('.user-info .user-name').text(data.name);
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