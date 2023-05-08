@extends('layout.user')
@section('content')
 
<section class="full rfq-page ">
  <div class="container">
    <div class="page-hedding">

    </div>
 
    <div class="list-item-details mt-3">
      <div class="row">
        <div class="col-md-6">
          <div class="left-img-d">
            <?php
            if (isset($rfq_detail->product_img) && !empty($rfq_detail->product_img)) {
            ?>
              <img src="<?php echo url("uploads/product_img/" . $rfq_detail->product_img); ?>" alt="">
            <?php } ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="text-right-r">
            <h2><?php echo $rfq_detail->name; ?></h2>
            <div class="date-t d-flex">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_15_4700)">
                  <path d="M14.5248 1.38658H13.8534V0.707206C13.8534 0.319043 13.5387 0.00439453 13.1506 0.00439453C12.7624 0.00439453 12.4477 0.319043 12.4477 0.707206V1.38658H5.55221V0.707206C5.55221 0.319043 5.23757 0.00439453 4.8494 0.00439453C4.46124 0.00439453 4.14659 0.319043 4.14659 0.707206V1.38658H3.47523C1.55898 1.38658 0 2.94556 0 4.86177V14.5211C0 16.4374 1.55898 17.9964 3.47523 17.9964H14.5248C16.441 17.9964 18 16.4374 18 14.5211V4.86177C18 2.94556 16.441 1.38658 14.5248 1.38658ZM3.47523 2.7922H4.14659V4.16269C4.14659 4.55085 4.46124 4.8655 4.8494 4.8655C5.23757 4.8655 5.55221 4.55085 5.55221 4.16269V2.7922H12.4478V4.16269C12.4478 4.55085 12.7624 4.8655 13.1506 4.8655C13.5388 4.8655 13.8534 4.55085 13.8534 4.16269V2.7922H14.5248C15.666 2.7922 16.5944 3.72062 16.5944 4.86177V5.53317H1.40562V4.86177C1.40562 3.72062 2.33404 2.7922 3.47523 2.7922ZM14.5248 16.5908H3.47523C2.33404 16.5908 1.40562 15.6623 1.40562 14.5211V6.93879H16.5944V14.5211C16.5944 15.6623 15.666 16.5908 14.5248 16.5908ZM6.24333 9.7032C6.24333 10.0914 5.92868 10.406 5.54051 10.406H4.15833C3.77016 10.406 3.45552 10.0914 3.45552 9.7032C3.45552 9.31504 3.77016 9.00039 4.15833 9.00039H5.54051C5.92864 9.00039 6.24333 9.31504 6.24333 9.7032ZM14.5445 9.7032C14.5445 10.0914 14.2299 10.406 13.8417 10.406H12.4595C12.0714 10.406 11.7567 10.0914 11.7567 9.7032C11.7567 9.31504 12.0714 9.00039 12.4595 9.00039H13.8417C14.2298 9.00039 14.5445 9.31504 14.5445 9.7032ZM10.3899 9.7032C10.3899 10.0914 10.0753 10.406 9.6871 10.406H8.30492C7.91676 10.406 7.60211 10.0914 7.60211 9.7032C7.60211 9.31504 7.91676 9.00039 8.30492 9.00039H9.6871C10.0752 9.00039 10.3899 9.31504 10.3899 9.7032ZM6.24333 13.8498C6.24333 14.238 5.92868 14.5526 5.54051 14.5526H4.15833C3.77016 14.5526 3.45552 14.238 3.45552 13.8498C3.45552 13.4616 3.77016 13.147 4.15833 13.147H5.54051C5.92864 13.147 6.24333 13.4616 6.24333 13.8498ZM14.5445 13.8498C14.5445 14.238 14.2299 14.5526 13.8417 14.5526H12.4595C12.0714 14.5526 11.7567 14.238 11.7567 13.8498C11.7567 13.4616 12.0714 13.147 12.4595 13.147H13.8417C14.2298 13.147 14.5445 13.4616 14.5445 13.8498ZM10.3899 13.8498C10.3899 14.238 10.0753 14.5526 9.6871 14.5526H8.30492C7.91676 14.5526 7.60211 14.238 7.60211 13.8498C7.60211 13.4616 7.91676 13.147 8.30492 13.147H9.6871C10.0752 13.147 10.3899 13.4616 10.3899 13.8498Z" fill="#888888" />
                </g>
                <defs>
                  <clipPath id="clip0_15_4700">
                    <rect width="18" height="18" fill="white" />
                  </clipPath>
                </defs>
              </svg>
              <p><?php echo date("d-m-Y", strtotime($rfq_detail->created_at)); ?></p>
            </div>
            <div class="qty-t d-flex">
              Qty : <p> <?php echo $rfq_detail->qty; ?></p>
            </div>
            <div class="qty-t d-flex">
              <?php
              if (isset($rfq_detail->document) && !empty($rfq_detail->document)) {
              ?>
              Quote Document : <p><a href="<?php echo url("uploads/document_file/" . $rfq_detail->document); ?>" download="">Click here to download</a></p>
              <?php } ?>
            </div>

            <div class="disc-text">
              <p><?php echo $rfq_detail->buyer_msg; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</section>

<section class="full pro-table">
  <div class="container">
    <h2>Send Quote</h2>

    <div class="pro-header">
            <div class="d-flex">
                
                <button type="button" data-toggle="modal" data-target="#addmodal" class="btn btn-add-pro btn-blue">Send Quote</button>
            </div>
        </div>


   
  </div>
</section>


<section class="full pro-table">
  <div class="container">
    <h2>Sent Quote List </h2>
    <table class="table table-hover">

      <tr>
        <th>SR No.</th>
        <th>Details</th>
        <th>Files</th>
      </tr>
      <tbody>
        <?php
        if (count($rfq_seller) > 0) {
          $i = 1;
          foreach ($rfq_seller as $vals) {
        ?>
            <tr>
              
              <td><?php echo $i; ?></td>
              <td><?php echo $vals->rfq_proposal; ?></td>
              <td>
                  <?php
                  if (isset($vals->seller_quote_file) && !empty($vals->seller_quote_file)) {
                  ?>
                  <a href="<?php echo url("uploads/seller_response_file/" . $vals->seller_quote_file); ?>" download="">File download</a>
              <?php } else { ?>
                No Quotes
              <?php } ?>
              </td>
            </tr>
          <?php $i++;
          }
        } else { ?>

          <tr>
            <td colspan="2">No Records</td>
          </tr>

        <?php } ?>


      </tbody>
    </table>
  </div>
</section>

 
<div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Send Quote</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-border add_form" method="post">

                <div class="modal-body">
                    <div class="pro-form-add">
                <input type="hidden" name="rfq_id" value="<?php echo $rfq_detail->id; ?>">

                        <div class="form-group">
                            <label>Detail</label>
                            <textarea class="form-control" id="product_description" name="product_description" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="formFile" class="form-label">Documents</label>
                            <input class="form-control" type="file" name="seller_quote_file" id="formFile">
                         </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary add_feed_product">Send</button>
                </div>

            </form>

        </div>
    </div>
</div>


<script>  
      $('body').on('click', '.add_feed_product', function(event) {

$(".add_form").validate({
    rules: {
      product_description: {
            required: true,
        },       
        seller_quote_file: {
            required: true,
        },

    },
    messages: {},

    submitHandler: function(form) {
        event.preventDefault();
        var formData = new FormData($(".add_form")[0]);

        $.ajax({
            url: '{{url("seller/seller_quote_send")}}',           
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
                    location.reload();
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
  </script>

@endsection