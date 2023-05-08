
@php

use App\Models\AdminNotification;
 
 

$admin_notification = AdminNotification::where('deleted_at','=', null)
    ->orderBy('created_at', 'desc')
    ->limit(20)
    ->get();

    $total_admin_notification = AdminNotification::where('deleted_at','=', null)->where('status','=', '0') 
    ->count();

   

@endphp


<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
            </div>
            <div class="search-bar flex-grow-1">
            </div>
            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center">
                    

                    <li class="nav-item dropdown dropdown-large">
                        <a class="nav-link  dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
                            <span class="alert-count"><?php echo $total_admin_notification; ?></span>
                            <i class='bx bx-bell'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:;">
                                <div class="msg-header">
                                    <p class="msg-header-title">Notifications</p>
                                    <p class="msg-header-clear ms-auto notification_alert"><a class="notification_alert" href="javascript:void(0)">Marks all as read</a></p>
                                   
                                </div>
                            </a>
                            <div class="header-notifications-list">

                            @foreach($admin_notification as $row)

                            <?php 
                            
                            // The date and time to convert
                                    $datetime = $row->created_at;
                                    // Create a DateTime object for the given date and time
                                    $date = new DateTime($datetime);
                                    // Get the current time as a DateTime object
                                    $now = new DateTime();
                                    // Calculate the difference between the two dates
                                    $interval = $now->diff($date);
                                    // Format the output based on the difference
                                    if ($interval->y > 0) {
                                        $output = $interval->format('%y years ago');
                                    } elseif ($interval->m > 0) {
                                        $output = $interval->format('%m months ago');
                                    } elseif ($interval->d > 0) {
                                        $output = $interval->format('%d days ago');
                                    } elseif ($interval->h > 0) {
                                        $output = $interval->format('%h hours ago');
                                    } elseif ($interval->i > 0) {
                                        $output = $interval->format('%i minutes ago');
                                    } else {
                                        $output = 'just now';
                                    }

                                   // echo $output; // Output: "2 days ago"

                            ?>
                               <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify bg-light-danger text-danger"><i class="bx bx-bell"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                  <h6 class="msg-name">{{$row->title}} <span class="msg-time float-end">{{ $output }}</span></h6>
                                            <p class="msg-info">{{$row->message}}- {{$row->admin_name}}</p>
                                        </div>
                                    </div>
                                </a>

                                @endforeach
                             
                              
                            
                            </div>
                            <a href="javascript:;">
                                <div class="text-center msg-footer">View All Notifications</div>
                            </a>
                        </div>
                    </li>

                    


                </ul>
            </div>
            <div class="user-box dropdown">
                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img id="admin_profile" src="{{Auth::user()->image ? url('uploads/admin_profile').'/'.Auth::user()->image : URL::asset('images/default_user.png')}}" class="user-img" alt="user avatar">
                    <div class="user-info ps-3">
                        <p class="user-name mb-0"> {{Auth::user()->first_name ?? ''}} {{Auth::user()->last_name ?? ''}} </p>
                        <p class="designattion mb-0"> {!! ucwords(roleName()) !!} </p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    @can('Profile Admin')
                    <li><a class="dropdown-item" href="{{route('admin.profile')}}"><i class='bx bx-user'></i><span> Profile </span></a>
                    </li>
                    @endcan

                    <!-- @can('Setting Admin')
                    <li><a class="dropdown-item model_open" href="javascript:;" url="{{route('admin.setting.form')}}"><i class="bx bx-cog"></i><span>Settings</span></a>
                    </li>
                    @endcan -->

                    @can('Change Password Admin')
                    <li><a class="dropdown-item model_open" href="javascript:;" url="{{route('admin.changePassword.form')}}"><i class='bx bx-lock'></i><span> Change Password </span></a>
                    </li>
                    @endcan


                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li><a class="dropdown-item logout_btn" href="{{route('admin.logout')}}"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>