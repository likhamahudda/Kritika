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
                        <td> Coupon Code  </td>
                        <td>{{$data->code ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td> Coupon Price  </td>
                        <td>{{$currency.$data->price ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td> No. of Use  </td>
                        <td>{{$data->no_of_use ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Start Date  </td>
                        <td> {{$data->start_date ? date('j F Y',strtotime($data->start_date)) : '-'}} </td>
                    </tr>
                    <tr>
                        <td>Expiry Date  </td>
                        <td> {{$data->expiry_date ? date('j F Y',strtotime($data->expiry_date)) : '-'}} </td>
                    </tr>
                    <tr>
                        <td>Coupon Type </td>
                        <td>@if($data->type==1) Template @else Subscription @endif</td>
                    </tr>
                    <tr>
                        <td> Coupon Description  </td>
                        <td>{!! nl2br($data->description) ?? '-' !!}</td>
                    </tr>
                    <tr>
                        <td>Status </td>
                        <td>@if($data->status==1) Active @else Inactive @endif</td>
                    </tr>
                    <tr>
                        <td>Created Date  </td>
                        <td> {{$data->created_at!='' ? default_date_format($data->created_at) : '-'}} </td>
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