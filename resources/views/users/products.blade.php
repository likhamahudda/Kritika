@extends('layout.user')
@section('content')

<section class="full rfq-page ">
    <div class="container">
        <div class="pro-header">
            <div class="d-flex">
                <h1>Product Listing</h1>
                <button type="button" data-toggle="modal" data-target="#addmodal" class="btn btn-add-pro btn-blue">Add New Product</button>
            </div>
        </div>

        <!-- Add Product form -->
        <div class="product-list-table">
            <div class="table-responsive">
                <table id="data_table" class="display table table-hover" cellspacing="0" width="100%" class="table-responsive">
                    <thead>
                        <tr>
                            <th>{{__('S.No.')}}</th>
                            <th> {{__('Image')}} </th>
                            <th> {{__('Name')}} </th>
                            <th> {{__('Category')}}</th>
                            <th> {{__('Status')}}</th>
                            <th> {{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
<style>
    img.image-icon {
        width: 71px;
    }
</style>

<!-- Add product -->
<div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add New Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-border add_form" method="post">

                <div class="modal-body">
                    <div class="pro-form-add">

                        <div class="form-group ">
                            <label>Category<span class="text-danger">*</span></label>
                            <select class="form-control" name="category_id" id="category_id">
                                <option value="" selected disable>Select Category</option>
                                @if(isset($category) && count($category) > 0)
                                @foreach($category as $category_list)
                                <option value="{{$category_list->id}}">{{$category_list->category_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Product Name<span class="text-danger">*</span></label>
                            <select class="form-control product_name" name="product_name" id="product_name">
                                <option value="" selected disable>Select Product</option>
                                @if(isset($master_products) && count($master_products) > 0)
                                @foreach($master_products as $val)
                                <option value="{{$val->id}}">{{$val->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Company Name<span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" id="product_description" name="product_description" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="formFile" class="form-label">Product Img</label>
                            <input accept="image/png, image/jpg, image/jpeg" class="form-control" type="file" name="product_img" id="formFile">
                            <small style="position: relative;top: -5px;"> &nbsp;(Only formats are allowed: jpeg, jpg, png)</small>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary add_feed_product">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>


<!-- Edit product -->
<div class="modal fade" id="feedmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-border edit_form" method="post">

            <div class="modal-body">
                    <div class="pro-form-add">

                <div class="form-group ">
                    <label>Category<span class="text-danger">*</span></label>
                    <select class="form-control category_id" name="category_id" id="category_id">
                        <option value="" selected disable>Select Category</option>
                        @if(isset($category) && count($category) > 0)
                        @foreach($category as $category_list)
                        <option value="{{$category_list->id}}">{{$category_list->category_name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label>Product Name <span class="text-danger">*</span> </label>

                    <select class="form-control product_name" name="product_name" id="product_name">
                        <option value="" selected disable>Select Product</option>
                        @if(isset($master_products) && count($master_products) > 0)
                        @foreach($master_products as $val)
                        <option value="{{$val->id}}">{{$val->name}}</option>
                        @endforeach
                        @endif
                    </select>

 
                </div>
                <div class="form-group">
                    <label>Company Name<span class="text-danger">*</span></label>
                    <input type="text" name="company_name" id="company_name" class="form-control">
                </div>

                <div class="form-group">
                    <label>Description <span class="text-danger">*</span> </label>
                    <textarea id="product_description" name="product_description" class="form-control border-2 product_description" placeholder="Enter Description"></textarea>
                    <!--  <input name="description" id="description" type="taxt" class="form-control border-2"  placeholder="Enter Description" > -->
                </div>
                <input name="id" id="product_id" type="hidden" class="form-control border-2" placeholder="Enter Description">

                <div class="form-group">
                    <label>Status <span class="text-danger">*</span> </label>
                    {!! Form::select('status', array('0' => 'Inactive', '1' => 'Active'),
                    '',['class' =>'form-control border-2 status']); !!}
                </div>

                <div class="form-group">
                    <label>Product Img <span class="text-danger">*</span></label>
                    <input type="file" class="form-control border-2" placeholder="Product Photo" name="product_img" accept="image/png, image/jpg, image/jpeg">
                    <small style="position: relative;top: -5px;"> &nbsp;(Only formats are
                        allowed: jpeg, jpg, png)</small>
                </div>
                <div class="images_show">
                </div>
 

                </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="edit_submit" type="submit" class="btn btn-primary ">Update Product</button>
                </div>
 
            </form>

        </div>
    </div>
</div>



 

<div class="modal fade" id="viewmodal" tabindex="-1" role="dialog" aria-labelledby="viewmodal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">View Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="pro-form-add">
                    <form id="signin-form">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" value="L Ram" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" value="123456789">
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="formFile" class="form-label">Default file input example</label>
                            <input class="form-control" type="file" id="formFile">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>




<script>
    //new WOW().init();
</script>

<!-- city-dropdowan-js -->

<script>
    function myFunction() {
        var x = document.getElementById("myDIV_pro");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
<script type="text/javascript">
    function cartopen() {
        document.getElementById("sitebar-cart").classList.add('open-cart');
    }

    function cartclose() {
        document.getElementById("sitebar-cart").classList.remove('open-cart');
    }

    function category_open() {
        document.getElementById("sitebar-category").classList.add('open-category');
    }

    function categoryclose() {
        document.getElementById("sitebar-category").classList.remove('open-category');
    }
</script>

<!-- End-category and cart right-open js -->

<!-- show-more-city-js -->
<script type="text/javascript">
    $(".show-morecity").click(function() {
        var type = $(this).attr("data-neexpend");
        console.log($(this).parent().closest("ul"))

        if ($(this).parent().parent().find(".nurtiwala-city").hasClass("show-more-height")) {
            $(this).text("- Show Less " + type);
        } else {
            $(this).text("+ Expand more " + type);
        }

        $(this).parent().parent().find(".nurtiwala-city").toggleClass("show-more-height");
    });
</script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>


<script>
    $(document).ready(function() {
        var id = "1";
        var table = $('#data_table').DataTable({
            responsive: true,
            "order": [
                [0, "desc"]
            ], // order by desc 
            "bProcessing": true,
            "serverSide": true,
            "lengthMenu": [10, 50, 100, 500],

            ajax: {
                url: "{{route('products.product_list') }}",
                data: {
                    status: $('.searchEmail').val()
                },
                error: function() {
                    $.alert('something_went_wrong!');

                }
            },

            "aoColumns": [{
                    mData: 'id'
                },

                {
                    mData: 'product_img'
                },
                {
                    mData: 'name'
                },

                {
                    mData: 'category_name'
                },
                {
                    mData: 'status'
                },

                {
                    mData: 'actions'
                },
            ],
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [-1, -2, -3]
            }, ],
        });

        $(".searchEmail").keyup(function() {
            table.draw();
        });

    });
</script>

<script>
    $(document).on('click', '.delete_button', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        swal({
                title: "Are you sure?",
                //text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'POST',
                        url: '{{url("seller/delete")}}/' + id,
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            if (data.status === true) {
                                toastr.success(data.message);
                                if ($('#data_table').length > 0) {
                                    $('#data_table').DataTable().ajax.reload();
                                }
                            }
                        }
                    });
                }
            });
    });

    $('body').on('click', '#editCompany', function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        $.get('product/' + id + '/edit', function(data) {
            //$('#userCrudModal').html("Edit category");
            //$('#submit').val("Edit category");
            $('#feedmodal').modal('show');
            $('#product_id').val(data.data.id);
            $('#company_name').val(data.data.company_name);
            $('.product_name').val(data.data.product_name);
            $('.status').val(data.data.status);
            $('.category_id').val(data.data.category_id);
            $('.product_description').val(data.data.product_description);

          const element = '<div class="image_view" id="img_'+data.data.id+'"> <div class="business_image"> <img  onclick=imageZoom("uploads/product_img","'+data.data.product_img+'") src="{{url('uploads/product_img')}}/'+ data.data.product_img + '"> </div></div>'
          document.querySelector('.images_show').innerHTML += element;
        })
    });

    $.validator.addMethod('positiveNumber',
        function(value) {
            return Number(value) > 0;
        }, 'Enter a positive number.');

    $('body').on('click', '#edit_submit', function(event) {

        $(".edit_form").validate({
            rules: {
                product_name: {
                    required: true,
                },
                category_id: {
                    required: true,
                },
                product_description: {
                    required: true,
                },
                company_name: {
                    required: true,
                },
                
            },
            messages: {},

            submitHandler: function(form) {

                event.preventDefault();
                var formData = new FormData($(".edit_form")[0]);

                $.ajax({ 
                    url: 'manage',
                    type: "POST",
                    type: "POST",
                    enctype: 'multipart/form-data',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true);
                        $('.loader').show();

                    },
                    success: function(data) {

                        if (data.status === true) {

                            $('#feedmodal').modal('hide');
                            toastr.success(data.message);
                            $('.loader').hide();
                            $('button[type="submit"]').prop('disabled', false);
                            if ($('#data_table').length > 0) {
                                $('#data_table').DataTable().ajax.reload();
                            }
                           // setTimeout(location.reload.bind(location), 1000);
                        } else {
                            $('.loader').hide();
                            toastr.error(data.message);
                            $('button[type="submit"]').prop('disabled', false);
                        }
                    }
                });
            }
        });

    });


    $(document).on('click', '.remove-image', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        swal({
                title: "Are you sure?",
                //text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'POST',
                        url: '{{url("removeimage")}}/' + id,
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            if (data.status === true) {
                                //$('#feedmodal').modal('hide');
                                $('#img_' + data.id + '').remove();
                                toastr.success(data.message);
                                //setTimeout(location.reload.bind(location), 1000);


                            }
                        }
                    });
                }
            });
    });




    $('body').on('click', '.add_feed_product', function(event) {

        $(".add_form").validate({
            rules: {
                product_name: {
                    required: true,
                },
                category_id: {
                    required: true,
                },
                product_description: {
                    required: true,
                },
                company_name: {
                    required: true,
                },
                product_img: {
                    required: true,
                },

            },
            messages: {},

            submitHandler: function(form) {
                event.preventDefault();
                var formData = new FormData($(".add_form")[0]);

                $.ajax({
                    url: 'addProduct',
                    type: "POST",
                    enctype: 'multipart/form-data',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true);
                        $('.loader').show();

                    },
                    success: function(data) {

                        if (data.status === true) {

                            $(".add_form")[0].reset();
                            $('#addmodal').modal('hide');
                            $('.loader').hide();
                            $('button[type="submit"]').prop('disabled', false);
                            toastr.success(data.message);
                            if ($('#data_table').length > 0) {
                                $('#data_table').DataTable().ajax.reload();
                            }
                        } else {
                            $('.loader').hide();
                            toastr.error(data.message);
                            $('button[type="submit"]').prop('disabled', false);
                        }

                    }
                });
            }
        });

    });

    $('.close_load').click(function() {
        location.reload();
    });
</script>




@endsection