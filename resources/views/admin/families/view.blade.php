<style> 
    /* Style the active tab */
/* Set tab background color */
.nav-tabs > li{
  background-color: #f1f1f1;
}

/* Set tab text color */
.nav-tabs > li {
  color: #333;
  margin-left: 20px;
}

/* Set active tab background color */
.nav-tabs > li.active > a, 
.nav-tabs > li.active > a:hover, 
.nav-tabs > li.active > a:focus {
  background-color: #fff;
}

/* Set active tab text color */
.nav-tabs > li.active > a, 
.nav-tabs > li.active > a:hover, 
.nav-tabs > li.active > a:focus {
  color: #333;
}

/* Set border color */
.nav-tabs > li > a {
  border-color: #ddd;
  font-size: 17px;
}

/* Set hover effect for all tabs */
.nav-tabs > li > a:hover {
  background-color: #ddd;
  color: #333;
  border-color: #ddd; 
}

/* Remove underline for active tab */
.nav-tabs > li.active > a, 
.nav-tabs > li.active > a:hover, 
.nav-tabs > li.active > a:focus {
  border-bottom: none;
}


</style>


<div class="modal-header">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">


    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#general_tab" aria-controls="general_tab" role="tab" data-toggle="tab" class="sys_temp_tab" data-type="1">General Details</a>
            </li>
             
            <li role="presentation" class=""><a href="#subscription_tab" aria-controls="subscription_tab" role="tab" data-toggle="tab" class="sys_temp_tab" data-type="2">Family Members</a>
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
                                    <td> <b>Head of Family</b>  </td>
                                    <td>{{$data->head_family }}</td>
                                </tr>
                                <tr>
                                    <td><b>Head Father Name</b> </td>
                                    <td>{{$data->head_father_name ?? '-'}} </td>
                                </tr>
                                <tr>
                                    <td><b>Sub Caste(Gotar)</b> </td>
                                    <td>{{$data->sub_caste ?? '-'}} </td>
                                </tr>
                                <tr>
                                    <td><b>Kulrishi</b> </td>
                                    <td>{{$data->kulrishi ?? '-'}} </td>
                                </tr>
                                <tr>
                                    <td><b>Kuldevi</b> </td>
                                    <td>{{$data->kildevi ?? '-'}} </td>
                                </tr>
                                <tr>
                                    <td><b>Gender</b> </td>
                                    <td>@if($data->gender=='male') Male @elseif($data->gender=='female') Female @else - @endif</td>
                                </tr>

                                <tr>
                                    <td><b>Country</b> </td>
                                    <td>{{$data->cname ?? '-'}} </td>
                                </tr>

                                <tr>
                                    <td><b>State</b> </td>
                                    <td>{{$data->sname ?? '-'}} </td>
                                </tr>

                                <tr>
                                    <td><b>District</b> </td>
                                    <td>{{$data->dname ?? '-'}} </td>
                                </tr>

                                <tr>
                                    <td><b>City/Tehsil</b> </td>
                                    <td>{{$data->tname ?? '-'}} </td>
                                </tr>

                                <tr>
                                    <td><b>Gram panchayat</b> </td>
                                    <td>{{$data->pname ?? '-'}} </td>
                                </tr>

                                <tr>
                                    <td><b>Village Name</b> </td>
                                    <td>{{$data->vname ?? '-'}} </td>
                                </tr>

                                <tr>
                                    <td><b>House in The Village</b> </td>
                                    <td>{{$data->house_village ?? '-'}} </td>
                                </tr>
                                <tr>
                                    <td><b>Panchayat Committee</b> </td>
                                    <td>{{$data->panchayat_committee ?? '-'}} </td>
                                </tr>
                                <tr>
                                    <td><b>Pin Code</b> </td>
                                    <td>{{$data->pin_code ?? '-'}} </td>
                                </tr>

                               
                                <tr>
                                    <td><b>Status </b></td>
                                    <td>@if($data->status==1) Active @else Inactive @endif</td>
                                </tr>
                               
                                <tr>
                        <td><b>Created Date </b>  </td>
                        <td> {{$data->created_at!='' ? default_date_format($data->created_at) : '-'}} </td>
                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="subscription_tab">
                <div class="row">
                    <div class="col-md-12 col-sm-12 table-responsive">
                        @if(count($data_familyMember)>0 || count($data_familyMember)>0)
                        <table class="table table-hover">
                            <tbody>                                    
                                <thead>
                                   
                                    <th> Name  </th>
                                    <th>Relation Select </th>
                                    <th> Relation With  </th>
                                    <th> Education</th>

                                    <th> Occupation</th>
                                    <th> Gender</th>
                                    <th> Phone Number</th>
                                    <th> Email</th>

                                    <th> Married Status</th>
                                    <th> D.O.B</th>
                                    <th> Age(Years)</th>
                                    <th> Current Address</th>
                                </thead>
                                @foreach($data_familyMember as $row)
                                <tr>
                                    
                                    <td>{{$row->member_name ?? '-'}}</td>
                                    <td>{{$row->relation_type ?? '-'}}</td>
                                    <td>{{$row->relation_name_with ?? '-'}}</td>
                                    <td>{{$row->ename ?? '-'}}</td>
                                    <td>{{$row->oname ?? '-'}}</td>
                                    
                                    <td>{{$row->gender_type ?? '-'}}</td>
                                    <td>{{$row->phone_no ?? '-'}}</td>
                                    <td>{{$row->email_address ?? '-'}}</td>
                                    <td>{{$row->married_status ?? '-'}}</td>

                                    <td>{{$row->dob ?? '-'}}</td>
                                    <td>{{$row->age ?? '-'}}</td>
                                    <td>{{$row->address ?? '-'}}</td>
                                     

                                    
                                </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                        @else
                        <div align="center"><b>No Members Added Yet</b></div>
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


