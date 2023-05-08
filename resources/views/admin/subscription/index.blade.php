@extends('layout.admin')
@section('content')
@push('css')
<link rel="stylesheet" href="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.css')}}" />

@endpush

<div class="d-flex justify-content-between">
<h5 class="page_heading">{{__('Subscription Plans List')}}  </h5>
<div class="col-md-5 row">
    <div class="col-md-6">
        <button type="button" class="btn btn-outline-primary rounded-0 model_open" url="{{route('subscription.mostpopular')}}">{{__('Select Most Popular Plan')}} </button>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn btn-outline-primary rounded-0 model_open" url="{{route('subscription.form')}}"> <i class="bx bxs-plus-square"> </i> {{__('Add Subscription Plan')}} </button>
    </div>
</div>
</div>
<hr class="page_row">
<div class="card">
    <div class="card-body">
        <div class="card-block table-border-style">
            <table id="data_table" class="display" cellspacing="0" width="100%" class="table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th> {{__('Subscription Plan Name')}} </th>
                        <th> {{__('Subscription Type')}} </th>
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
            "order": [
                [0, "desc"]
            ], // order by desc 
            "bProcessing": true,
            "serverSide": true,
            "lengthMenu": [10,50,100,500],
            "pageLength": 50,

            ajax: {
                url: "{{ route($page.'.index') }}",
                error: function() {
                    $.alert('something_went_wrong!');

                }
            },

            "aoColumns": [{
                    mData: 'id'
                },

                {
                    mData: 'plan_name'
                },

                {
                    mData: 'subscription_type'
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

    });
</script>
@endpush