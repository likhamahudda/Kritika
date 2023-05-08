<?php

namespace App\Http\Controllers\Api;

use App\Constants\Constant;
use App\Models\Payments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class CronJobController extends BaseController
{



    // check subscription daily 
    public function subscription_check(Request $request){
        $users =  User::whereHas('roles', function($query)
        {
           $query->where('name', 'user');
        });
        $users = $users->where('subscription_status', Constant::ACTIVE)->select('id', 'end_date')->get();
        $current_date = Date('Y-m-d');

        foreach($users as $key => $item){
            if($item->end_date < $current_date){
                User::where('id', $item->id)->update(['subscription_status' => Constant::IN_ACTIVE]);
            }
        }
 
    }


// notification send 
    // public function send_notification(Request $request){
    //     $users =  User::whereHas('roles', function($query)
    //     {
    //        $query->where('name', 'user');
    //     });

    //     $users = $users->where('subscription_status', Constant::ACTIVE)->select('id', 'end_date')->get();
        
    //     foreach($users as $key => $item){
    //         $current_date = date('Y-m-d');
    //         $new_date = date('Y-m-d', strtotime($current_date. ' + 30 days') );
    //         if($item->end_date == $new_date){
    //             $data = new Notification();
    //             $data->title = "Subscription Expiration Notification";
    //             $data->description = "Your is plan is expire afert 30 days Please active your plan";
    //             $data->sender_id = 1;
    //             $data->receiver_id = $item->id;
    //             $data->notification_type = 0;
    //             $data->save();
    //         }
    //     }
 
    // }
    public function send_notification(Request $request){
        //echo 'xdgdfg';
        $nft_send_days = getSettingValue('nft_send_days');
        $date = date('Y-m-d',strtotime('+'.$nft_send_days.' Days'));
        $q = Payments::where('expire_date','LIKE',$date.'%')->where('deleted_at',null)->where('transaction_status','1');
        if($q->count()>0){
            $data = $q->get();
            $content = getEmailContentValue(4);
            if($content){
                foreach ($data as $row) {
                    $user = User::select('email')->where('id', $row->user_id)->where('deleted_at','=',null)->first();
                    $emailval = $content->description;
                    $subject = $content->title.' - '.$row->user_name;
                    if(empty(getSettingValue('logo'))){     // get site logo
                        $logo = url('images/logo.png');
                    }else{
                        $logo = url('uploads/admin_profile').'/'.getSettingValue('logo');
                    }
                    $replace_data = [
                            '@logo' => $logo,
                            '@user_name' => $row->user_name,
                            '@nft_send_days' => $nft_send_days,
                        ];

                        foreach ($replace_data as $key => $value) {     // set values in email
                            $emailval = str_replace($key, $value, $emailval);
                        }
                    if (sendMail($user->email, $emailval, $subject)) {
                    } 
                }
            }
        }
    }


}
