<div class="modal-header">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">


    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#general_tab" aria-controls="general_tab" role="tab" data-toggle="tab" class="sys_temp_tab" data-type="1">General Details</a>
            </li>
             
            <li role="presentation" class=""><a href="#subscription_tab" aria-controls="subscription_tab" role="tab" data-toggle="tab" class="sys_temp_tab" data-type="2">Subscriptions Details</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="general_tab">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <table class="table table-hover">
                            <!-- <img src="{{$data->image ? URL::asset('uploads/user_profile').'/'.$data->image : URL::asset('images/default_user.png')}}" width="60"> -->
                            <tbody>
                                <tr>
                                    <td> Name  </td>
                                    <td>{{$data->first_name.' '.$data->last_name ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Email </td>
                                    <td>{{$data->email ?? '-'}} </td>
                                </tr>
                                <tr>
                                    <td>Phone No. </td>
                                    <td>{{$data->mobile ?? '-'}} </td>
                                </tr>
                                <tr>
                                    <td>Address </td>
                                    <td>{{$data->address ?? '-'}} </td>
                                </tr>
                                <tr>
                                    <td>Gender </td>
                                    <td>@if($data->gender==1) Male @elseif($data->gender==2) Female @else - @endif</td>
                                </tr>
                                <tr>
                                    <td>Date Of Birth</td>
                                    <td>{{$data->dob!=NULL ? date('j F Y',strtotime($data->dob)) : '-'}} </td>
                                </tr>
                                <tr>
                                    <td>Company </td>
                                    <td>{{$data->company!='' ? $data->company : '-'}} </td>
                                </tr>
                                <tr>
                                    <td>Industry </td>
                                    <td>@if($data->industry==1) Accounting 
                                        @elseif($data->industry==2) Air Transportation 
                                        @elseif($data->industry==3) Architect 
                                        @elseif($data->industry==4) Banking 
                                        @elseif($data->industry==5) Consulting 
                                        @elseif($data->industry==6) Education 
                                        @elseif($data->industry==7) Others 
                                        @else - @endif</td>
                                </tr>
                                <tr>
                                    <td>Using For </td>
                                    <td>@if($data->using_for==1) Signing, Sending, and requesting documents 
                                        @elseif($data->using_for==2) Integrating the Kodago-Vendors API
                                        @elseif($data->using_for==3) All of the Above 
                                        @elseif($data->using_for==4) I'm not Sure 
                                        @else - @endif</td>
                                </tr>
                                <tr>
                                    <td>Status </td>
                                    <td>@if($data->status==1) Active @else Inactive @endif</td>
                                </tr>
                                <tr>
                                    <td>Email Verification Status</td>
                                    <td>@if($data->verification_status==1) Verified @else Not Verified @endif </td>
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
            <div role="tabpanel" class="tab-pane" id="subscription_tab">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        @if(count($payment_active)>0 || count($payment_expire)>0)
                        <table class="table table-hover">
                            <tbody>                                    
                                <thead>
                                    <th> Status  </th>
                                    <th> Plan Name  </th>
                                    <th> Start On </th>
                                    <th> Expire On  </th>
                                    <th> Price($) </th>
                                </thead>
                                @foreach($payment_active as $row)
                                <tr>
                                    <td>Active</td>
                                    <td>{{$row->plan_name ?? '-'}}</td>
                                    <td>{{$row->created_at ? date('j F Y',strtotime($row->created_at)) : '-'}}</td>
                                    <td>{{$row->expire_date ? date('j F Y',strtotime($row->expire_date)) : '-'}}</td>
                                    <td>{{$currency.$row->total_subscription_price ?? '-'}}</td>
                                </tr>
                                @endforeach
                                @foreach($payment_expire as $row)
                                <tr>
                                    <td>Expired</td>
                                    <td>{{$row->plan_name ?? '-'}}</td>
                                    <td>{{$row->created_at ? date('j F Y',strtotime($row->created_at)) : '-'}}</td>
                                    <td>{{$row->expire_date ? date('j F Y',strtotime($row->expire_date)) : '-'}}</td>
                                    <td>{{$currency.$row->total_subscription_price ?? '-'}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div align="center"><b>No Subscription Purchased Yet</b></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Close
    </button>
</div>