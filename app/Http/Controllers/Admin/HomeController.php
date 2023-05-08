<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
//use App\Models\BankDetail;
//use App\Models\City;
//use App\Models\Document;
//use App\Models\Earning;
//use App\Models\FamilyMember;
//use App\Models\Locality;
//use App\Models\MainLocality;
//use App\Models\Offer;
//use App\Models\PaymentRequest;
use App\Models\FamilyMember;
use App\Models\Payments;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Families;
use App\Models\Country; 
use App\Models\States;
use App\Models\Districts; 
use App\Models\Tehsils; 
use App\Models\Panchayat; 
use App\Models\Village; 
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user_data_main =  Families::where('id','!=','1')->where('deleted_at' ,NULL)->count();
        $user_count = $user_data_main;

        $Country =  Country::where('deleted_at' ,NULL)->count();
        $country_count = $Country;

        $States =  States::where('deleted_at' ,NULL)->count();
        $states_count = $States;

        $Districts =  Districts::where('deleted_at' ,NULL)->count();
        $districts_count = $Districts;

        $Tehsils =  Tehsils::where('deleted_at' ,NULL)->count();
        $tehsils_count = $Tehsils;

        $Panchayat =  Panchayat::where('deleted_at' ,NULL)->count();
        $panchayat_count = $Panchayat;

        $Village =  Village::where('deleted_at' ,NULL)->count();
        $village_count = $Village;

        $FamilyMember =  FamilyMember::where('deleted_at' ,NULL)->count();
        $FamilyMember_count = $FamilyMember;

        $FamilyMembermale =  FamilyMember::where('deleted_at' ,NULL)->where('gender_type' ,'male')->count();
        $FamilyMembermale_count = $FamilyMembermale;


        $FamilyMemberfemale =  FamilyMember::where('deleted_at' ,NULL)->where('gender_type' ,'female')->count();
        $FamilyMemberfemale_count = $FamilyMemberfemale;

      
       

        $active_user_count=Families::where('id','!=','1')->where('deleted_at' ,NULL)->where('status','1')->count();
        $inactive_user_count=Families::where('id','!=','1')->where('deleted_at' ,NULL)->where('status','0')->count();

        $subscription_amount =  Constant::CURRENCY.Payments::where('transaction_status','1')->where('deleted_at' ,NULL)->sum('total_subscription_price');

        $subscription_data_main  = Payments::where('transaction_status','1')->where('deleted_at' ,NULL)->count();
        $subscription_count = $subscription_data_main;

        $free_subscription=Payments::where('total_subscription_price',NULL)->where('transaction_status','1')->where('deleted_at' ,NULL)->count();
        $paid_subscription=Payments::where('total_subscription_price','>','0')->where('transaction_status','1')->where('deleted_at' ,NULL)->count();
        $active_subscription=Payments::where('expire_date','>=',date('Y-m-d H:i:s'))->where('transaction_status','1')->where('deleted_at' ,NULL)->count();

        $user_data  = Families::whereYear('created_at', date('Y'))->where('id','!=','1')->where('deleted_at' ,NULL)->select('id', 'created_at')->get();

        $user_count0 = $user_data->count();

        $user = $user_data->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });

        $usermcount = [];
        $userArr = [];
        foreach ($user as $key => $value) {
            $usermcount[(int)$key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!empty($usermcount[$i])) {
                $userArr[$i] = $usermcount[$i];
            } else {
                $userArr[$i] = 0;
            }
        }
        $month_array = json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $array_values = array_values($userArr);
        $monthly_users = json_encode($array_values);



       // $family_member_data  = FamilyMember::whereYear('dob', date('Y'))->where('deleted_at' ,NULL)->select('id', 'dob as created_at')->get();

        $family_member_data = FamilyMember::whereBetween(DB::raw('YEAR(dob)'), [2010, 2023])
        ->whereNull('deleted_at')->get();

        $family_member_data0 = $family_member_data->count();

        $family_member = $family_member_data->groupBy(function ($date) {
            return Carbon::parse($date->dob)->format('Y'); // grouping by months
        });
       

        $family_membermcount = [];
        $family_memberArrArr = [];
        foreach ($family_member as $key => $value) {
            $family_membermcount[(int)$key] = count($value);
        }

      
       

        for ($i = 2010; $i <= 2023; $i++) {
            if (!empty($family_membermcount[$i])) {
                $family_memberArrArr[$i] = $family_membermcount[$i];
            } else {
                $family_memberArrArr[$i] = 0;
            }
        }
      
       
        $start_year = 2010;
        $end_year = 2023;

        $years = array();
        for ($year = $start_year; $year <= $end_year; $year++) {
            $years[] = $year;
        }

        $month_popution_array = json_encode($years);

       // $month_popution_array = json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $array_values_polution = array_values($family_memberArrArr);
        $popution_monthly_users = json_encode($array_values_polution);

           
       


        $subscription_data  = Payments::whereYear('created_at', date('Y'))->where('transaction_status','1')->where('deleted_at' ,NULL)->get();

        $subscription_count0 = $subscription_data->count();
        $subscription = $subscription_data->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });



        $subscriptioncount = [];
        $subscriptionArr = [];

        foreach ($subscription as $key => $value) {
            $subscriptioncount[(int)$key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!empty($subscriptioncount[$i])) {
                $subscriptionArr[$i] = $subscriptioncount[$i];
            } else {
                $subscriptionArr[$i] = 0;
            }
        }

        $subscription_monthly = array_values($subscriptionArr);
        $subscription_monthly = json_encode($subscription_monthly);
 
        $result = ['title'=>'Dashboard',
            'active_user_count' => $active_user_count,
            'inactive_user_count' => $inactive_user_count,
            'user_count' => $user_count,
            'FamilyMember_count' => $FamilyMember_count,
            'FamilyMembermale_count' => $FamilyMembermale_count,
            'FamilyMemberfemale_count' => $FamilyMemberfemale_count,
            'country_count' => $country_count,
            'states_count' => $states_count,
            'districts_count' => $districts_count,
            'tehsils_count' => $tehsils_count,
            'panchayat_count' => $panchayat_count,
            'village_count' => $village_count,
            'user_count0' => $user_count0, 
            'family_member_data0' => $family_member_data0, 
            'subscription_monthly' => $subscription_monthly, 
            'month_array' => $month_array,
            'monthly_users' => $monthly_users,
            'pop_month_array' => $month_popution_array,
            'pop_monthly_users' => $popution_monthly_users,
            'subscription_count' => $subscription_count,
            'subscription_count0' => $subscription_count0,
            'subscription_amount' => $subscription_amount,
            'free_subscription' => $free_subscription,
            'paid_subscription' => $paid_subscription, 
            'active_subscription' => $active_subscription
                ];
        return view('admin.index', $result);
    }

    public function get_chart_data(Request $request)
    {
        $user_data_main =  Families::whereYear('created_at', $request->year)->where('deleted_at' ,NULL);           
       
        $user_count = $user_data_main->count();        
        $user_data  = $user_data_main->select('id', 'created_at')->get();
        $user = $user_data->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });

        $usermcount = [];
        $userArr = [];
        foreach ($user as $key => $value) {
            $usermcount[(int)$key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!empty($usermcount[$i])) {
                $userArr[$i] = $usermcount[$i];
            } else {
                $userArr[$i] = 0;
            }
        }
        $month_array = json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $monthly_users = array_values($userArr);
        //$monthly_users = json_encode($monthly_users); 
        //$subscription_count = $subscription_data_main;
        $subscription_data  = Payments::whereYear('created_at', $request->year)->where('transaction_status','1')->where('deleted_at' ,NULL)->get();

        $subscription_count = $subscription_data->count();
        $subscription = $subscription_data->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });

        $subscriptioncount = [];
        $subscriptionArr = [];

        foreach ($subscription as $key => $value) {
            $subscriptioncount[(int)$key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!empty($subscriptioncount[$i])) {
                $subscriptionArr[$i] = $subscriptioncount[$i];
            } else {
                $subscriptionArr[$i] = 0;
            }
        }

        $subscription_monthly = array_values($subscriptionArr);
        //$subscription_monthly = json_encode($subscription_monthly); 

        $result = ['user_count' => $user_count,  'subscription_monthly' => $subscription_monthly,  'month_array' => $month_array, 'monthly_users' => $monthly_users, 'subscription_count' => $subscription_count];
        return response()->json($result);
    }


    
    public function get_member_chart_data(Request $request)
    {
       // $user_data_main =  FamilyMember::whereYear('dob', $request->year)->where('deleted_at' ,NULL);
        $year_range = $request->year;
        $year_range = str_replace('-', ',', $year_range);
        $years = explode(',', $year_range);
        $start_year = $years[0];
        $end_year = $years[1];
        
        $user_data_main = FamilyMember::whereBetween(DB::raw('YEAR(dob)'), [$start_year, $end_year])
                                     ->whereNull('deleted_at');
                                     
        
       
        $user_count = $user_data_main->count();

        
        $user_data  = $user_data_main->select('id', 'dob')->get();
        $user = $user_data->groupBy(function ($date) {
            return Carbon::parse($date->dob)->format('Y'); // grouping by months
        });

        $usermcount = [];
        $userArr = [];
        foreach ($user as $key => $value) {
            $usermcount[(int)$key] = count($value);
        }

        for ($i = $start_year; $i <= $end_year; $i++) {
            if (!empty($usermcount[$i])) {
                $userArr[$i] = $usermcount[$i];
            } else {
                $userArr[$i] = 0;
            }
        }

        

        $years = array();
        for ($year = $start_year; $year <= $end_year; $year++) {
            $years[] = $year;
        }
        $month_array = json_encode($years);

      //  $month_array = json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $monthly_users = array_values($userArr);
        //$monthly_users = json_encode($monthly_users);
           
       
        //$subscription_count = $subscription_data_main;


        $subscription_data  = Payments::whereYear('created_at', $request->year)->where('transaction_status','1')->where('deleted_at' ,NULL)->get();

        $subscription_count = $subscription_data->count();
        $subscription = $subscription_data->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });



        $subscriptioncount = [];
        $subscriptionArr = [];

        foreach ($subscription as $key => $value) {
            $subscriptioncount[(int)$key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!empty($subscriptioncount[$i])) {
                $subscriptionArr[$i] = $subscriptioncount[$i];
            } else {
                $subscriptionArr[$i] = 0;
            }
        }

        $subscription_monthly = array_values($subscriptionArr);
        //$subscription_monthly = json_encode($subscription_monthly);

      



        $result = ['user_count' => $user_count,  'subscription_monthly' => $subscription_monthly,  'month_array' => $month_array, 'monthly_users' => $monthly_users, 'subscription_count' => $subscription_count];
        return response()->json($result);
    }

    public function theme_style(Request $request)
    {
        $data =    Session::put('theme_mode', $request->theme_mode);
        return response()->json(['status' => true, 'message' => 'Theme mode has been changed']);
    }


    public function changePassword()
    {
        $data = Auth::user();
        $result = ['title' => 'Change Password', 'data' => $data];
        return view('admin.setting.changePassword', $result);
    }




    public function setting()
    {
        $all_data = Setting::select("slug","value")->get();
        $array = array();
        foreach ($all_data as $value) {
            // code...
            //echo $value['value'];
            $array = Arr::add($array, $value['slug'], $value['value']);
        }
        
        $result = ['title' => 'Settings', 'array'=>$array];
        return view('admin.setting.setting', $result);
    }




    public function updateSetting(Request $request)
    {
        //echo $request->logo; die();
        // $request->validate([
        //     'employee_commission' => 'required|integer',
        //     'tm_commission' => 'required|integer',
        //     'business_commission' => 'required|integer'
        // ]);

        // Setting::where('slug', 'employee_commission')->update(['value' => $request->employee_commission]);
        // Setting::where('slug', 'tm_commission')->update(['value' => $request->tm_commission]);
        // Setting::where('slug', 'business_commission')->update(['value' => $request->business_commission]);
        $input = $_POST;
        //print_r($input);
        foreach ($input as $key => $value) {
            // code...
            if($key!='_token' && $key!='logo'){
                Setting::where('slug', $key)->update(['value' => $value]);
            }
        }
        if (isset($request->logo)) {
            $path = 'uploads/logo/';
            if(!empty(getSettingValue('logo'))){
                deleteImage($path . getSettingValue('logo'));
            }
            $image_path =  uploadImage($request->logo, $path);
            //echo $image_path; die();
            Setting::where('slug', 'logo')->update(['value' => $image_path]);
            $json = ['status' => true, 'src' => url($path).'/'.$image_path, 'message' => 'Settings updated succesfully'];
        }else{
            $json = ['status' => true, 'message' => 'Settings updated succesfully'];
        }
        return response()->json($json);
    }




    public function updatePassword(Request $request)
    {
        $id = $request->get('id');
        $request->validate([
            'confirm_password' => 'required|min:6|max:15',
            'password' => 'required|min:6|max:15',
            'current_password' => 'required|min:6|max:15'
        ]);

        $data = User::where('id', $request->id)->first();
        if (isset($data)) {

            if (Hash::check($request->current_password, Auth::user()->password)) {

                $data->password = Hash::make($request->get('password'));
                if ($data->save()) {
                    return response()->json(['status' => true, 'message' => ' Password has been updated succesfully']);
                } else {
                    return response()->json(['status' => false,  'message' => 'Something went wrong']);
                }
            } else {
                return response()->json(['status' => false,  'message' => 'Invalid old password.']);
            }
        } else {
            return response()->json(['status' => false,  'message' => 'User not found.']);
        }
    }






    public function profile()
    {
        $data = Auth::user();
        $result = ['title' => 'Update Profile Details', 'data' => $data];
        return view('admin.setting.profile', $result);
    }


    public function updateProfile(Request $request)
    {
        $id = $request->get('id');
        $request->validate([
            'first_name' => 'required|max:30',
            'last_name' => 'required|max:30',
            'email' => 'required||unique:users,email,' . $id,
            'mobile' => 'required|unique:users,mobile,' . $id,
            'address' => 'required',
            'zip_code' => 'required',




        ]);

        $data = User::where('id', $request->id)->first();
        if (isset($data)) {
            $data->first_name = $request->get('first_name');

            
            $data->last_name = $request->get('last_name');
            $data->email = $request->get('email');
            $data->mobile = $request->get('mobile');
            $data->address = $request->get('address');
            $data->zip_code = $request->get('zip_code');
            if (isset($request->image)) {
                $path = 'uploads/admin_profile/';
                if(!empty($data->image)){
                    deleteImage($path . $data->image);
                }
                $image_path =  uploadImage($request->image, $path);
                $data->image = $image_path;
                $json = ['status' => true, 'name'=>$data->first_name.' '.$data->last_name, 'src' => url($path) .'/'. $data->image, 'message' => ' Profile has been updated succesfully'];
            }else{
                $json = ['status' => true, 'name'=>$data->first_name.' '.$data->last_name, 'message' => ' Profile has been updated succesfully'];
            }

            if ($data->save()) {
                return response()->json($json);
            } else {
                return response()->json(['status' => false,  'message' => 'Something went wrong']);
            }
        } else {
            return response()->json(['status' => false,  'message' => 'User not found.']);
        }
    }
}
