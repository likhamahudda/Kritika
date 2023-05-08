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
                        <td> Template Title  </td>
                        <td>{{$data->title ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Template Category </td>
                        <td>{{$data->name ?? '-'}} </td>
                    </tr>
                    <tr>
                        <td>Template Type</td>
                        <td>@if($data->type==1) Paid @else Free @endif </td>
                    </tr>
                    <tr>
                        <td>Price </td>
                        <td>{{$data->price!=0 ? Constant::CURRENCY.$data->price : '-'}} </td>
                    </tr>
                    <tr>
                        <td>Description  </td>
                        <td>{!! nl2br($data->description) ?? '-' !!}</td>
                    </tr>
                    <tr>
                        <td>Template PDF File </td>
                        <td> 
                            <a target="_blank" title="View PDF" href="{{URL::asset('uploads/template_pdf').'/'.$data->file}}" class="" id=""> {{$data->file}} <!-- <i class="bx bx-show model_open"></i> --> </a>
                        </td>
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