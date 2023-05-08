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
                        <td> Subscription Plan Name  </td>
                        <td>{{$data->plan_name ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Subscription Type </td>
                        <td>@if($data->subscription_type==1) Account Plan @else API Plan @endif</td>
                    </tr>
                    <tr>
                        <td> Monthly Price  </td>
                        <td>{{$data->monthly_price.Constant::CURRENCY.' per month' ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td> Yearly Price  </td>
                        <td>{{$data->plan_yearly_but_montly_price.Constant::CURRENCY.' per month' ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Status </td>
                        <td>@if($data->status==1) Active @else Inactive @endif</td>
                    </tr>
                    <tr>
                        <td>Created Date  </td>
                        <td> {{$data->created_at!='' ? default_date_format($data->created_at) : '-'}} </td>
                    </tr>
                    <tr>
                        <td><b>Features</b>  </td>
                        <td>
                            <ul>
                                @foreach ($data_f as $row)
                                    <li>@if($row->type!=0) @if($row->count==-1) Unlimited @else {{$row->count}} @endif @endif {{$row->description}}</li>
                                @endforeach
                            </ul>
                        </td>
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