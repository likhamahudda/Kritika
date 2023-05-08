<header class="header top-bar-toggle-header">
    <!--nav bar helper-->
    <div class="navbar-helper">
        <div class="row-fluid d-flex">
            <!--panel site-name-->
            <div class="left-bar-nav">
                <div class="panel-sitename dash-main-logo">
                    <div class="toggle-button">
                        <a id="toggle_button" href="javascript:void(0)">
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                    </div>

                    <a href="{{route('user.index')}}">
                        <img src="https://esign.orbitnapp.com/Front-html/images/web-logo.svg" alt="">
                    </a>


                </div>
            </div>
            <div class="top-menu">
                <ul class="sidebar-top">
                    <li class="">
                        <a href="{{url('assignments/cassignmentDocs')}}" class="corner-all">                            
                            <span class="sidebar-text">Sign Document</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="/templates/list/" class="corner-all">                            
                            <span class="sidebar-text">Templates</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{url('assignments/list')}}" class="corner-all">                            
                            <span class="sidebar-text">Documents</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="corner-all help-screen-open">                            
                            <span class="sidebar-text">Help</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!--/panel name-->
            <div class="dropdown pull-left upgrade_btn_drop">
                <button class="upgradebtn_head dropdown-toggle btn btn-blue" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Upgrade                    
                </button>
                <ul class="dropdown-menu pull-left hidepricingios" aria-labelledby="dropdownMenu4">
                    <!-- <li>
                        <a href="javaScript:void(0)"  data-type="paid" data-ptime="yearly" data-target="157a9b96b146a1ccd114b0b8d5c03326" class="btnselfupgrade">
                            Pro Plan($11/month) Yearly
                        </a>   
                    </li>
                    <li>
                        <a href="javaScript:void(0)" data-type="paid" data-ptime="yearly" data-target="59acdb98cd52cea59d2b8950d30e2838" class="btnselfupgrade">
                        Business Plan($15/month) Yearly
                        </a>   
                    </li>
                <li><a href="/pricing/yearly">View All Plans</a></li>
            -->

                    <li style="display:none">

                        <a href="javaScript:void(0)" data-type="paid" data-ptime="yearly" data-target="026323cee4acbd54c6ce1bb5ab7f4136" class="btnselfupgrade">

                            Solo($5/month) Yearly

                        </a>

                    </li>

                    <li>

                        <a href="javaScript:void(0)" data-type="paid" data-ptime="yearly" data-target="9b7021aa6f43505a760a75f289230e60" class="btnselfupgrade">

                            Professional Plan($10/month) Yearly

                        </a>

                    </li>

                    <li>

                        <a href="javaScript:void(0)" data-type="paid" data-ptime="yearly" data-target="66cac4db8e57ecee94aa5b245a7f3c0b" class="btnselfupgrade">

                            Business (Team) Plan($20/month) Yearly

                        </a>

                    </li>

                    <li>

                        <a href="/contact-us" class="btnselfupgrade1">

                            Enterprise Plans

                        </a>

                    </li>



                    <li><a href="/pricing">View All Plans</a></li>
                </ul>
            </div>

            <div class="header-right">

                <!--panel button ext-->
                <div class="panel-ext main-overlay-disp">
                    @php
                        use App\Models\UserEmails;
                        $signing_requests = UserEmails::where('read_unread','0')->where('receiver_id',Auth::guard('user')->user()->id)->get();
                    @endphp
                    <div class="dropdown">
                        <a class="notif-btn dropdown-toggle" title="Signing requests" data-toggle="dropdown" href="#">
                            <i class="icon-envelope"></i>
                            @if(isset($signing_requests) && count($signing_requests) > 0)
                                <span class="bg-green">{{count($signing_requests)}}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-user user_notify_list" role="menu" aria-labelledby="dLabel">
                            
                            @if(isset($signing_requests) && count($signing_requests) > 0)
                            <li class="user-status"><i class="icon-envelope"></i>Signing requests</li>
                            @foreach($signing_requests as $sign_request)
                            <li>
                                <a target="_blank" href="{{url('request/signdoc/?request=').$sign_request->request_id}}">
                                    <div class="media">
                                        <img src="{{URL::asset('esign_example/frontend-theme/img/user.jpg')}}" class="media-object pull-left" data-src="js/holder.js/30x30">

                                        <div class="media-body">
                                            <h6 class="media-heading">{{$sign_request->sender_email}}</h6>

                                            <p> {{date('M d, Y h:i:A',strtotime($sign_request->created_at));}}</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                            @else
                            <li>
                                <a class="text-center" href="javascript:void(0);">
                                    No Signing Request Yet </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="dropdown">

                        <a class="notif-btn dropdown-toggle" title="Notifications &amp; messages" data-toggle="dropdown" href="#">
                            <i class="icon-bell-alt"></i> </a>
                        <ul class="dropdown-menu dropdown-user user_notify_list" role="menu" aria-labelledby="dLabel">
                            <li class="user-status"><i class="icon-bell-alt"></i>Notification</li>
                            <li>
                                <a class="text-center" href="javascript:void(0);">
                                    No Notification yet
                                </a>
                            </li>
                        </ul>


                    </div>
                    <div class="dropdown sign-out-btn-header">
                        <a title="Logout" class="notif-btn dropdown-signout confirm_logout" style="cursor: pointer;">
                            <i class="icon-off"></i>
                        </a>
                    </div>
                    <!--panel button ext-->


                </div>
                <!--panel button ext-->
            </div>

        </div>
        <!--/row-fluid-->
    </div>
    <!--/nav bar helper-->
</header>