<div class="modal-header model_loader">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{{ Form::open(['url' => route('content.manage'), 'class' => 'form_submit', 'method'=>'post', 'files' => 'yes']) }}
<div class="modal-body">
    <div class="row">


        <div class="col-md-12">
            <div class="row">
                <div class="col mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Title')}}*</label>
                    {{ Form::text('title',$data->title ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Title']) }}
                </div>
                {!! Form::hidden('id', $data->id ?? '') !!}

                <div class="col mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Slug')}}*</label>
                    {{ Form::text('slug',$data->slug ?? '' ,['class' =>'form-control', 'placeholder' =>'Enter Slug']) }}
                </div>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="nameBackdrop" class="form-label">{{__('Mobile')}}*</label>
                    <textarea name="description" class="ck_editor">
        &lt;p&gt;{{ $data->description ?? ''}} &lt;/p&gt;
    </textarea>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Close
    </button>
    <button type="submit" class="btn btn-primary submit_btn"> Submit </button>
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        ClassicEditor
            .create(document.querySelector('.ck_editor'))
            .catch(error => {
                console.error(error);
            });
    });
</script>