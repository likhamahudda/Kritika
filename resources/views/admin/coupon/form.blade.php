<div class="modal-header model_loader">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
@php
if(!empty($data->start_date)){
    $min=date('Y-m-d',(strtotime($data->start_date)));
}else{
    $min=date('Y-m-d');
}
@endphp
{{ Form::open(['url' => route('coupon.manage'), 'class' => 'form_submit_cc', 'method'=>'post', 'files' => 'yes']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Coupon Code')}}*</label>
                    {{ Form::text('code',$data->code ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter coupon code', 'required' => true]) }}
                </div>   
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Coupon Price')}}*</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon2">{{Constant::CURRENCY}}</span>
                        {{ Form::number('price',$data->price ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter coupon price', 'required' => true,'min'=>1]) }}
                    </div>
                </div>  
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Coupon Start Date')}}*</label>
                    {{ Form::date('start_date',$data->start_date ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter coupon start date', 'required' => true, 'min'=>$min]) }}
                </div> 
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Coupon Expiry Date')}}*</label>
                    {{ Form::date('expiry_date',$data->expiry_date ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter coupon expiry date', 'required' => true]) }}
                </div>  
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('No. of Use')}}*</label>
                        {{ Form::number('no_of_use',$data->no_of_use ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter no. of times coupon can be used', 'required' => true,'min'=>1]) }}
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Coupon Type')}}*</label>
                    {{ Form::select('type',array('0' => 'Subscription', '1' => 'Template') ,$data->type ?? '',['class' =>'form-control', 'placeholder' =>'Select coupon type', 'required' => true]) }}
                </div>  
                <div class="col-md-12 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Coupon Description')}}*</label>
                        {{ Form::textarea('description',$data->description ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter coupon description', 'required' => true,'rows'=>2]) }}
                </div>            
            </div>
                {!! Form::hidden('id', $data->id ?? '') !!}

               
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Close
    </button>
    <button type="submit" class="btn btn-primary submit_btn_cc"> Submit </button>
</div>
{{ Form::close() }}

<script type="text/javascript">
jQuery.validator.addMethod("date", function(value, element) {
    if($('input[name=start_date]').val()=='' || $('input[name=expiry_date]').val()==''){
        return true;
    }else{
        if($('input[name=start_date]').val()<=$('input[name=expiry_date]').val()){
            return true;
        }else{
            return false;
        }
    }
});

$('.form_submit_cc').validate({
     rules: {
        start_date : {
            date : true,
            min : '{{$min}}',
        },
        expiry_date : {
            date : true
        }
    },
    messages: {
         start_date : {
            date : 'start date must be less than or equal to expiry date',
            min : 'start date must be greater than or equal to current date',
        },
        expiry_date : {
            date : 'expiry date must be greater than or equal to start date'
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
    submitHandler: function (form) {
        var button = $('.submit_btn_cc');
        // $(".form_submit_cc").unbind().submit(function (event) {
        //     event.preventDefault(); //prevent default action 
        var form_all = $(".form_submit_cc")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_cc').text();

            // if ($('.form_submit_cc').valid()) {
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