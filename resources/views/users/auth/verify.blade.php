
@extends('layout.user_auth')
@section('content')
<section class="register-box">
    <div class="signup-form-box login-form-box">
        <div class="login-form show" [class]="visible_login ? 'login-form hide' : 'login-form show'">  
@if ($message = Session::get('success'))
  <strong>{{ $message }}</strong>
@elseif ($message = Session::get('error'))
  <strong style="color:red">{{ $message }}</strong>
@else
<script>window.location = "{{ route('user.login') }}";</script>
@endif
     </div>
    <div class="login-button">
        <a href="{{route('user.login')}}" class="btn btn-blue">Back to Login page</a>
    </div>
        
    </div>
</section>
@endsection
