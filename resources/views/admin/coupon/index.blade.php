@extends('layout.admin')
@section('content')
@push('css')
<link rel="stylesheet" href="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.css')}}" />

@endpush

<div class="d-flex justify-content-between">
<h5 class="page_heading">{{__('Coupon Code List')}}  </h5>
<div>
    <button type="button" class="btn btn-outline-primary rounded-0 model_open" url="{{route('coupon.form')}}"> <i class="bx bxs-plus-square"> </i> {{__('Add Coupon Code')}} </button>
</div>
</div>
<hr class="page_row">
<div class="card">
    <div class="card-body">
        <div class="card-block table-border-style">
            <table id="data_table" class="display" cellspacing="0" width="100%" class="table-responsive">
                <div class="row" style="margin-bottom: 15px;">
                    <!-- <div class="row col-sm-10"> -->
                        <div class="col-sm-3">
                            <label>Coupon Code</label>
                            <input type="name" class="form-control" name="name_search" id="name_search" placeholder="Search by coupon code">
                        </div>
                        <div class="col-sm-3">
                            <label>Coupon type</label>
                            <select class="form-control" name="type_search" id="type_search">
                                <option value="">Search by coupon type</option>
                                <option value="0">Subscription</option>
                                <option value="1">Template</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Status</label>
                            <select class="form-control" name="status_search" id="status_search">
                                <option value="">Search by Status</option>
                                <option value="1">Active</option>
                                <option value="0">In-active</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date">
                        </div>
                        <div class="col-sm-2">
                            <label>Expiry Date</label>
                            <input type="date" class="form-control" name="expiry_date" id="expiry_date">
                        </div>
                    <!-- </div>
                    <div class="col-sm-2" align="center">
                        <button class="btn btn-outline-primary export_csv">Export in CSV</button>
                    </div> -->
                </div>
                <thead>
                    <tr>
                        <th>#</th>
                        <th> {{__('Coupon Code')}} </th>
                        <th> {{__('Price')}} </th>
                        <th> {{__('Start Date')}} </th>
                        <th> {{__('Expiry Date')}} </th>
                        <th> {{__('Coupon Type')}} </th>
                        <th> {{__('Status')}} </th>
                        <th> {{__('Created Date')}}</th>
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
                    d.type_search = $('#type_search').val();
                    d.status_search = $('#status_search').val();
                    d.start_date = $('#start_date').val();
                    d.expiry_date = $('#expiry_date').val();
                },
                error: function() {
                    $.alert('something_went_wrong!');

                }
            },

            "aoColumns": [{
                    mData: 'id'
                },

                {
                    mData: 'code'
                },

                {
                    mData: 'price'
                },

                {
                    mData: 'start_date'
                },

                {
                    mData: 'expiry_date'
                },

                {
                    mData: 'type'
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
                "aTargets": [-1,-3]
            }, ],
        });
        $('#name_search').keyup(function(){
            table.draw();
        });
        $('#type_search').on('change',function(){
            table.draw();
        });
        $('#status_search').on('change',function(){
            table.draw();
        });
        $('#start_date').on('change',function(){
            table.draw();
        });
        $('#expiry_date').on('change',function(){
            table.draw();
        });
    });
</script>
@endpush