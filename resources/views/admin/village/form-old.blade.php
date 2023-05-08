
<div class="modal-header model_loader">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{{ Form::open(['url' => route('village.manage'), 'class' => 'form_submit_testimonial', 'method'=>'post', 'files' => 'yes']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Country Name')}}*</label>
                    <select name="country_id" class="form-control country_id">
                       <option >Select Country</option>
                      
                        @foreach ($country as  $value)
                    <?php 
                          $select_chk=" ";
                          if(isset($value->id) && isset($data->cid)){
                                 if($value->id==$data->cid){
                                     $select_chk="selected";
                                 }
                          }
                         
                    ?>
                            <option value="{{ $value->id }}" {{$select_chk}}>{{ $value->name.'('.$value->country_code.')' }}</option>
                        @endforeach
                       
                    </select>

                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label ">{{__('State Name')}}*</label>
                   
                    <select name="state_id" class="form-control state_id">
                    <option >Select State</option>
                    @if(isset($data->id)?true:false)
                    @foreach ($state as  $value)
                    <?php 
                          $select_chk=" ";
                          if(isset($value->id) && isset($data->sid)){
                                 if($value->id==$data->sid){
                                     $select_chk="selected";
                                 }
                          }
                         
                    ?>
                            <option value="{{ $value->id }}" {{$select_chk}}>{{ $value->name }}</option>
                    @endforeach
                    @endif


                      
                    </select>

                </div>


                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('District Name')}}*</label>
                 
                    <select name="district_id" class="form-control district_id">
                    <option >Select District</option>
                    @if(isset($data->id)?true:false)
                    @foreach ($district as  $value)
                    <?php 
                          $select_chk11=" ";
                          if(isset($value->id) && isset($data->did)){
                                 if($value->id==$data->did){
                                     $select_chk11="selected";
                                 }
                          }
                         
                    ?>
                            <option value="{{ $value->id }}" {{$select_chk11}}>{{ $value->name }}</option>
                    @endforeach
                    @endif

                    </select>

                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Tehsils Name')}}*</label>
                    
                    <select name="tehsil_id" class="form-control tehsil_id">
                    <option >Select Tehsil</option>
                    @if(isset($data->id)?true:false)
                    @foreach ($tehsil as  $value)
                    <?php 
                          $select_chk=" ";
                          if(isset($value->id) && isset($data->tid)){
                                 if($value->id==$data->tid){
                                     $select_chk="selected";
                                 }
                          }
                         
                    ?>
                            <option value="{{ $value->id }}" {{$select_chk}}>{{ $value->name }}</option>
                    @endforeach
                    @endif

                    </select>

                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Panchayat Name')}}*</label>
                    <select name="panchayat_id" class="form-control panchayat_id">
                       <option >Select Panchayat</option>
                       @if(isset($data->id)?true:false)
                       @foreach ($panchayat as  $value)
                    <?php 
                          $select_chk=" ";
                          if(isset($value->id) && isset($data->pid)){
                                 if($value->id==$data->pid){
                                     $select_chk="selected";
                                 }
                          }
                         
                    ?>
                            <option value="{{ $value->id }}" {{$select_chk}}>{{ $value->name }}</option>
                    @endforeach
                    @endif

                    </select>

                </div>



               
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Village Name')}}*</label>
                    {{ Form::text('village_name',$data->name ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Village Name', 'required' => true]) }}
                </div>

                <div class="col-md-12 mb-3">  
                    <label for="nameBackdrop" class="form-label">{{__('Village History')}}*</label>
                     {{ Form::textarea('village_history', $data->village_history ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Village Name', 'required' => true]) }}

                </div>


               



            </div>
                {!! Form::hidden('id', $data->id ?? '') !!}

               
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Close
    </button>
    <button type="submit" class="btn btn-primary submit_btn_testimonial"> Submit </button>
</div>
{{ Form::close() }}

<script type="text/javascript">

$.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value != param;
}, " ");

$('.form_submit_testimonial').validate({
    rules: {

        state_id: {
        required: true,
        notEqual: "Select State"
      },

      country_id: {
        required: true,
        notEqual: "Select Country"
      },

      district_id: {
        required: true,
        notEqual: "Select District"
      },
      tehsil_id: {
        required: true,
        notEqual: "Select Tehsil"
      },
      panchayat_id:{
        required: true,
        notEqual: "Select Panchayat"

      }


        
    },
    messages: {

        state_id: {
        required: "Please select a state",
        notEqual: "Please select a state"
      },
      country_id: {
        required: "Please select a country",
        notEqual: "Please select a country"
      },
      district_id: {
        required: "Please select a district",
        notEqual: "Please select a district"
      },
      tehsil_id: {
        required: "Please select a tehsil",
        notEqual: "Please select a tehsil"
      },

      panchayat_id:{
        required: "Please select a Panchayat",
        notEqual: "Please select a Panchayat"

      }

         
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
        var button = $('.submit_btn_testimonial');
        // $(".form_submit_testimonial").unbind().submit(function (event) {
        //     event.preventDefault(); //prevent default action 
        var form_all = $(".form_submit_testimonial")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_testimonial').text();

            // if ($('.form_submit_testimonial').valid()) {
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
                            $("#fileControl").val('');
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


<script type="text/javascript">

$(document).ready(function () {

    //Get State by country
    $('.country_id').change(function () {
        $('.state_id').empty();
        $('.state_id').append('<option value="">Select State</option>');
        var countryId = $(this).val();
        $.ajax({
            type: "GET",
            url: "{{ route('districts.states-by-country') }}",
            data: { country_id: countryId },
            dataType: "json",
            success: function (states) {
                $('.state_id').empty();
                $('.state_id').append('<option value="">Select State</option>');
                $.each(states, function (key, value) {
                    $('.state_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });
    });



   //Get districts by state
   $('.state_id').change(function () {
                $('.district_id').empty();
                $('.district_id').append('<option value="">Select District</option>');
                var stateId = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('tehsils.districts-by-state') }}",
                    data: { state_id: stateId },
                    dataType: "json",
                    success: function (states) {
                        $('.district_id').empty();
                        $('.district_id').append('<option value="">Select District</option>');
                        $.each(states, function (key, value) {
                            $('.district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
});


   //Get tehsils by district
   $('.district_id').change(function () {
                $('.tehsil_id').empty();
                $('.tehsil_id').append('<option value="">Select City/Tehsil</option>');
                var district_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('village.tehsil-by-districts') }}",
                    data: { district_id: district_id },
                    dataType: "json",
                    success: function (states) {
                        $('.tehsil_id').empty();
                        $('.tehsil_id').append('<option value="">Select Tehsil</option>');
                        $.each(states, function (key, value) {
                            $('.tehsil_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
});



   //Get tehsils by district
   $('.tehsil_id').change(function () {
                $('.panchayat_id').empty();
                $('.panchayat_id').append('<option value="">Select Panchayat </option>');
                var tehsil_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('village.panchayat-by-tehsil') }}",
                    data: { tehsil_id: tehsil_id },
                    dataType: "json",
                    success: function (states) {
                        $('.panchayat_id').empty();
                        $('.panchayat_id').append('<option value="">Select Panchayat</option>');
                        $.each(states, function (key, value) {
                            $('.panchayat_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
});



});

</script>

