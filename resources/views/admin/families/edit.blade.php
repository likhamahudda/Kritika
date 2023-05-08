@extends('layout.admin')
@section('content')

<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<div class="card">
<div class="card-header">
        <div class="d-lg-flex align-items-center mb-2 mt-2">
            <div class="position-relative">
                <h5> {{$title ?? ''}} </h5>
            </div>
            <div class="ms-auto"> {!! backAction(route($page.'.index') , 'Back') !!} </div>
        </div>
    </div>
    <div class="card-body">
         @include('admin.'.$page.'.edit_form') 
        
    </div>
</div> 
@endsection