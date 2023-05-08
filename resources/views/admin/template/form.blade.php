<div class="modal-header model_loader">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{{ Form::open(['url' => route('template.manage'), 'class' => 'form_submit_tmplt', 'method'=>'post', 'files' => 'yes']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Template Title')}}*</label>
                    {{ Form::text('title',$data->title ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter template title', 'required' => true]) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Template Category')}}*</label>
                    <select required name="category_id" class="form-control">
                        <option value="">Select template category</option>
                        @foreach($data_ct as $row)
                            <option @if(!empty($data)) @if($row->id==$data->category_id) selected @endif @endif value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Template Type')}}*</label>
                    {{ Form::select('type',array('0' => 'Free', '1' => 'Paid') ,$data->type ?? '',['class' =>'form-control', 'placeholder' =>'Select template type', 'onchange'=>"template_type($(this).val())", 'required' => true]) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Price')}}*</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon2">$</span>
                        {{ Form::number('price',$data->price ?? '0' ,['class' =>'form-control', 'placeholder' =>'Enter template price','id'=>'set_price', 'required' => true, 'min'=>'0']) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Description')}}*</label>
                        {{ Form::textarea('description',$data->description ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter description', 'required' => true,'rows'=>2]) }}
                </div> 
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Template PDF File')}}*</label>
                    {{ Form::file('file' ,['class' =>'form-control', 'id' => 'fileControl', 'accept' =>'.pdf']) }}
                </div>
                @if(!empty($data->id))
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Uploaded PDF File')}}</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon2" style="overflow: hidden;width: 88.81%;font-size: 12px;">{{$data->file}}</span>
                        <a target="_blank" title="View PDF" href="{{URL::asset('uploads/template_pdf').'/'.$data->file}}" class="input-group-text" id="basic-addon2"> <i class="bx bx-show model_open"></i> </a>
                    </div>
                </div>
                @endif
                
                {!! Form::hidden('id', $data->id ?? '') !!}

               
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Close
    </button>
    <button type="submit" class="btn btn-primary submit_btn_tmplt"> Submit </button>
</div>
{{ Form::close() }}

@if(empty($data))
<script type="text/javascript">
    $('input[type=file]').attr('required',true);
</script>
@else
@if($data->type=='0')
<script type="text/javascript">
    $('#set_price').val('0');
    $('#set_price').attr('readonly',true);
</script>
@endif
@endif

<script type="text/javascript">
function template_type(val) {
    if(val=='0'){
        $('#set_price').val('0');
        $('#set_price').attr('readonly',true);
    }else{
        //$('#set_price').val('0');
        $('#set_price').removeAttr('readonly');
    }
}
$.validator.addMethod("extension", function (value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, '|') : "pdf";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, "Only .pdf file types are allowed.");

$('.form_submit_tmplt').validate({
    rules: {
        
        file : {
            extension : true
        }
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
        var button = $('.submit_btn_tmplt');
        // $(".form_submit_tmplt").unbind().submit(function (event) {
        //     event.preventDefault(); //prevent default action 
        var form_all = $(".form_submit_tmplt")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_tmplt').text();

            // if ($('.form_submit_tmplt').valid()) {
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