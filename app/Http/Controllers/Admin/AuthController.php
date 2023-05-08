<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
//use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{

    public function __construct()
    {
        // login with  remember me before checking whether its logged in or not
        Auth::viaRemember();
        //$this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /* Function for view login page */

    public function login() 
    {
        if(Auth::check()){  // admin is already login or not
            return redirect()->route('admin.index');
        }
        if(Cookie::get('esign-admin-id')){
            $user=array('email' => Cookie::get('esign-admin-username'), 'password'=>Cookie::get('esign-admin-password'),'remember_me'=>'1' );
        }else{
            $user=array('email' => '', 'password'=>'','remember_me'=>'' );
        }
        $data = ['title' => 'Admin Login','user'=>$user];
        return view('admin.auth.login', $data);
    }

    /* Function for login admin */

    public function do_login(AuthRequest $request)
    {
        try {
            if (checkRecaptcha($_POST['g-recaptcha-response']) == 1) {  // google recaptcha
                $remember_me = $request->has('remember_me') ? true : false; 
                
                if (Auth::attempt($request->only('email', 'password'))) {
                    if (roleName() !='admin') {
                        Auth::logout();
                        return response()->json(['status' => false,  'message' => 'These credentials do not match our records.']);
                    }
                    if (Auth::user()->status == Constant::IN_ACTIVE) {  // login user is active or not
                        Auth::logout();
                        return response()->json(['status' => false,  'message' => 'Your account is temporarily blocked please contact administrator.']);
                    }
                    // store remember me in Cookies
                    if($remember_me){
                        Cookie::queue('esign-admin-id',Auth::user()->id,(60*24*365));
                        Cookie::queue('esign-admin-username',$request->email,(60*24*365));
                        Cookie::queue('esign-admin-password',$request->password,(60*24*365));
                    }else{
                        Cookie::queue(Cookie::forget('esign-admin-id'));
                        Cookie::queue(Cookie::forget('esign-admin-username'));
                        Cookie::queue(Cookie::forget('esign-admin-password'));
                    }
                    //var_dump(Auth::viaRemember()); die();
                    return response()->json(['status' => true,  'message' => 'Login successfully', 'redirect_url' => route('admin.index')]);
                } else {
                    return response()->json(['status' => false,  'message' => 'These credentials do not match our records.']);
                }
            } else {
                return response()->json(['status' => false,  'message' => 'Mismatch captcha.']);
            }
        } catch (Exception $e) {
            dd($e);
            return response()->json(['status' => false,  'message' => $e->message]);
        }
    }

    /* Function for logout admin */

    public function logout()
    {
        Auth::logout();
        return response()->json(['status' => true,  'message' => 'Admin logout successfully', 'redirect_url' => Route('admin.login')]);
    }

    /* Function for view forget password page */

    public function forgetPassword()
    {
        $title = "Forget Password";
        return view('password.forget', compact('title'));
    }

    /* Function for send link to admin */

    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50',
        ]);

        $user = User::where('email', $request->email)->first();
        if (isset($user)) {     // insert request data if user exist
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Str::random(40),
                'created_at' => Carbon::now(),
            ]);

            $tokenData = DB::table('password_resets')->where('email', $request->email)->first();
            // send link through send email
            $content = getEmailContentValue(1);
            if($content){
                $emailval = $content->description;
                $subject = $content->subject;
                //echo getSettingValue('logo'); die();
                if(empty(getSettingValue('logo'))){     // get site logo
                    $logo = url('images/logo.png');
                }else{
                    $logo = url('uploads/logo').'/'.getSettingValue('logo');
                }
                $replace_data = [
                        '@link_value' => url('reset-password/'.$tokenData->token),
                        '@logo' => $logo,
                        '@user_name' => 'Admin',
                    ];

                    foreach ($replace_data as $key => $value) {     // set values in email
                        $emailval = str_replace($key, $value, $emailval);
                    }
                    return response()->json(['status' => true, 'redirect_url' => Route('admin.login'), 'message' => 'A reset link has been sent to your email address.']);

                // if (sendMail($request->email, $emailval, $subject)) {
                //     return response()->json(['status' => true, 'redirect_url' => Route('admin.login'), 'message' => 'A reset link has been sent to your email address.']);
                // } else {
                //     return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'Something went wrong. Please try again.']);
                // }
            }else{
                return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'Something went wrong. Please try again.']);
            }
        } else {
            return response()->json(['status' => false,  'message' => 'These email does not match our records.']);
        }
    }

    /* Function for view reset password page */
    
    public function resetPassword($token)
    {
        $result = ['title' => 'Reset Password', 'token' => $token];
        return view('password.reset', $result);
    }

    /* Function for reset password */

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|max:15',
            'confirm_password' => 'required|min:6|same:password',
        ]);
        $password = $request->password;
        $token = $request->token;
        $tokenData = DB::table('password_resets')->where('token', $token)->first();

        if (!isset($tokenData)) {   // check valid token
            return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'Token Expire.']);
        }
        $user = User::where('email', $tokenData->email)->first();

        if (!isset($user)) {    // check user is exist
            return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'User not found.']);
        } else {    // update password
            $user->password = Hash::make($password);
            $user->save();
            DB::table('password_resets')->where('token', $tokenData->token)->delete();
            return response()->json(['status' => true, 'redirect_url' => Route('admin.login'),   'message' => 'Your password has been successfully reset please login.']);
        }
    }
}
