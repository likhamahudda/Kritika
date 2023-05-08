@extends('layout.admin')
@section('content')
@push('css')
<link rel="stylesheet" href="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.css')}}" />

@endpush

<div class="d-flex justify-content-between">
<h5 class="page_heading">{{__('Users Report')}}  </h5>
</div>
<hr class="page_row">
<div class="card">
    <div class="card-body">
        <div class="card-block table-border-style">
            <table id="data_table" class="display" cellspacing="0" width="100%" class="table-responsive">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="row col-sm-10">
                        <div class="col-sm-3">
                            <input type="name" class="form-control" name="name_search" id="name_search" placeholder="Search by user name">
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" name="status_search" id="status_search">
                                <option value="">Search by Status</option>
                                <option value="1">Active</option>
                                <option value="0">In-active</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" name="from_date" id="from_date">
                        </div>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" name="to_date" id="to_date">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-outline-primary export_csv">Export in CSV</button>
                    </div>
                </div>
                <thead>
                    <tr>
                        <th>#</th>
                        <th> {{__('Name')}} </th>
                        <th> {{__('Email')}} </th>
                        <th> {{__('Phone No.')}} </th>
                        <th> {{__('Status')}} </th>
                        <th> {{__('Register Date')}}</th>
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
            //searching: false,
            "order": [
                [0, "desc"]
            ], // order by desc 
            "bProcessing": true,
            "serverSide": true,
            "lengthMenu": [10,50,100,500],

            ajax: {
                url: "{{ route($page.'.users_report') }}",
                data: function (d) {
                    d.name_search = $('#name_search').val();
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
                    mData: 'email'
                },

                {
                    mData: 'mobile'
                },

                {
                    mData: 'status'
                },
                {
                    mData: 'updated_at'
                },

                // {
                //     mData: 'actions'
                // },
            ],
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": false
            }, ],
        });
        $('#name_search').keyup(function(){
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
        var ff = "name_search="+$('#name_search').val()+"&status_search="+$('#status_search').val()+"&from_date="+$('#from_date').val()+"&to_date="+$('#to_date').val()+"";
        window.location="{{ url('admin/reports/users_export') }}?"+ff;
    });
</script>
@endpush