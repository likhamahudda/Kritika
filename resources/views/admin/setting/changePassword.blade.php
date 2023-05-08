<div class="modal-header model_loader">
    <h5 class="modal-title" id="backDropModalTitle"> {{__($title ?? '-')}} </h5>
    <img class="model_loader_class" src="{{URL::asset('images/model_loader.gif')}}" alt="Loader.gif" id="model_loader">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{{ Form::open(['url' => route('admin.updatePassword'), 'class' => 'form_submit', 'method'=>'post']) }}
<div class="modal-body">
    <div class="row">


<div class="col-md-12">
    <div class="row">
        <div class="col mb-3">
            <label for="nameBackdrop" class="form-label">{{__('Current Password')}}*</label>
            {{ Form::password('current_password' ,['class' =>'form-control', 'placeholder' =>'Current Password','minlength'=>'8','maxlength'=>'12']) }}
        </div>
        {!! Form::hidden('id', $data->id ?? '') !!}
        <div class="col mb-3">
            <label for="nameBackdrop" class="form-label">{{__('Password')}}*</label>
            {{ Form::password('password' ,['class' =>'form-control', 'id' =>'password', 'placeholder' =>'Password','minlength'=>'8','maxlength'=>'12']) }}
        </div>

        <div class="col mb-3">
            <label for="nameBackdrop" class="form-label">{{__('Confirm Password')}}*</label>
            {{ Form::password('confirm_password' ,['class' =>'form-control', 'placeholder' =>'Confirm Password','minlength'=>'8','maxlength'=>'12']) }}
        </div>

    </div>

   
</div>

</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Close
    </button>
    <button type="submit" class="btn btn-primary submit_btn"> Update Password </button>
</div>
{{ Form::close() }}
