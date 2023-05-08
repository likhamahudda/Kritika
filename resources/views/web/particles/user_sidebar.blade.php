@php
use App\Models\Signature;
$user_id = Auth::guard('user')->user()->id;

$signaure = Signature::where('user_id', $user_id)->where('signature_type', '1')->orderBy('id', 'desc')->limit(1)->first();

$initials = Signature::where('user_id', $user_id)->where('signature_type', '2')->orderBy('id', 'desc')->limit(1)->first();

@endphp
<div class="left-bar flex-right">
    <!--side bar-->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="">
                <!-- .btn-navbar is used as the toggle for collapsed navbar content -->

                <div class="nav-collapse">
                    <aside class="side-left">
                        <div class="user-group ">
                            <div class="user-img">
                            </div>
                            <div class="user-details">
                                <p class="user-name update_name_submit">{{Auth::guard('user')->user()->first_name.' '.Auth::guard('user')->user()->last_name}} <span class="hidepricingios">( Free Member)</span>
                                </p>
                                <div class="dropdown-user dropdown-user_new icon-under-name" role="menu" aria-labelledby="dLabel">
                                    <!-- <li class="user-status"><i class="icon-cog"></i> Details</li> -->
                                    <span><a href="{{route('user.editprofile')}}" title="My Profile"><i class="far fa-user"></i></a></span>
                                    <span><a href="{{route('user.editprofile')}}" title="Settings"><i class="fas fa-user-cog"></i></a></span>
                                    <span class="hidepricingios"><a href="{{route('user.editprofile')}}" title="My Transactions"><i class="far fa-credit-card"></i></a></span>
                                    <!-- <span><a class="confirm_logout" style="cursor: pointer;" title="Logout"><i class="icon-off"></i></a></span> -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>

                        <div class="current-plan">
                            <h4>Current Plan</h4>
                            <h5>Subscription : <span>Free</span></h5>
                            <div class="cub-btn">
                                <button type="button" class="btn btn-blue">Upgrade Now</button>
                            </div>
                        </div>


                        <div class="clearfix"></div>
                        <div class="signature-main-box">
                            <!--always define class .first for first-child of li element sidebar left-->

                            <ul class="signature-back" role="menu" aria-labelledby="dLabel">
                                <li>
                                    <div class="signature-main">
                                        <h1 class="Sign-heading">Your Signature</h1>

                                        <div class="signature-image" id="create_sign_disp_ajax">
                                            @if(isset($signaure->signature_image) && !empty($signaure->signature_image))
                                            <div id="edit_user_sign" class="edit_sign_cont text-center">
                                                <a href="javascript:void(0);" id="edit_sign_link" class="edit-icon" title="Edit Signature"><i class="icon-pencil"></i></a>
                                                <img class="edit_this_img" src="{{url('uploads/signature')}}/{{$signaure->signature_image}}">
                                            </div>
                                            @else
                                            <a class="add-sign-init" href="javascript:void(0);" id="add_sign" title="Add Signature"><i class="icon-plus-sign"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="signature-main">
                                        <h1 class="Sign-heading">Your initials</h1>

                                        <div class="signature-image" id="create_initial_disp_ajax">
                                            @if(isset($initials->signature_image) && !empty($initials->signature_image))
                                            <div id="edit_user_initial" class="edit_sign_cont text-center">
                                                <a href="javascript:void(0);" id="edit_initial_link" class="edit-icon" title="Edit Initial"><i class="icon-pencil"></i></a>
                                                <img class="edit_this_img" src="{{url('uploads/signature')}}/{{$initials->signature_image}}">
                                            </div>
                                            @else
                                            <a class="add-sign-init" href="javascript:void(0);" id="add_initial" title="Add Initial"><i class="icon-plus-sign"></i></a>
                                            @endif
                                        </div>

                                    </div>
                                </li>
                            </ul>

                            <div class="clearfix"></div>
                        </div>

                        <div id="">

                        </div>
                        <div class="create-folder-main">
                            <a href="javascript:void(0)" id="add_folder_modal" title="Create Folder">
                                Create Folder <i class="icon-plus-sign"></i>
                            </a>
                        </div>
                        <ul class="create-folder-list">
                            
                        </ul>
                    </aside>
                </div>

            </div>
        </div>
    </div>
    <!--/side bar -->
</div>