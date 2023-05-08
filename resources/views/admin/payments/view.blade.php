<div class="modal-header">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <td>User Name  </td>
                        <td>{{$data->user_name ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td> Subscription Plan </td>
                        <td>{{$data->plan_name ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td> Subscription Type</td>
                        <td>@if($data->subscription_type==1) Account Plan @else API Plan @endif</td>
                    </tr>
                    <tr>
                        <td> Monthly Price </td>
                        <td>{{$data->subcription_price ? Constant::CURRENCY.$data->subcription_price.' per month' : '-'}}</td>
                    </tr>
                    <tr>
                        <td> Duration </td>
                        <td>{{$data->subscription_months ?$data->subscription_months.' months' : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Total Amount </td>
                        <td>{{$data->total_subscription_price ? Constant::CURRENCY.$data->total_subscription_price : '-'}}</td>
                    </tr>
                    <tr>
                        <td> Transaction ID  </td>
                        <td>{{$data->transaction_id ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Payment Status </td>
                        <td>@if($data->transaction_status==1) Complete @else Failed @endif</td>
                    </tr>
                    <tr>
                        <td>Payment Date&Time </td>
                        <td> {{$data->created_at ?? '-'}} </td>
                    </tr>
                    <tr>
                        <td>Plan Start Date </td>
                        <td> {{date('j F Y', strtotime($data->created_at)) ?? '-'}} </td>
                    </tr>
                    <tr>
                        <td>Plan Expiry Date </td>
                        <td> {{date('j F Y', strtotime($data->expire_date)) ?? '-'}} </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Close
    </button>
</div>