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
                        <td> Title  </td>
                        <td>{{$data->title ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Page Name</td>
                        <td>{{$data->page_name ?? '-'}} </td>
                    </tr>
                    <tr>
                        <td>Page Slug </td>
                        <td>{{$data->page_slug ?? '-'}} </td>
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
                        <td>Description </td>
                        <td>{!! $data->description ?? '-' !!} </td>
                    </tr>
                    <tr>
                        <td> Meta Keyword </td>
                        <td>{{!empty($data->meta_keyword) ? $data->meta_keyword : '-'}}</td>
                    </tr>
                    <tr>
                        <td> Meta Description </td>
                        <td>{!! !empty($data->meta_description) ? $data->meta_description : '-' !!}</td>
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