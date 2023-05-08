 
{{ Form::open(['url' => route('families.manage'), 'class' => 'form_submit_user', 'method'=>'post', 'files' => 'yes']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Head of Family')}}*</label>
                    {{ Form::text('head_family',$data->head_family ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Head of Family', 'required' => true]) }}
                </div> 

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Head Father Name')}}*</label>
                    {{ Form::text('head_father_name',$data->head_father_name ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Head Father Name', 'required' => true]) }}
                </div> 

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Sub Caste(Gotra)')}}*</label>
                    {{ Form::text('sub_caste',$data->sub_caste ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Sub Caste(Gotra)', 'required' => true]) }}
                </div>


                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Kulrishi')}}</label>
                    {{ Form::select('kulrishi',array('Uddalak Ji'=>'Uddalak Ji (उद्दालक जी)','Vasishtha Ji'=>'Vasishtha Ji (वशिष्ठ जी)','Kashyapa Ji'=>'Kashyapa Ji (कश्यप जी)','Parasara Ji'=>'Parasara Ji (पारासर जी)','Piplad Ji'=>'Piplad Ji (पिप्लाद जी)','Shandilya Ji'=>'Shandilya Ji (शांडिल्य जी)','Bharadwaj Ji'=>'Bharadwaj Ji (भारद्वाज जी)'),$data->kulrishi ?? '',['class' =>'form-control', 'placeholder' =>'Select Kulrishi', 'required' => true]) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Kuldevi')}}</label>
                    {{ Form::text('kildevi',$data->kildevi ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Kuldevi']) }}
                </div>
 
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Gender')}}*</label>
                    {{ Form::select('gender',array('male'=>'Male','female'=>'Female'),$data->gender ?? '',['class' =>'form-control', 'placeholder' =>'Select Gender', 'required' => true]) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Country')}}*</label>
                    <select required name="country_id" class="form-control country_id">
                        <option value="">Select Country</option>
                        @foreach($data_country as $row)
                            <option @if(!empty($data)) @if($row->id==$data->country_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div> 

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('State')}}*</label>
                    <select required name="state_id" class="form-control state_id">
                        <option value="">Select State</option> 
                        @foreach($data_state as $row)
                            <option @if(!empty($data)) @if($row->id==$data->state_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('District')}}*</label>
                    <select required name="district_id" class="form-control district_id">
                        <option value="">Select District</option>  
                        @foreach($data_district as $row)
                            <option @if(!empty($data)) @if($row->id==$data->district_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div> 

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('City/Tehsil')}}*</label>
                    <select required name="tehsil_id" class="form-control tehsil_id">
                        <option value="">Select City/Tehsil</option> 
                        @foreach($data_tehsils as $row)
                            <option @if(!empty($data)) @if($row->id==$data->tehsil_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Gram panchayat')}}*</label>
                    <select required name="panchayat_id" class="form-control panchayat_id">
                        <option value="">Select Gram panchayat</option> 
                        @foreach($data_panchayat as $row)
                            <option @if(!empty($data)) @if($row->id==$data->panchayat_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Village Name')}}*</label>
                    <select required name="village_id" class="form-control village_id">
                        <option value="">Select Village Name</option> 
                        @foreach($data_village as $row)
                            <option @if(!empty($data)) @if($row->id==$data->village_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                       
                    </select>
                </div>
             

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('House in The Village')}}</label>
                    {{ Form::text('house_village',$data->house_village ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter House in The Village', 'required' => true]) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Panchayat Committee')}}*</label>
                    {{ Form::text('panchayat_committee',$data->panchayat_committee ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Panchayat Committee', 'required' => true]) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Pin Code')}}*</label>
                    {{ Form::text('pin_code',$data->pin_code ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Pin Code', 'required' => true]) }}
                </div>

 
         

        <div class=" add_here_features ">

        <h5>List Member</h5>
        
        @if(isset($data_familyMember) && !empty($data_familyMember))

        @foreach ($data_familyMember as $i => $data_f)

        <div class="list_member_sec row">
        <div class="col-md-11">
        <div class="row">
                
                <div class="col-md-2"> 
                     <div class="form-group mb-3">
                     <!-- <label for="nameBackdrop" class="form-label">{{__('Name')}}* &emsp; -->
                        {{ Form::text('member_name[]',$data_f->member_name ?? '' ,['class' =>'form-control name_count', 'placeholder' =>'Name',  'required' => true]) }}
                     </div>
                </div>
               

                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Relation Select')}}* &emsp; -->
                     <div class="form-group mb-3">
                     {{ Form::select('relation_type[]',array('son_of'=>'Son Of','wife_of'=>'Wife Of','daughter_of'=>'Daughter Of'),$data_f->relation_type ?? '',['class' =>'form-control', 'placeholder' =>'Select Relation', 'required' => true]) }}
                     </div>
                </div>


                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Relation With')}}* &emsp; -->
                     <div class="form-group mb-3">
                        {{ Form::text('relation_name_with[]',$data_f->relation_name_with ?? '' ,['class' =>'form-control relation_name_with_count', 'placeholder' =>'Relation With',  'required' => true]) }}
                    </div>
                </div>

                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Education')}}  -->
                     <div class="form-group mb-3">
                         <select id="education" name="education[]" class="form-control">
                         <option value="">Select Education</option>
                         @foreach($data_education as $row)
                            <option @if(!empty($data_f)) @if($row->id==$data_f->education) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Occupation')}}  -->
                     <div class="form-group mb-3">
                     <select required name="occupation[]" class="form-control">
                     <option value="">Select Occupation</option>
                     @foreach($data_occupations as $row)
                            <option @if(!empty($data_f)) @if($row->id==$data_f->occupation) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                     </div>
                </div>

                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Gender')}}* &emsp; -->
                     <div class="form-group mb-3">
                     {{ Form::select('gender_type[]',array('male'=>'Male','female'=>'Female'),$data_f->gender_type ?? '',['class' =>'form-control', 'placeholder' =>'Select Gender', 'required' => true]) }}
                     </div>
                </div>

                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Phone Number')}}  -->
                     <div class="form-group mb-3">
                        {{ Form::text('phone_no[]',$data_f->phone_no ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Phone Number']) }}
                    </div>
                </div>

                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Email')}}  -->
                     <div class="form-group mb-3">
                        {{ Form::text('email_address[]',$data_f->email_address ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Email']) }}
                    </div>
                </div>

                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Married Status')}}* &emsp; -->
                     <div class="form-group mb-3">
                     {{ Form::select('married_status[]',array('married'=>'Married','unmarried'=>'UnMarried'),$data_f->married_status ?? '',['class' =>'form-control', 'placeholder' =>'Select Married Status', 'required' => true]) }}
                    </div>
                </div>


                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('D.O.B')}}* &emsp; -->
                     <div class="form-group mb-3">
                        {{ Form::text('dob[]',$data_f->dob ?? '' ,['class' =>'form-control select_dob', 'placeholder' =>'D.O.B',  'required' => true]) }}
                    </div>
                </div> 


                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Age(Years)')}}* &emsp; -->
                     <div class="form-group mb-3">
                        {{ Form::text('age[]',$data_f->age ?? '' ,['class' =>'form-control dob_age', 'placeholder' =>'Age',   'required' => true]) }}
                    </div>
                </div>

                <div class="col-md-2">
                    <!-- <label for="nameBackdrop" class="form-label">{{__('Current Address')}}* &emsp; -->
                     <div class="form-group mb-3">
                        {{ Form::text('address[]',$data_f->address ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Current Address', 'required' => true]) }}
                    </div>
                </div>
            

             
             
                 </div>
                 </div>

                 <div class="col-md-1 row justify-content-center align-items-end"> 
                @if ($i != 0)
                <div class="col-md-12">
                    <div class=" mb-3">
                   
                    <button type="button" class="btn btn-danger remove-sec" title="Remove" onclick="remove_features($(this),'url');" url="{{ customeRoute('families.delete_feature', ['id' => $data_f->id]) }}"><i class="bx bx-minus"> </i> </button> 
                  
                       
                    </div>
                 </div>
                 @endif 
                 
                 @if ($i == 0)
                <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-primary add_more_features" title="Add More Features"> 
                <i class="bx bx-plus"> </i> </button>
                </div>
                @endif 
                </div>

                 </div>
                 <hr>
        
        @endforeach
        @endif
       
       
        </div> 
       
       {!! Form::hidden('id', $data->id ?? '') !!}

               
        </div>

         
       

    </div>
</div>
<div class="modal-footer">
   <button type="submit" class="btn btn-primary submit_btn_user"> Submit </button>
</div>
{{ Form::close() }} 

<script type="text/javascript">

 

$(function() {
  $(".select_dob").datepicker({
    dateFormat: "dd-mm-yy",
    maxDate: 0,
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
    onSelect: function(dateText, inst) {
      var age = calculateAge(convertDateFormat(dateText));
      if (age > 0) {
        $(this).closest('.col-md-2').next('.col-md-2').find(".dob_age").val(age);
      } else {
        $(this).closest('.col-md-2').next('.col-md-2').find(".dob_age").val("0");
      }
    }
  });
});
 


function convertDateFormat(dateString) {
  if (!dateString || typeof dateString !== "string") {
    return "Invalid date string";
  }

  var dateParts = dateString.split("-");
  var day = dateParts[0];
  var month = dateParts[1];
  var year = dateParts[2].slice(-2); // extract last two digits of the year

  return month + "-" + day + "-" + year;
}

$(".dob_age").on("change", function() {
  var age = $(this).val();
  var currentDate = new Date();
  var birthYear = currentDate.getFullYear() - age;
  var birthDate = new Date(birthYear, 0, 1);
  
  $(this).closest('.col-md-2').prev('.col-md-2').find(".select_dob").datepicker("setDate", birthDate);
});
 


function calculateAge(birthDateString) {
  var birthDate = new Date(Date.parse(birthDateString));
  var today = new Date();
  var age = today.getFullYear() - birthDate.getFullYear();
  var birthMonth = birthDate.getMonth();
  var birthDay = birthDate.getDate();
  var currentMonth = today.getMonth();
  var currentDay = today.getDate();

  if (currentMonth < birthMonth || (currentMonth === birthMonth && currentDay < birthDay)) {
    age--;
  }

  return age;
}

 

$.validator.addMethod("alpha", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
},'Only alphabets allowed.');
  
$('.form_submit_user').validate({
    debug: true, // enable debugging
    rules: {
        head_family : {
            required: true,
            alpha : true
        },
        head_father_name : {
            required: true,
            alpha : true
        },
        sub_caste : {
            required: true,
            alpha : true
        },
        kildevi : { 
            alpha : true
        },
        pin_code : {
            required: true,
            number: true
        },
        'member_name[]' : {
            required: true, 
            alpha : true
        },
        'relation_name_with[]' : {
            required: true, 
            alpha : true
        },
        'phone_no[]' : {
            number: true,
            minlength: 10, // minimum length of 10 digits
            maxlength: 12 // maximum length of 12 digits
        },
        'email_address[]' : {
            email: true
        },
        'age[]' : {
            number: true
        },
        'dob[]' : {
            required: true, 
        },
        'address[]' : {
            required: true, 
        },
        
      
    },
     
    submitHandler: function (form) {
        var button = $('.submit_btn_user'); 
        var form_all = $(".form_submit_user")[0];
            var formData = new FormData(form_all);
            var old_text = $(button).closest('.submit_btn_user').text(); 
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

                            toastr.success(data.message);
                            form_all.reset();

                            if (typeof (data.redirect_url) != "undefined" && data.redirect_url !== null) {
                                setTimeout(function(){
                                    window.location.replace(data.redirect_url);
                                }, 3000);
                               
                            }
                            $('#model_loader').addClass('model_loader_class');
                            $('#model_loader').removeClass('loader_image_heder');
                           
                            $('#manage_data_model').modal('hide');
                            //console.log($('#data_table').length);
                            if($('#data_table').length>0){
                            $('#data_table').DataTable().ajax.reload();
                            }
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

        $('.district_id').empty();
        $('.district_id').append('<option value="">Select District</option>');
        $('.tehsil_id').empty();
        $('.tehsil_id').append('<option value="">Select City/Tehsil</option>');
        $('.panchayat_id').empty();
        $('.panchayat_id').append('<option value="">Select Gram panchayat</option>');
        $('.village_id').empty();
        $('.village_id').append('<option value="">Select Village</option>');

        var countryId = $(this).val();
        $.ajax({
            type: "GET",
            url: "{{ route('families.states-by-country') }}",
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
 
        $('.tehsil_id').empty();
        $('.tehsil_id').append('<option value="">Select City/Tehsil</option>');
        $('.panchayat_id').empty();
        $('.panchayat_id').append('<option value="">Select Gram panchayat</option>');
        $('.village_id').empty();
        $('.village_id').append('<option value="">Select Village</option>');


       var stateId = $(this).val();
       $.ajax({
           type: "GET",
           url: "{{ route('families.districts-by-state') }}",
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
  

      //Get tehsils by districts
      $('.district_id').change(function () {

       
        $('.panchayat_id').empty();
        $('.panchayat_id').append('<option value="">Select Gram panchayat</option>');
        $('.village_id').empty();
        $('.village_id').append('<option value="">Select Village</option>');

       var district_id = $(this).val();
       $.ajax({
           type: "GET",
           url: "{{ route('families.tehsil-by-district') }}",
           data: { district_id: district_id },
           dataType: "json",
           success: function (states) {
               $('.tehsil_id').empty();
               $('.tehsil_id').append('<option value="">Select City/Tehsil</option>');
               $.each(states, function (key, value) {
                   $('.tehsil_id').append('<option value="' + value.id + '">' + value.name + '</option>');
               });
           }
       });
   });
 

   //Get panchayat_id by tehsils
       $('.tehsil_id').change(function () { 

        $('.village_id').empty();
        $('.village_id').append('<option value="">Select Village</option>');

       var tehsil_id = $(this).val();
       $.ajax({
           type: "GET",
           url: "{{ route('families.panchayat-by-tehsil') }}",
           data: { tehsil_id: tehsil_id },
           dataType: "json",
           success: function (states) {
               $('.panchayat_id').empty();
               $('.panchayat_id').append('<option value="">Select Gram Panchayat</option>');
               $.each(states, function (key, value) {
                   $('.panchayat_id').append('<option value="' + value.id + '">' + value.name + '</option>');
               });
           }
       });
   });
  

    //Get panchayat_id by tehsils
    $('.panchayat_id').change(function () {
       var panchayat_id = $(this).val();
       $.ajax({
           type: "GET",
           url: "{{ route('families.village-by-panchayat') }}",
           data: { panchayat_id: panchayat_id },
           dataType: "json",
           success: function (states) {
               $('.village_id').empty();
               $('.village_id').append('<option value="">Select Village</option>');
               $.each(states, function (key, value) {
                   $('.village_id').append('<option value="' + value.id + '">' + value.name + '</option>');
               });
           }
       });
   });


   
   
});
</script>

<script type="text/javascript">

var i = 0;
$('.add_more_features').on('click', function () {
     i = i+1;
     var html_add = '<div class="list_member_sec row">';
     html_add += '<hr>'; 

    html_add += '<div class="col-md-11"> '; 
    html_add += '<div class="row"> ';
     
    html_add += '<div class="col-md-2"> ';
    html_add += '<div class="form-group mb-3">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Name')}}* &emsp;';
    html_add += '{{ Form::text('member_name[]',$data_f['data_f1']->count ?? '' ,['class' =>'form-control name_count', 'placeholder' =>'Name',  'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Relation Select')}}* &emsp;';
    html_add += '<div class="form-group mb-3">';
    html_add += '{{ Form::select('relation_type[]',array('son_of'=>'Son Of','wife_of'=>'Wife Of','daughter_of'=>'Daughter Of'),$data->relation_type ?? '',['class' =>'form-control', 'placeholder' =>'Select Relation', 'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Relation With')}}* &emsp;';
    html_add += '<div class="form-group mb-3">';
    html_add += '{{ Form::text('relation_name_with[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control relation_name_with_count', 'placeholder' =>'Relation With',  'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Education')}} ';
    html_add += '<div class="form-group mb-3">';
    html_add += '<select id="education" name="education[]" class="form-control">';
    html_add += '<option value="">Select Education</option>';
    html_add += '@foreach($data_education as $row)';
    html_add += '<option @if(!empty($data)) @if($row->id==$data->category_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>';
    html_add += '@endforeach';
    html_add += '</select>';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Occupation')}} </label>';
    html_add += '<div class="form-group mb-3">';
    html_add += '<select required name="occupation[]" class="form-control">';
    html_add += '<option value="">Select Occupation</option>';
    @foreach($data_occupations as $row)
    html_add += '<option @if(!empty($data)) @if($row->id==$data->category_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>';
    @endforeach
    html_add += '</select>';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Gender')}}* &emsp;';
    html_add += '<div class="form-group mb-3">';
    html_add += '{{ Form::select('gender_type[]',array('male'=>'Male','female'=>'Female'),$data->gender ?? '',['class' =>'form-control', 'placeholder' =>'Select Gender', 'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Phone Number')}}';
    html_add += '<div class="form-group mb-3">';
    html_add += '{{ Form::text('phone_no[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Phone Number']) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Email')}}';
    html_add += '<div class="form-group mb-3">';
    html_add += '{{ Form::text('email_address[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Email']) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Married Status')}}* &emsp;';
    html_add += '<div class="form-group mb-3">';
    html_add += '{{ Form::select('married_status[]',array('married'=>'Married','unmarried'=>'UnMarried'),$data->married_status ?? '',['class' =>'form-control', 'placeholder' =>'Select Married Status', 'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';  
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('D.O.B')}}* &emsp;';
    html_add += '<div class="form-group mb-3">';
    html_add += '{{ Form::text('dob[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control select_dob', 'placeholder' =>'D.O.B',  'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>'; 

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Age')}}* &emsp;';
    html_add += '<div class="form-group mb-3">';
    html_add += '{{ Form::text('age[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control dob_age', 'placeholder' =>'Age',   'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-2">';
    // html_add += '<label for="nameBackdrop" class="form-label">{{__('Current Address')}}* &emsp;</label>';
    html_add += '<div class="form-group mb-3">';
    html_add += '{{ Form::text('address[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Current Address', 'required' => true]) }}';
    html_add += '</div></div>';

    html_add += '</div>'; // row
    html_add += '</div>'; // col-11

    html_add += '<div class="col-md-1 row justify-content-center align-items-end"> ';
     
    html_add += '<div class="col-md-12 mb-3"><button type="button" class="btn btn-danger remove-sec" title="Remove" onclick="remove_features($(this));"><i class="bx bx-minus"> </i> </button></div>';
    html_add += '</div>';
    html_add += '</div>';

     $('.add_here_features').append(html_add);

     $(".select_dob").datepicker({
        dateFormat: "dd-mm-yy",
        maxDate: 0,
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        onSelect: function(dateText, inst) {
        var age = calculateAge(convertDateFormat(dateText));
        if (age > 0) {
            $(this).closest('.col-md-2').next('.col-md-2').find(".dob_age").val(age);
        } else {
            $(this).closest('.col-md-2').next('.col-md-2').find(".dob_age").val("0");
        }
        }
  });

  $(".dob_age").on("change", function() {
  var age = $(this).val();
  var currentDate = new Date();
  var birthYear = currentDate.getFullYear() - age;
  var birthDate = new Date(birthYear, 0, 1);
  
  $(this).closest('.col-md-2').prev('.col-md-2').find(".select_dob").datepicker("setDate", birthDate);
});

});

function remove_features(div,url=null){
    $.confirm({
        title: 'Delete',
        content: 'Are you sure you want delete this record!',
        buttons: {
            btnClass: 'btn-blue',
            confirm: function () {
                if(url!==null && url=='url'){
                    $.ajax({
                            url: $(div).attr('url'),
                            method: 'get',
                            // data: { 'id': id, 'status': value },
                            success: function (result) {
                                //toastr.success(result.message);
                                $(div).closest('.row').remove();
                            },
                            error: function (error) {
                                toastr.error(error.message);
                                //console.log(error.responseJSON.message);
                            }
                        });
                }else{
                    $(div).closest('.list_member_sec').remove();
                }
            },
            cancel: function () {
                btnClass: 'btn-blue'
            },
        }
    });
}


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
$('#is_change').on('change',function () {
    if ($(this).is(':checked')) {
        $(this).val('1');
        $('#is_change_sow').show();
    }else{
        $(this).val('0');
         $('#is_change_sow').hide();
    }
});



</script>