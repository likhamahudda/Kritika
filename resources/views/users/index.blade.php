@extends('layout.user')
@section('content')
<div class="content">
   
   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


<section class="full rfq-page ">
  <div class="container">
  <div class="page-hedding">
    <h1>Listing RFQ'S</h1>
  </div>

  <div class="filter-bar">
  <div class="row">
          <div class="col">
            <label>Search</label>
            <input type="text" class="form-control product_name" placeholder="Search Product...">
          </div>
          <div class="col">
            <label>Date From</label>
            <div class="date-mt d-flex">

              <div class="form-group mb-4">
                <div class="from_date date input-group">
                  <input type="text" placeholder="From Date" class="form-control fromDate" id="fecha1">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="col">
            <label>Date To</label>
            <div class="date-mt d-flex">
              <div class="form-group mb-4">
                <div class="to_date date input-group">
                  <input type="text" placeholder="To Date" class="form-control toDate" id="fecha1">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="col search-btn">
            <label class="w-100">&nbsp;</label>
            <button type="button" class="btn btn-search btn-blue filter_search">SEARCH</button>
            <button type="button" class="btn btn-search filter_reset">RESET</button>
          </div>
        </div>
  </div>

  <!-- Listing -->

  <div class="list-item mt-3">
    <div class="row request_quote_list">
 

     
 
    </div>
  </div>


  </div>
 </section>
 
</div>


<script>
  $(function() {
    $('.from_date').datepicker({
      autoclose: true,
      format: "dd-mm-yyyy"
    });
  });

  $(function() {
    $('.to_date').datepicker({
      autoclose: true,
      format: "dd-mm-yyyy"
    });
  });
</script>

<script>
 
   request_quote_listing();
  $(document).on("click", ".filter_search", function () {
    var product_name = $(".product_name").val();
    var fromDate = $(".fromDate").val();
    var toDate = $('.toDate').val();
    
    if(product_name !='' ||  fromDate !='' || toDate !=''){
       request_quote_listing();
    }

  });

  $(document).on("click", ".filter_reset", function () {
       $(".product_name").val('');
       $(".fromDate").val('');
       $('.toDate').val('');
       request_quote_listing();
    

  });


  

  function request_quote_listing(page = '') {
    var product_name = $(".product_name").val();
    var fromDate = $(".fromDate").val();
    var toDate = $('.toDate').val();

    if (page != '') {
      var totalPage = parseInt(page);
    } else {
      totalPage = 1;
    }
    $.ajax({
      type: "POST",
      url: '{{url("seller/request_quote_filter_list")}}',
      data: {
        page: totalPage,
        product_name: product_name,
        fromDate: fromDate,
        toDate: toDate
      },
      beforeSend: function() {
        $('.loader').show();
      },
      success: function(data) {
        $('.loader').hide();

        //  var response = JSON.parse(data);
        //  $("#content").html('<tr><td colspan="6"><strong>loading...</strong></td></tr>');
        $(".request_quote_list").html(data);


      }
    });
  }
</script>


@endsection

