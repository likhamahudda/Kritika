<?php

namespace App\Http\Controllers\Web;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payments;
use App\Models\TemplatePayments;
use Illuminate\Http\Request;
use App\Http\Requests\UserAuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Models\UserContacts;

class UserDetailsController extends Controller 
{
    public function __construct()
    {
    }

    public function edit_profile()
    {
        $user = Auth::guard('user')->user(); 
        // get total contact count
        $contacts_count = UserContacts::where('user_id', $user->id)->count();
         $data = ['title' => 'Edit Profile','user'=>$user];
        return view('users.userDetails.profile', $data);
    }

        public function updateProfile(Request $request)
    {
        $id = Auth::guard('user')->user()->id;
        $request->validate([
            'first_name' => 'required|max:30', 
            'mobile' => 'required', 
        ]);

        $data = User::where('id', $id)->where('deleted_at', null)->first();

        $check = User::where('id','!=', $id)->where('deleted_at', null)->where('mobile', $request->get('mobile'))->count();
        if($check>0){
            return response()->json(['status' => false,  'message' => 'Mobile number is already exist.']);
        }
        if (isset($data)) {
            $data->first_name = $request->get('first_name');  
            $data->mobile = $request->get('mobile'); 
            if (isset($request->image)) {
                $path = 'uploads/profile_img/';
                if(!empty($data->image)){
                   // deleteImage($path . $data->image);
                }
                $image_path =  uploadImage($request->image, $path);
                $data->image = $image_path;
               // $json = ['status' => true, 'name'=>$data->first_name.' '.$data->last_name, 'src' => url($path) .'/'. $data->image, 'message' => ' Profile has been updated succesfully'];
            } 
            $json = ['status' => true, 'name'=>$data->first_name, 'date'=>date('F j, Y'), 'message' => ' Profile has been updated succesfully'];

            if ($data->save()) {
                return response()->json($json);
            } else {
                return response()->json(['status' => false,  'message' => 'Something went wrong']);
            }
        } else {
            return response()->json(['status' => false,  'message' => 'User not found.']);
        }
    }

    public function change_password()
    {
        $user = Auth::guard('user')->user(); 
           $data = ['title' => 'Edit Change Password','user'=>$user];
        return view('users.userDetails.change_password', $data);
    }

    public function update_password(Request $request)
    {
        $id = Auth::guard('user')->user()->id;
        $password = $request->password;
        $request->validate([
            'password' => 'required|min:8|max:12',
            'confirm_password' => 'required|min:8|same:password',
        ]);

        $user = User::where('id', $id)->where('deleted_at',null)->first();
 
        if (!isset($user)) {    // check user is exist
            return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'User not found.']);
        } else {    // update password
            if (\Hash::check($request->current_password , $user->password)) {
                $user->password = Hash::make($password);
                $user->save();
                return response()->json(['status' => true, 'message' => 'Your password has been change successfully.']);
            }else{
                return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'Current password is not matched.']);
            }
        }
    }

  

    
}
