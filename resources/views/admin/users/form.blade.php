<div class="modal-header model_loader">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

{{ Form::open(['url' => route('users.manage'), 'class' => 'form_submit_user', 'method'=>'post', 'files' => 'yes']) }}
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
                    <label for="nameBackdrop" class="form-label">{{__('Sub Caste(Gotar)')}}*</label>
                    {{ Form::text('sub_caste',$data->sub_caste ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Sub Caste(Gotar)', 'required' => true]) }}
                </div>


                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Kulrishi')}}*</label>
                    {{ Form::text('kulrishi',$data->kulrishi ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Kulrishi']) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Kildevi')}}*</label>
                    {{ Form::text('kildevi',$data->kildevi ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Kildevi']) }}
                </div>
 
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Gender')}}*</label>
                    {{ Form::select('gender',array('1'=>'Male','2'=>'Female'),$data->gender ?? '',['class' =>'form-control', 'placeholder' =>'Select Gender', 'required' => true]) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Country')}}*</label>
                    <select required name="country_id" class="form-control country_id">
                        <option value="">Select Country</option>
                        @foreach($data_country as $row)
                            <option @if(!empty($data)) @if($row->id==$data->category_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('State')}}*</label>
                    <select required name="state_id" class="form-control state_id">
                        <option value="">Select State</option> 
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('District')}}*</label>
                    <select required name="district_id" class="form-control district_id">
                        <option value="">Select District</option>  
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('City/Tehsil')}}*</label>
                    <select required name="tehsil_id" class="form-control tehsil_id">
                        <option value="">Select City/Tehsil</option> 
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Gram panchayat')}}*</label>
                    <select required name="panchayat_id" class="form-control panchayat_id">
                        <option value="">Select Gram panchayat</option> 
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Village Name')}}*</label>
                    <select required name="village_id" class="form-control village_id">
                        <option value="">Select Village Name</option> 
                       
                    </select>
                </div>
             

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('House in The Village')}}*</label>
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

 
                <div class="col-md-12">
            <h5>List Member</h5>
            <div class="row">
                <div class="col-md-3 row"> 
                     <div class="col-md-12 mb-3">
                     <label for="nameBackdrop" class="form-label">{{__('Name')}}* &emsp;
                        {{ Form::text('name[]',$data_f['data_f1']->count ?? '' ,['class' =>'form-control name_count', 'placeholder' =>'Name',  'required' => true]) }}
                    </div>
                </div>
               

                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Relation Select')}}* &emsp;
                     <div class="col-md-12 mb-3">
                     {{ Form::select('relation_type[]',array('son_of'=>'Son Of','wife_of'=>'Wife Of','daughter_of'=>'Daughter Of'),$data->relation_type ?? '',['class' =>'form-control', 'placeholder' =>'Select Relation', 'required' => true]) }}
                     </div>
                </div>


                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Relation With')}}* &emsp;
                     <div class="col-md-12 mb-3">
                        {{ Form::text('relation_name_with[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control relation_name_with_count', 'placeholder' =>'Relation With',  'required' => true]) }}
                    </div>
                </div>

                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Education')}} 
                     <div class="col-md-12 mb-3">
                         <select id="education" name="education[]" class="form-control">
                         <option value="">Select Education</option>
                         @foreach($data_occupations as $row)
                            <option @if(!empty($data)) @if($row->id==$data->category_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Occupation')}} 
                     <div class="col-md-12 mb-3">
                     <select required name="occupation" class="form-control">
                     <option value="">Select Occupation</option>
                     @foreach($data_education as $row)
                            <option @if(!empty($data)) @if($row->id==$data->category_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                     </div>
                </div>

                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Gender')}}* &emsp;
                     <div class="col-md-12 mb-3">
                     {{ Form::select('gender_type[]',array('male'=>'Male','female'=>'Female'),$data->gender ?? '',['class' =>'form-control', 'placeholder' =>'Select Gender', 'required' => true]) }}
                     </div>
                </div>

                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Phone Number')}} 
                     <div class="col-md-12 mb-3">
                        {{ Form::text('phone_no[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Phone Number']) }}
                    </div>
                </div>

                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Email')}} 
                     <div class="col-md-12 mb-3">
                        {{ Form::text('email_address[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Email']) }}
                    </div>
                </div>

                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Married Status')}}* &emsp;
                     <div class="col-md-12 mb-3">
                     {{ Form::select('married_status[]',array('married'=>'Married','unmarried'=>'UnMarried'),$data->married_status ?? '',['class' =>'form-control', 'placeholder' =>'Select Married Status', 'required' => true]) }}
                    </div>
                </div>


                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('D.O.B')}}* &emsp;
                     <div class="col-md-12 mb-3">
                        {{ Form::text('dob[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'D.O.B',  'required' => true]) }}
                    </div>
                </div>


                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Age')}}* &emsp;
                     <div class="col-md-12 mb-3">
                        {{ Form::text('age[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Age',   'required' => true]) }}
                    </div>
                </div>

                <div class="col-md-3 row">
                    <label for="nameBackdrop" class="form-label">{{__('Current Address')}}* &emsp;
                     <div class="col-md-12 mb-3">
                        {{ Form::text('address[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Current Address', 'required' => true]) }}
                    </div>
                </div>


                <div class="col-md-1">
                    <button type="button" class="btn btn-primary add_more_features" title="Add More Features"> 
                    <i class="bx bxs-plus-square"> </i> </button>
                </div>
             </div>
        </div>

        <div class="col-md-12 add_here_features row">
        @if(isset($data_f['data_f0']) && !empty($data_f['data_f0']))
        @foreach ($data_f['data_f0'] as $row)
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ Form::text('description['.$row->id.']',$row->description ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter feature', 'required' => true]) }}
                </div>
                <div class="col-md-6 mb-3"><button type="button" class="btn btn-danger" title="Remove" onclick="remove_features($(this),'url');" url="{{ customeRoute('subscription.delete_feature', ['id' => $row->id]) }}"><i class="bx bx-minus"> </i> </button></div>
            </div>
        @endforeach
        @endif
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
    <button type="submit" class="btn btn-primary submit_btn_user"> Submit </button>
</div>
{{ Form::close() }}

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
            url: "{{ route('users.states-by-country') }}",
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
           url: "{{ route('users.districts-by-state') }}",
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
           url: "{{ route('users.tehsil-by-district') }}",
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
           url: "{{ route('users.panchayat-by-tehsil') }}",
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
           url: "{{ route('users.village-by-panchayat') }}",
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
     var html_add = '';
     html_add += '<hr>'; 
     
    html_add += '<div class="col-md-3 row"> ';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Name')}}* &emsp;';
    html_add += '{{ Form::text('name[]',$data_f['data_f1']->count ?? '' ,['class' =>'form-control name_count', 'placeholder' =>'Name',  'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Relation Select')}}* &emsp;';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '{{ Form::select('relation_type[]',array('son_of'=>'Son Of','wife_of'=>'Wife Of','daughter_of'=>'Daughter Of'),$data->relation_type ?? '',['class' =>'form-control', 'placeholder' =>'Select Relation', 'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Relation With')}}* &emsp;';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '{{ Form::text('relation_name_with[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control relation_name_with_count', 'placeholder' =>'Relation With',  'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Education')}} ';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '<select id="education" name="education[]" class="form-control">';
    html_add += '<option value="">Select Education</option>';
    html_add += '@foreach($data_occupations as $row)';
    html_add += '<option @if(!empty($data)) @if($row->id==$data->category_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>';
    html_add += '@endforeach';
    html_add += '</select>';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Occupation')}} </label>';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '<select required name="occupation" class="form-control">';
    html_add += '<option value="">Select Occupation</option>';
    @foreach($data_education as $row)
    html_add += '<option @if(!empty($data)) @if($row->id==$data->category_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>';
    @endforeach
    html_add += '</select>';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Gender')}}* &emsp;';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '{{ Form::select('gender_type[]',array('male'=>'Male','female'=>'Female'),$data->gender ?? '',['class' =>'form-control', 'placeholder' =>'Select Gender', 'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Phone Number')}}';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '{{ Form::text('phone_no[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Phone Number']) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Email')}}';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '{{ Form::text('email_address[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Email']) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Married Status')}}* &emsp;';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '{{ Form::select('married_status[]',array('married'=>'Married','unmarried'=>'UnMarried'),$data->married_status ?? '',['class' =>'form-control', 'placeholder' =>'Select Married Status', 'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('D.O.B')}}* &emsp;';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '{{ Form::text('dob[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'D.O.B',  'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Age')}}* &emsp;';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '{{ Form::text('age[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Age',   'required' => true]) }}';
    html_add += '</div>';
    html_add += '</div>';

    html_add += '<div class="col-md-3 row">';
    html_add += '<label for="nameBackdrop" class="form-label">{{__('Current Address')}}* &emsp;</label>';
    html_add += '<div class="col-md-12 mb-3">';
    html_add += '{{ Form::text('address[]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control phone_no_count', 'placeholder' =>'Current Address', 'required' => true]) }}';
    html_add += '</div></div>'; 
     
    html_add += '<div class="col-md-6 mb-3"><button type="button" class="btn btn-danger" title="Remove" onclick="remove_features($(this));"><i class="bx bx-minus"> </i> </button></div>';


     $('.add_here_features').append(html_add);
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
                    $(div).closest('.row').remove();
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
$.validator.addMethod("alpha", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
},'Only alphabets allowed.');
$.validator.addMethod("email_val", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
},'Please enter a valid email address.');
$.validator.addMethod("extension", function (value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, "Only .jpg, .png & .jpeg file types are allowed.");

$('.form_submit_user').validate({
    rules: {
        first_name : {
            alpha : true
        },
        last_name : {
            alpha : true
        },
        email : {
            email_val : true
        },
        mobile : {
            maxlength: 12,
            minlength: 6
        },
        password : {
            maxlength: 12,
            minlength: 8
        },
        confirm_password : {
            equalTo: "#password"
        },
        image : {
            extension : true
        }
    },
    messages: {
        mobile : {
            maxlength: 'Please enter no more than 12 digits.',
            minlength: 'Please enter at least 6 digits.'
        },
        password : {
            maxlength: 'Please enter no more than 12 characters.',
            minlength: 'Please enter at least 8 characters.'
        },
        confirm_password : {
            equalTo: "The password and confirm password do not match."
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
        var button = $('.submit_btn_user');
        // $(".form_submit_user").unbind().submit(function (event) {
        //     event.preventDefault(); //prevent default action 
        var form_all = $(".form_submit_user")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_user').text();

            // if ($('.form_submit_user').valid()) {
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