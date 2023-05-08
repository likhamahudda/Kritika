<div class="modal-header model_loader">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{{ Form::open(['url' => route('faqs.manage'), 'class' => 'form_submit_faqs', 'method'=>'post', 'files' => 'yes']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Question')}}*</label>
                    {{ Form::text('faq',$data->faq ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter question', 'required' => true]) }}
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('FAQs Type')}}*</label>
                    {{ Form::select('type',array('0' => 'FAQs Page', '1' => 'Pricing', '2' => 'General') ,$data->type ?? '',['class' =>'form-control', 'placeholder' =>'Select FAQs type', 'required' => true]) }}
                </div>

                <div class="col-md-12 mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Answer')}}*</label>
                    {{ Form::textarea('description',$data->description ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Answer', 'id' => 'editor']) }}
                    <!-- <small id="editor_error" style="display: none;" >This field is required minimum 40 characters.</small> -->
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
    <button type="submit" class="btn btn-primary submit_btn_faqs"> Submit </button>
</div>
{{ Form::close() }}

<script type="text/javascript">
$(document).ready(function () {

CKEDITOR.replace('editor',{
           fullPage: true,
           //extraPlugins: 'panelbutton,colorbutton,colordialog,justify,indentblock,aparat,buyLink',
           // You may want to disable content filtering because if you use full page mode, you probably
           // want to  freely enter any HTML content in source mode without any limitations.
           allowedContent: true,
           autoGrow_onStartup: true,
           enterMode: CKEDITOR.ENTER_BR
       }).on('key',
         function(e){
             setTimeout(function(){
                 document.getElementById('editor').value = e.editor.getData();
             },10);

             });
});

$.validator.addMethod('ckrequired', function (value, element, params) {
  // $("#editor_error").addClass("invalid-feedback");
  // document.getElementById("editor_error").style.display="none";
    var idname = jQuery(element).attr('id');
    var messageLength =  $.trim(CKEDITOR.instances.editor.document.getBody().getText());
    //console.log(messageLength);
  //   if(messageLength.length < 10){
  //       //document.getElementById("editor_error").style.display="block";
  //       return false;
  //   //alert(messageLength.length);
  // }
    return !params  || messageLength.length >= 40;
}, "This field is required minimum 40 characters.");

$('.form_submit_faqs').validate({
     ignore : [],
    debug: false,
    rules: {
        description: {
               ckrequired:true
          },
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
        $('#editor').val(CKEDITOR.instances['editor'].getData());
        var button = $('.submit_btn_faqs');
        // $(".form_submit_faqs").unbind().submit(function (event) {
        //     event.preventDefault(); //prevent default action 
        var form_all = $(".form_submit_faqs")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_faqs').text();

            // if ($('.form_submit_faqs').valid()) {
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