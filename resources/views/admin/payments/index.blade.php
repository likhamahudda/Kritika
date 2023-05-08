@extends('layout.admin')
@section('content')
@push('css')
<link rel="stylesheet" href="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.css')}}" />

@endpush
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mt-3">


  <div class="col">
    <div class="card radius-10 border-start border-0 border-3 border-danger">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary"> Last Month </p>
            <h4 class="my-1 text-danger"> {{$last_month ?? 0}}  </h4>
          </div>
          <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">$
          </div>
        </div>
      </div>
    </div>
  </div>




  <div class="col">
    <div class="card radius-10 border-start border-0 border-3 border-info">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">This Month</p>
            <h4 class="my-1 text-info">{{$this_month ?? 0}}</h4>
            <!-- <p class="mb-0 font-13">  Count in  team leader </p> -->
          </div>
          <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">$
          </div>
        </div>
      </div>
    </div>
  </div>




  <div class="col">
    <div class="card radius-10 border-start border-0 border-3 border-success">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary"> This Year </p>
            <h4 class="my-1 text-success"> {{$this_year ?? 0}} </h4>
          </div>
          <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">$
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card radius-10 border-start border-0 border-3 border-primary">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary"> Total</p>
            <h4 class="my-1 text-primary"> {{$total ?? 0}} </h4>
          </div>
          <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">$
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="d-flex justify-content-between">
<h5 class="page_heading">{{__('Payments List')}}  </h5>
<button class="btn btn-outline-primary export_csv">Export in CSV</button>
</div>
<hr class="page_row">
<div class="card">
    <div class="card-body">
        <div class="card-block table-border-style">
            <table id="data_table" class="display" cellspacing="0" width="100%" class="table-responsive">
                <div class="row" style="margin-bottom: 15px;">
                    <!-- <div class="row col-sm-10"> -->
                        <div class="col-sm-2">
                            <label>Name</label>
                            <input type="name" class="form-control" name="name_search" id="name_search" placeholder="Search by user name">
                        </div>
                        <div class="col-sm-2">
                            <label>Subscription Type</label>
                            <select class="form-control" name="status_search" id="plan_type" onchange="get_plans($(this).val());">
                                <option value="">Subscription type</option>
                                <option value="1">Account Plan</option>
                                <option value="2">API Plan</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Subscription Plans</label>
                            <select class="form-control" name="status_search" id="plan_id">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Payment Status</label>
                            <select class="form-control" name="status_search" id="status_search">
                                <option value="">Payment Status</option>
                                <option value="1">Complete</option>
                                <option value="0">Failed</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>From Date</label>
                            <input type="date" class="form-control" name="from_date" id="from_date">
                        </div>
                        <div class="col-sm-2">
                            <label>To Date</label>
                            <input type="date" class="form-control" name="to_date" id="to_date">
                        </div>
                    <!-- </div>
                    <div class="col-sm-2" align="center">
                        <button class="btn btn-outline-primary export_csv">Export in CSV</button>
                    </div> -->
                </div>
                <thead>
                    <tr>
                        <th>#</th>
                        <th> {{__('User Name')}} </th>
                        <th> {{__('Subscription Plan')}} </th>
                        <th> {{__('Amount Paid')}} </th>
                        <th> {{__('Payment Status')}} </th>
                        <th> {{__('Payment Date')}}</th>
                        <th> {{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.js')}}"></script>
<script>
    $(document).ready(function() {
        var table = $('#data_table').DataTable({
            responsive: true, 
            bFilter:false,
            "order": [
                [0, "desc"]
            ], // order by desc 
            "bProcessing": true,
            "serverSide": true,
            "lengthMenu": [10,50,100,500],
            "pageLength": 50,
            "sDom": '<"top" <"row" <"" <"">>>>tr<"bottom" <"row" <"col-sm-4" l><"col-sm-4 text-center" i><"col-sm-4" p>>>',


            ajax: {
                url: "{{ route($page.'.index') }}",
                data: function (d) {
                    d.name_search = $('#name_search').val();
                    d.plan_type = $('#plan_type').val();
                    d.plan_id = $('#plan_id').val();
                    d.status_search = $('#status_search').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                },
                error: function() {
                    $.alert('something_went_wrong!');

                }
            },

            "aoColumns": [{
                    mData: 'id'
                },

                {
                    mData: 'user_name'
                },

                {
                    mData: 'plan_name'
                },

                {
                    mData: 'amount'
                },

                {
                    mData: 'status'
                },
                {
                    mData: 'updated_at'
                },

                {
                    mData: 'actions'
                },
            ],
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [-1]
            }, ],
        });
        $('#name_search').keyup(function(){
            table.draw();
        });
        $('#plan_type').on('change',function(){
            table.draw();
        });
        $('#plan_id').on('change',function(){
            table.draw();
        });
        $('#status_search').on('change',function(){
            table.draw();
        });
        $('#from_date').on('change',function(){
            table.draw();
        });
        $('#to_date').on('change',function(){
            table.draw();
        });
        
    });
    $('.export_csv').on('click',function () {
        var ff = "name_search="+$('#name_search').val()+"&plan_type="+$('#plan_type').val()+"&plan_id="+$('#plan_id').val()+"&status_search="+$('#status_search').val()+"&from_date="+$('#from_date').val()+"&to_date="+$('#to_date').val()+"";
        window.location="{{ url('admin/payments/payment_exports') }}?"+ff;
    });
    get_plans();
    function get_plans(plan_type='') {
        $.ajax({
            url: "{{ route('payments.get_plans') }}", 
            data : { type : plan_type },
            dataType : 'html',
            success: function(result){
                $("#plan_id").html(result);
            }
        });
    } 
    function send_invoice(payment_id) {
        $.confirm({
            title: 'Send Invoice',
            content: 'Are you sure you send invoice!',
            buttons: {
                btnClass: 'btn-blue',
                confirm: function () {
                    $.ajax({
                        url: "{{ route('payments.send_invoice') }}", 
                        data : { payment_id : payment_id },
                         beforeSend: function(){
                            // $('#model_loader').removeClass('model_loader_class');
                            // $('#model_loader').addClass('loader_image_heder');
                        },
                        success: function (result) {
                            if(result.status==true){
                                // $('#model_loader').addClass('model_loader_class');
                                // $('#model_loader').removeClass('loader_image_heder');
                                toastr.success(result.message);
                                //$('#data_table').DataTable().ajax.reload();
                            }else{
                                toastr.error(result.message);
                            }
                        },
                        error: function (error) {
                            toastr.error(error.message);
                            console.log(error.responseJSON.message);
                        }
                    });
                },
                cancel: function () {



                    btnClass: 'btn-blue'
                },
            }
        });
    } 
    function download_invoice(payment_id) {
        window.location="{{ route('payments.download_invoice') }}?payment_id="+payment_id;
    }
</script>
@endpush