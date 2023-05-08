<div class="modal-header model_loader">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{{ Form::open(['url' => route('subscription.manage'), 'class' => 'form_submit_subscription', 'method'=>'post', 'files' => 'yes']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Subscription Plan Name')}}*</label>
                    {{ Form::text('plan_name',$data->plan_name ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter plan name', 'required' => true]) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Subscription Type')}}*</label>
                    {{ Form::select('subscription_type',array('1' => 'Account Plan', '2' => 'API Plan') ,$data->subscription_type ?? '',['class' =>'form-control', 'placeholder' =>'Select plan type', 'required' => true]) }}
                </div> 
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Monthly Price')}}*</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon2">{{Constant::CURRENCY}}</span>
                        {{ Form::number('monthly_price',$data->monthly_price ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter monthly plan price', 'required' => true]) }}
                        <span class="input-group-text" id="basic-addon2">per month</span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Yearly Price')}}*</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon2">{{Constant::CURRENCY}}</span>
                        {{ Form::number('plan_yearly_but_montly_price',$data->plan_yearly_but_montly_price ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter yearly plan price', 'required' => true]) }}
                        <span class="input-group-text" id="basic-addon2">per month</span>
                    </div>
                </div>           
            </div>
                {!! Form::hidden('id', $data->id ?? '') !!}   
        </div>
        <div class="col-md-12"> 
            <h5>Features</h5>
            <div class="row" style="margin: 15px 0px;">
                <div class="col-md-4 row">
                    <label for="nameBackdrop" class="form-label">{{__('Monthly Signatures')}}* &emsp;
                    <input type="checkbox" name="monthly_signature_" class="unlimited_checkbox" @if(isset($data_f['data_f1']->count)) @if($data_f['data_f1']->count=='-1') checked @endif @endif></label>
                    <div class="col-md-12 mb-3">
                        {{ Form::number('count[1]',$data_f['data_f1']->count ?? '' ,['class' =>'form-control monthly_signature_count', 'placeholder' =>'No. of monthly signatures','min'=>'-1','oninput'=>"this.value = this.value.replace('.', '')", 'required' => true]) }}
                    </div>
                </div>
                <div class="col-md-4 row">
                    <label for="nameBackdrop" class="form-label">{{__('Reusable Template')}}* &emsp;
                    <input type="checkbox" name="reusable_template_" class="unlimited_checkbox" @if(isset($data_f['data_f2']->count)) @if($data_f['data_f2']->count=='-1') checked @endif @endif></label>
                    <div class="col-md-12 mb-3">
                        {{ Form::number('count[2]',$data_f['data_f2']->count ?? '' ,['class' =>'form-control reusable_template_count', 'placeholder' =>'No. of reusable template','min'=>'-1','oninput'=>"this.value = this.value.replace('.', '')", 'required' => true]) }}
                    </div>
                </div>
                <div class="col-md-4 row">
                    <label for="nameBackdrop" class="form-label">{{__('Receive & sign')}}* &emsp;
                    <input type="checkbox" name="receive_sign_" class="unlimited_checkbox" @if(isset($data_f['data_f3']->count)) @if($data_f['data_f3']->count=='-1') checked @endif @endif></label>
                    <div class="col-md-12 mb-3">
                        {{ Form::number('count[3]',$data_f['data_f3']->count ?? '' ,['class' =>'form-control receive_sign_count', 'placeholder' =>'No. of receive & sign','min'=>'-1','oninput'=>"this.value = this.value.replace('.', '')", 'required' => true]) }}
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-primary add_more_features" title="Add More Features"> 
                    <i class="bx bxs-plus-square"> </i> </button>
                </div>
                <small class="text-danger">Note : Check the checkbox to set unlimited.</small>
            </div>
        </div>
        <div class="col-md-12 add_here_features">
        @foreach ($data_f['data_f0'] as $row)
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ Form::text('description['.$row->id.']',$row->description ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter feature', 'required' => true]) }}
                </div>
                <div class="col-md-6 mb-3"><button type="button" class="btn btn-danger" title="Remove" onclick="remove_features($(this),'url');" url="{{ customeRoute('subscription.delete_feature', ['id' => $row->id]) }}"><i class="bx bx-minus"> </i> </button></div>
            </div>
        @endforeach
        </div>
    </div>

</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Close
    </button>
    <button type="submit" class="btn btn-primary submit_btn_subscription"> Submit </button>
</div>
{{ Form::close() }}

<script type="text/javascript">
$('.unlimited_checkbox').each(function () {
    var name = $(this).attr('name')+'count';
    var input = $('.'+name);
    if($(this).is(":checked")){
        $(input).val('-1');
        $(input).attr('type','hidden');
        $(input).parent().append('<input type="text" class="form-control" readonly value="Unlimited" id="unlimited">');
    }
});
$('.unlimited_checkbox').on('change', function () {
    var name = $(this).attr('name')+'count';
    var input = $('.'+name);
    $(input).removeClass('is-invalid');
    //console.log(input);
    if($(this).is(":checked")){
        $(input).val('-1');
        $(input).attr('type','hidden');
        $(input).parent().append('<input type="text" class="form-control" readonly value="Unlimited" id="unlimited">');
    }else{
        $(input).val('');
        $(input).parent().find('#unlimited').remove();
        $(input).attr('type','number');
    }
});
var i = 0;
$('.add_more_features').on('click', function () {
     i = i+1;
    var input = '<div class="row"><div class="col-md-6 mb-3"><input class="form-control" placeholder="Enter feature" required="" name="description0['+i+']" type="text" value=""></div><div class="col-md-6 mb-3"><button type="button" class="btn btn-danger" title="Remove" onclick="remove_features($(this));"><i class="bx bx-minus"> </i> </button></div></div>';
    $('.add_here_features').append(input);
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

$('.form_submit_subscription').validate({
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
        var button = $('.submit_btn_subscription');
        // $(".form_submit_subscription").unbind().submit(function (event) {
        //     event.preventDefault(); //prevent default action 
        var form_all = $(".form_submit_subscription")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_subscription').text();

            // if ($('.form_submit_subscription').valid()) {
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


$( function() {
    $( "#datepicker" ).datepicker();
  } );
</script>