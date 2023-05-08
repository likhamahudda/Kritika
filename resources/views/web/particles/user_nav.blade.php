<header class="full header">
  <div class="navigation-wrap start-header start-style">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="navbar navbar-expand-md navbar-light">
            <a class="navbar-brand" href="<?php echo url('/user'); ?>">
            <img src="{{URL::asset('images/logo.svg')}}" alt=""></a>  
            <span class="typeuser">-
            @if(Auth()->guard('user')->user()->user_type == 1)
            Seller
            @else
            Buyer
            @endif
          </span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav py-4 py-md-0">
              @if(Auth()->guard('user')->user()->user_type == 1)
                <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                  <a class="nav-link" href="<?php echo url('/seller/products'); ?>">Product</a>                  
                </li> 
                @else
                <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                  <a class="nav-link" href="<?php echo url('/buyer/request_for_quote'); ?>">Create RFQ</a>                  
                </li> 
                @endif
              </ul>
            </div>
            <div class="header-btn ml-3 pl-3">
            <div class="use-img-top dropdown">
                <a href="#" class="d-flex dropdown-toggle " data-toggle="dropdown">
                  <div class="pro-img">
                  
                    @if(Auth()->guard('user')->user()->image)
                            <img src="{{url('uploads/profile_img/')}}/{{Auth()->guard('user')->user()->image}}" alt="">
                            @else
                            <img src="{{url('images/dummy-user.png')}}" alt="">
                            @endif

                  </div>
                  <p>{{Auth()->guard('user')->user()->first_name}}</p>
                </a>
                <div class="dropdown-menu">                  
                  <a class="dropdown-item" href="{{route('user.editprofile')}}"><i class="fa-solid fa-user"></i>Profile Info</a>
                  <a class="dropdown-item" href="{{route('user.change_password')}}"><i class="fa-solid fa-lock"></i>Change password</a>                 
                  <a class="dropdown-item confirm_logout" href="#"><i class="fa-solid fa-power-off"></i>Log Out</a> 
                 </div>
            </div>
            </div>
          </nav>    
        </div>
      </div>
      
    </div>
  </div>
</header>