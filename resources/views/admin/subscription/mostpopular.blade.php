            <div class="modal-header model_loader">
                <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
                <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['url' => route('subscription.managemp'), 'class' => 'form_submit_mp', 'method'=>'post', 'files' => 'yes']) }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nameBackdrop" class="form-label">Account Plans</label>
                                    <select class="form-control" name="account_type" aria-required="true">
                                        <option value="">Select For Account Plan</option>
                                        @if(!empty($data1))
                                            @foreach($data1 as $data)
                                                <option @if($data->is_popular=='1') selected @endif value="{{$data->id}}">{{$data->plan_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div> 
                                <div class="col-md-6 mb-3">
                                    <label for="nameBackdrop" class="form-label">API Plans</label>
                                    <select class="form-control" name="api_type" aria-required="true">
                                        <option value="">Select For API Plan</option>
                                        @if(!empty($data2))
                                            @foreach($data2 as $data)
                                                <option @if($data->is_popular=='1') selected @endif value="{{$data->id}}">{{$data->plan_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>           
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary submit_btn_mp"> Submit </button>
                </div>
            {{ Form::close() }}

<script type="text/javascript">

$('.form_submit_mp').validate({
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
        var button = $('.submit_btn_mp');
        // $(".form_submit_mp").unbind().submit(function (event) {
        //     event.preventDefault(); //prevent default action 
        var form_all = $(".form_submit_mp")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_mp').text();

            // if ($('.form_submit_mp').valid()) {
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