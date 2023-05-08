<script src="https://kit.fontawesome.com/a02ea7a291.js" crossorigin="anonymous"></script>
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">

        <!-- <img src="{{URL::asset('images/logo.png')}}" class="logo-icon" alt="logo icon"> -->
        <a href="{{Route('admin.index')}}"><img id="site_logo" src="{{getSettingValue('logo') ? url('uploads/logo').'/'.getSettingValue('logo') : URL::asset('images/logo.png')}}" class="logo-icon0" alt="logo icon" width="200" height="60"></a>
        <div>
            <!-- <h4 class="logo-text">{{Constant::APP_NAME}}</h4> -->
        </div>


        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">

        <li>
            <a href="{{route('admin.index')}}">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">{{__('Dashboard')}}</div>
            </a>
        </li>

        <!-- <li class="menu-label"> Management </li> -->


        <li class="{{ Request::is('admin/families*') ? 'active' : '' }}">
            <a href="{{route('families.index')}}">
                <div class="parent-icon"><i class="bx bx-user"></i>
                </div>
                <div class="menu-title">{{__('Families')}}</div>
            </a>
        </li> 

           <li class="{{ Request::is('admin/countries*') ? 'active' : '' }}">
            <a href="{{route('countries.index')}}">
                <div class="parent-icon"><i class="bx bx-flag"></i>
                </div>
                <div class="menu-title">{{__('Countries')}}</div>
            </a>
        </li>

           <li class="{{ Request::is('admin/states*') ? 'active' : '' }}">
            <a href="{{route('states.index')}}">
                <div class="parent-icon"><i class="bx bx-globe"></i>
                </div>
                <div class="menu-title">{{__('States')}}</div>
            </a>
        </li>

        <li class="{{ Request::is('admin/districts*') ? 'active' : '' }}">
            <a href="{{route('districts.index')}}">
                <div class="parent-icon"><i class="bx bx-building"></i>
                </div>
                <div class="menu-title">{{__('Districts')}}</div>
            </a>
        </li>

        <li class="{{ Request::is('admin/tehsils*') ? 'active' : '' }}">
            <a href="{{route('tehsils.index')}}">
                <div class="parent-icon"><i class="bx bx-current-location"></i>
                </div>
                <div class="menu-title">{{__('Tehsils')}}</div>
            </a>
        </li>

        <li class="{{ Request::is('admin/panchayat*') ? 'active' : '' }}">
            <a href="{{route('panchayat.index')}}">
                <div class="parent-icon"><i class="bx bx-compass"></i>
                </div>
                <div class="menu-title">{{__('Panchayat')}}</div>
            </a>
        </li>

        <li class="{{ Request::is('admin/village*') ? 'active' : '' }}">
            <a href="{{route('village.index')}}">
                <div class="parent-icon"><i class="bx bx-home"></i>
                </div>
                <div class="menu-title">{{__('Village')}}</div>
            </a>
        </li>

        <li class="{{ Request::is('admin/education*') ? 'active' : '' }}">
            <a href="{{route('templateCategory.index')}}">
                <div class="parent-icon"><i class="bx bx-book"></i>
                </div>
                <div class="menu-title">{{__('Education Master')}}</div>
            </a>
        </li>
        <li class="{{ Request::is('admin/occupations*') ? 'active' : '' }}">
            <a href="{{route('testimonial.index')}}">
                <div class="parent-icon"><i class="bx bx-briefcase"></i>
                </div>
                <div class="menu-title">{{__('Occupations Master')}}</div>
            </a>
        </li>


        
        <!-- <li>
            <a href="{{route('emailTemplate.index')}}">
                <div class="parent-icon"><i class="bx bx-envelope"></i>
                </div>
                <div class="menu-title">{{__('Email Template')}}</div>
            </a>
        </li> 
        <li>
            <a href="{{route('pageManager.index')}}">
                <div class="parent-icon"><i class="bx bx-file"></i>
                </div>
                <div class="menu-title">{{__('Page Management')}}</div>
            </a>
        </li> 
        <li>
            <a href="{{route('faqs.index')}}">
                <div class="parent-icon"><i class="bx bx-question-mark"></i>
                </div>
                <div class="menu-title">{{__('FAQs Management')}}</div>
            </a>
        </li> 
        <li>
            <a href="{{route('coupon.index')}}">
                <div class="parent-icon"><i class="bx bx-gift"></i>
                </div>
                <div class="menu-title">{{__('Coupon Code Management')}}</div>
            </a>
        </li> 
        <li>
            <a href="{{route('testimonial.index')}}">
                <div class="parent-icon"><i class="bx bx-message-alt-detail"></i>
                </div>
                <div class="menu-title">{{__('Manage Testimonials')}}</div>
            </a>
        </li> 
        <li>
            <a href="{{route('subscription.index')}}">
                <div class="parent-icon"><i class="bx bx-id-card"></i>
                </div>
                <div class="menu-title">{{__('Manage Subscriptions')}}</div>
            </a>
        </li> 
        <li>
            <a href="{{route('payments.index')}}">
                <div class="parent-icon"><i class="bx bx-money"></i>
                </div>
                <div class="menu-title">{{__('Payment Management')}}</div>
            </a>
        </li> 
        <li>
            <a href="{{route('templateCategory.index')}}">
                <div class="parent-icon"><i class="bx bx-file"></i>
                </div>
                <div class="menu-title">{{__('Template Category')}}</div>
            </a>
        </li> 
        <li>
            <a href="{{route('template.index')}}">
                <div class="parent-icon"><i class="bx bx-file"></i>
                </div>
                <div class="menu-title">{{__('Manage Templates')}}</div>
            </a>
        </li>  -->

        <!-- @if(Auth::user()->id == 1)

        <li>
            <a href="{{route('role.index')}}">
                <div class="parent-icon"><i class="bx bx-lock"></i>
                </div>
                <div class="menu-title">{{__('Role Management')}}</div>
            </a>
        </li>


        <li>
            <a href="{{route('permission.index')}}">
                <div class="parent-icon"><i class="bx bx-lock"></i>
                </div>
                <div class="menu-title">{{__('Permission')}}</div>
            </a>
        </li>

        @endif
         -->
        <!-- <li>
            <a href="javascript:(0)">
                <div class="parent-icon"><i class="bx bx-bar-chart"></i>
                </div>
                <div class="menu-title">{{__('Report Management')}}</div>
                <i class='bx bxs-chevron-down arrow' ></i>
            </a>
            <ul class="sub-menu">
              <li><a href="{{route('reports.users_report')}}">Users Report</a></li>
              <li><a href="{{route('reports.reviews_report')}}">Reviews Report</a></li>
              <li><a href="{{route('reports.payment_report')}}">Payment Report</a></li>
              <li><a href="{{route('reports.trial_report')}}">Free trial Report</a></li>
            </ul>
        </li> -->
        <li>
            <a href="{{route('admin.setting')}}">
                <div class="parent-icon"><i class="bx bx-cog"></i>
                </div>
                <div class="menu-title">{{__('Settings')}}</div>
            </a>
        </li> 
        
    </ul>
    <!--end navigation-->
</div>