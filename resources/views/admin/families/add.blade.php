@extends('layout.admin')
@section('content')

<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<div class="d-lg-flex align-items-center">
    <div class="position-relative">
        <h5 class="page_heading"> Add New Families </h5>
    </div>
    <div class="ms-auto"> {!! backAction(route($page.'.index') , 'Back') !!} </div>
</div>
<hr class="page_row">

<div class="card">
    <div class="card-body">
       
        @include('admin.'.$page.'.form')
        
    </div>
</div>
@endsection