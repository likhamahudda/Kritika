@extends('layout.user')
@section('content')


<section class="full rfq-page ">
    <div class="container">

        <div class="create-rfq-form">
            <h2>Choose Product</h2>
            <div class="create-field">
                <form name="AddRfq" action="{{route('buyer.send_rfq_seller')}}" method="POST" class="AddRfq" id="editprofileform">

                    <div class="form-group">
                        <label>Choose Product</label>

                        <select class="form-control product_id" name="product_id" id="product_id">
                            <option value="" selected disable>Select Product</option>
                            @if(isset($products) && count($products) > 0)
                            @foreach($products as $val)
                            <option master_pro_id="{{$val->master_pro_id}}" value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                            @endif
                        </select>

                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="text" class="form-control" name="quantity">
                    </div>

                    <div class="form-group">
                        <label>Sent to Seller</label>
                        <select multiple class="form-control seller_id" name="seller_id[]" id="seller_id">
                            <option value="" selected disable>Select Product</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="formFile" class="form-label">Add Attachment</label>
                        <input class="form-control" name="document_file" type="file" id="formFile">
                    </div>

                    <div class="form-group">
                    <button id="edit_submit" type="submit" class="btn btn-primary ">Send RFQ</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>  
 

<script type="text/javascript">

        $(document).ready(function() {  
        $('.seller_id_').multiselect();  
       
    });  


    $(document).on('change', '.product_id', function() {

        var id = $(this).val();
        var id = $('option:selected', this).attr('master_pro_id');

       if($(this).val() ==''){
        var id = 'none';
       }
        
     
        $.ajax({
            type: 'POST',
            url: '{{url("buyer/get_product_seller")}}/' + id,
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
               
                $('.seller_id').html(data);
                $('.seller_id').multiselect('rebuild'); 
               
            }
        });

    });


    jQuery.validator.addMethod("alpha", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /^[A-Za-z]+$/.test(value);
    }, 'Only alphabets are allowed.');


    $('.AddRfq').validate({
        rules: {
            product_id: {
                required: true,
            },
            quantity: {
                required: true,
                number: true
            },
            'seller_id[]': {
                required: true,
            },
            description: {
                required: true,
            }
            
        },
        messages: {

        },

        submitHandler: function(form) {
            var button = $('.submit_btn_profile');
            var form_all = $(".AddRfq")[0];
            var formData = new FormData(form_all);
            var old_text = $(button).closest('.submit_btn_profile').text();

            $.ajax({
                url: $(form_all).attr("action"),
                type: $(form_all).attr("method"),
                data: formData,
                contentType: false, //this is requireded please see answers above
                processData: false,
                beforeSend: function() {
                    $(button).prop('disabled', true);
                    $(button).text('Please wait...');
                    //model loader add 
                },
                success: function(data) {
                    if (data.status === true) {
                        $(".AddRfq")[0].reset();
                        $('.seller_id').multiselect('refresh');
                        $('.update_name_submit').text(data.name);
                        $('.update_date').text(data.date);
                        toastr.success(data.message);

                        $(button).prop('disabled', false);
                        $(button).text(old_text);
                        
                    } else {

                        toastr.error(data.message);
                        $(button).prop('disabled', false);
                        $(button).text(old_text);
                    }
                },
                error: function(e) {
                    $(button).prop('disabled', false);
                    $(button).text(old_text);
                    toastr.error(e.responseJSON.message);
                }
            });
            //}
            //});
        }
    });
    $('.form_submit_changepass').validate({

        rules: {
            password: {
                required: true,
                minlength: 8,
                maxlength: 12,
            },
            confirm_password: {
                equalTo: '#password'
            }
        },
        messages: {

        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-control').parent().append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        /*  submitHandler: function (form) {
               $('.loader').show();
         $( "#event_add" ).submit();
               form.submit();

           } */
        submitHandler: function(form) {
            var button = $('.submit_btn_changepass');
            var form_all = $(".form_submit_changepass")[0];
            var formData = new FormData(form_all);

            var old_text = $(button).closest('.submit_btn_changepass').text();

            $.ajax({
                url: $(form_all).attr("action"),
                type: $(form_all).attr("method"),
                data: formData,
                contentType: false, //this is requireded please see answers above
                processData: false,
                beforeSend: function() {
                    $(button).prop('disabled', true);
                    $(button).text('Please wait...');
                    //model loader add 
                },
                success: function(data) {
                    if (data.status === true) {
                        $('.update_name_submit').text(data.name);

                        toastr.success(data.message);
                        $(".form_submit_changepass")[0].reset();
                        $(button).prop('disabled', false);
                        $(button).text(old_text);
                    } else {
                        toastr.error(data.message);
                        $(button).prop('disabled', false);
                        $(button).text(old_text);
                    }
                },
                error: function(e) {
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