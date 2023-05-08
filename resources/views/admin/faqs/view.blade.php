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
                        <td> Question  </td>
                        <td>{{$data->faq ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td> Answer  </td>
                        <td>{!! $data->description ?? '-' !!}</td>
                    </tr>
                    <tr>
                        <td>FAQs Type </td>
                        <td>@if($data->type==1) Pricing @elseif($data->type==2) General @else FAQs Page @endif</td>
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