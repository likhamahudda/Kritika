<?php

namespace App\Http\Controllers\Web;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Jobs\OTP as JobsOTP;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserAuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\UserContacts;
use App\Models\RequestQuotation;
use App\Models\RequestQuotationVendors;

class UserController extends Controller
{
    public function __construct()
    {
        $this->admin_email = User::select('email')->where('id', '1')->where('deleted_at', null)->first();
    }


    public function index(Request $request)
    {
 
        if( Auth::guard('user')->user()->id){

        $user_id =   Auth::guard('user')->user()->id;
        $user_type =   Auth::guard('user')->user()->user_type;

        if(isset($user_type) && $user_type == 0){ //buyer

        $rfq_data = RequestQuotation::select('request_for_quotation.id','master_products.name','request_for_quotation.buyer_id','products.product_img','request_for_quotation.created_at')
        ->leftJoin('products', function ($join) {
            $join->on('request_for_quotation.product_id', '=', 'products.id');
        })
        ->leftJoin('master_products', function ($join) {
            $join->on('master_products.id', '=', 'products.product_name');
        })
        ->where('request_for_quotation.buyer_id', $user_id)->orderBy('request_for_quotation.created_at', 'desc')->get();
  
        $data['title'] = 'Dashboard';
        $data['rfq_data'] = $rfq_data; 
            
       return view('users.index', $data);

    }else{ // seller

        $rfq_data = RequestQuotation::select('request_for_quotation.id','master_products.name','request_for_quotation.buyer_id','products.product_img','request_for_quotation.created_at')
        ->leftJoin('products', function ($join) {
            $join->on('request_for_quotation.product_id', '=', 'products.id');
        })
        ->leftJoin('master_products', function ($join) {
            $join->on('master_products.id', '=', 'products.product_name');
        })
        ->leftJoin('request_quotation_vendors', function ($join) {
            $join->on('request_quotation_vendors.rfq_id', '=', 'request_for_quotation.id');
        })
        ->where('request_quotation_vendors.seller_id', $user_id)->orderBy('request_for_quotation.created_at', 'desc')->get();
  
        $data['title'] = 'Dashboard';
        $data['rfq_data'] = $rfq_data; 
            
       return view('users.dashboard', $data);


    }
        }else{
            return redirect('user/loginUser');
        }
       
    }

    public function sendOtp(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|unique:users,email',
            'name' => "required"
        ]);

         Otp::where('email', $request->email)->update(['status' => 0]);

        $otp = new Otp;
        $otp->name = $request->name;
        $otp->email = $request->email;
        $otp->referral_id = $request->referral_id;
        $otp_generate = generateNumericOTP(4);
        $otp->otp = $otp_generate;
        $otp->status = 1;
        if ($otp->save()) {

            $data_array = [
                'email' => $request->email,
                'otp' => $otp_generate,
                'content' => 'Indeal: Your Verification Code is '
            ];
            $this->dispatch(new JobsOTP($data_array));

            return response()->json(['message' => 'OTP has been sent succesfully', 'status' => true]);
        } else {
            return response()->json(['message' => 'something went wrong please try again', 'status' => false]);
        }
    }


    public function verifyOtp(Request $request)
    {
        $validator = $request->validate([
            'email' => "required|email",
            'otp' => "required|between:4,4'",
        ]);
        $data = Otp::where('email', $request->email)->where('otp', $request->otp)->where('status', 1)->first();

        if (isset($data)) {
            $data->status = 0;
            $data->save();

            $user = new User();
            $user->first_name = $data->name;
            $user->email = $data->email;

            $user->referral_id = Str::random(Constant::REFERRAL_ID_LENGTH);
            $user->perent_referral_id = $data->referral_id;

            if ($user->save()) {
                return response()->json([
                    'redirect_url' => route('web.profile.update', base64_encode($user->id)),
                    'status' => true,
                    'message' => 'OTP has been verified',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong',
                ]);
            }
        } else {
            return response()->json([
                ' status' => false,
                'message' => 'OTP does not match',
            ]);
        }
    }



    function refer_user_count($referral_id)
    {
        $data = User::where('perent_referral_id', $referral_id)->count();
        return $data;
    }



    public function profileUpdate($id)
    {
        $user = User::where('id', base64_decode($id))->first();
        $state = State::where(['status' => Constant::ACTIVE])->pluck('name', 'id');
        if (isset($user)) {

            if (isset(Auth::user()->referral_id)) {
                $referal_user = User::where('perent_referral_id', Auth::user()->referral_id)->paginate(3);
            } else {
                $referal_user = [];
            }

            $city = City::where(['status' => Constant::ACTIVE, 'state_id' => $user->state_id])->pluck('name', 'id');
            $main_locality = MainLocality::where(['status' => Constant::ACTIVE, 'city_id' => $user->city_id])->pluck('name', 'id');

            $data = ['title' => 'User Dashbord', 'referal_user' => $referal_user,  'user' => $user, 'state' => $state, 'city' => $city, 'main_locality' => $main_locality];
            return view('web.pages.dashbord', $data);
        } else {
            return redirect()->back()->with('error', 'Data not found. ');
        }
    }


    public function updateProfile(Request $request, $id)
    {
        $user = User::where('id', base64_decode($id))->first();
        if (isset($user)) {
            $user->first_name = $request->get('name');
            $user->mobile = $request->get('mobile');
            $user->email = $request->get('email');
            $user->state_id = $request->get('state_id');
            $user->city_id = $request->get('city_id');
            $user->main_locality_id = $request->get('main_locality_id');
            $user->address = $request->get('address');
            $user->zip_code = $request->get('zip_code');



            if (isset($request->image)) {
                $strArray = explode('/', $user->image);
                $lastElement = end($strArray);
                deleteImage('/uploads/profile/' . $lastElement);
                $image_path =  uploadImage($request->image, 'uploads/profile/');
                $user->image = $image_path;
            }

            if (isset($request->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
            }


            if ($user->save()) {
                if (isset($request->password)) {
                    $user->assignRole(['5']);
                    Auth::guard('web')->attempt(['email' => $request->get('email'), 'password' => $request->password]);
                }

                return redirect()->back()->with('success', 'Profile details has been updated');
            } else {
                return redirect()->back()->with('error', 'Something went wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Data not found.');
        }
    }

    // Code Start

 
       

    public function login(Request $request)
    {
        if (Auth::guard('user')->check()) {  // admin is already login or not
            return redirect()->route('user.index');
        }
        $data = ['title' => 'User Login'];
        return view('users.auth.login', $data);
    }

    public function signup(Request $request)
    {
        $data = ['title' => 'User SignUp'];
        return view('users.auth.signup', $data);
    }


    public function do_login(UserAuthRequest $request)
    {
        if (Auth::guard('user')->attempt(['email' => $request->get('email'), 'user_type' => $request->get('user_type'), 'deleted_at' => null, 'password' => $request->password])) {
            //echo roleName(); die();
            // if (roleName() !='user') {
            //     Auth::guard('user')->logout();
            //     return response()->json(['status' => false,  'message' => '0These credentials do not match our records.']);
            // }
             
             
            $user = User::where('email', $request->email)->where('user_type', $request->user_type)->where('deleted_at', null)->first();
            if ($this->admin_email->email == $request->get('email')) {
                Auth::guard('user')->logout();
                return response()->json(['status' => false, 'message' => 'These credentials do not match our records']);
            }
            if ($user->verification_status == '0') {
                Auth::guard('user')->logout();
                return response()->json(['status' => false, 'message' => 'Email is not verified']);
            }
            if ($user->status == Constant::ACTIVE) {
                if($user->user_type == 0){
                    $redict_url = route('user.index');
                }else{
                    $redict_url = route('user.index');
                }
                return response()->json(['status' => true, 'message' => 'Login successfully', 'redirect_url' => $redict_url]);
            } else {
                Auth::guard('user')->logout();
                return response()->json(['status' => false, 'message' => 'Your account has been blocked Please contact admin']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'These credentials do not match our records']);
        }
    }

    public function do_register(Request $request)
    {
        $count = User::whereNull('deleted_at')->where('email', '=', $request->get('email'))->where('user_type', '=', $request->get('user_type'))->count();
        if ($count > 0) {
            return response()->json(['status' => false,  'message' => 'User is already exist with given email id']);
        }
        if (User::where('mobile', '=', $request->get('mobile'))->where('user_type', '=', $request->get('user_type'))->where('deleted_at', '=', null)->count() > 0) {
            return response()->json(['status' => false,  'message' => 'User is already exist with given phone number']);
        }
        $data = new User();
        //$data->assignRole('user');
        //echo $data->getRoleNames(); die();

        $data->status = '1';
        $data->email_verified_at = date('Y-m-d ');
        $data->verification_status = '1';
        $data->user_type = $request->get('user_type');
        $data->email = $request->get('email');
        $data->mobile = $request->get('mobile');
        $data->first_name = $request->get('first_name');
        $data->password =  Hash::make($request->get('password'));
        $data->verify_token = Str::random(40);
        if ($data->save()) {
            $content = getEmailContentValue(5);
            if ($content) {
                $emailval = $content->description;
                $subject = $content->title;
                //echo getSettingValue('logo'); die();
                if (empty(getSettingValue('logo'))) {     // get site logo
                    $logo = url('images/logo.png');
                } else {
                    $logo = url('uploads/logo') . '/' . getSettingValue('logo');
                }
                $replace_data = [
                    '@link_value' => url('user/verify_email?token=' . $data->verify_token),
                    '@user_email' => $request->get('email'),
                    '@logo' => $logo,
                ];

                foreach ($replace_data as $key => $value) {     // set values in email
                    $emailval = str_replace($key, $value, $emailval);
                }
                // if (sendMail($request->email, $emailval, $subject)) {
                $html = 'THANK YOU FOR SIGNING UP<span>An Email has been sent with instruction for verifying your account.<br>Have questions about advanced features and integrations? write us at <a href="mailto:' . getSettingValue('company_email') . '">' . getSettingValue('company_email') . '</a></span>';
                return response()->json(['status' => true,  'message' => 'Register successfully Please login.', 'html' => $html]);
                // } else {
                //     return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'Something went wrong. Please try again.']);
                // }
            } else {
                return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'Something went wrong. Please try again.']);
            }
        }
    }

    public function logout()
    {
        Auth::guard('user')->logout();
        return redirect()->route('user.login')->with('success', 'Logout successfully');
    }

    public function verify_email(Request $request)
    {
        $q = User::where('deleted_at', null)->where('verify_token', $request->token);
        if ($q->count() > 0) { // if user exist with token
            $data = $q->first();
            if ($data->verification_status == '1') { // if token is alreday verified
                return redirect()->route('user.login');
            } else { // for user verification
                $data->email_verified_at = date('Y-m-d');
                $data->verification_status = '1';
                $data->remember_token = null;
                if ($data->save()) {
                    return redirect()->route('user.login')->with('success', 'Email Verified successfully');
                }
            }
        } else { //token is expire
            return redirect()->route('user.login')->with('error', 'Token Expried');
        }
    }

    /* Function for view forget password page */

    public function forgetPassword()
    {
        $title = "Forget Password";
        return view('password.user_forget', compact('title'));
    }

    /* Function for send link to admin */

    public function sendLink(Request $request)
    {

        if ($this->admin_email->email == $request->email) {
            return response()->json(['status' => false, 'message' => 'Email is not exist.']);
        }
        $request->validate([
            'email' => 'required|email|max:50',
        ]);

        $user = User::where('email', $request->email)->where('user_type', $request->user_type)->where('deleted_at', null)->first();
        if (isset($user)) {     // insert request data if user exist
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'user_type' => $request->user_type,
                'token' => Str::random(40),
                'created_at' => Carbon::now(),
            ]);

            $tokenData = DB::table('password_resets')->where('email', $request->email)->where('user_type', $request->user_type)->first();
            // send link through send email
            $content = getEmailContentValue(1);
            if ($content) {
                $emailval = $content->description;
                $subject = $content->subject . ' - ' . $user->first_name . ' ' . $user->last_name;
                //echo getSettingValue('logo'); die();
                if (empty(getSettingValue('logo'))) {     // get site logo
                    $logo = url('images/logo.png');
                } else {
                    $logo = url('uploads/logo') . '/' . getSettingValue('logo');
                }
                $replace_data = [
                    '@link_value' => url('/user/reset-password/' . $tokenData->token.'/'.$request->user_type),
                    '@logo' => $logo,
                    '@user_name' => $user->first_name . ' ' . $user->last_name,
                ];

              

                foreach ($replace_data as $key => $value) {     // set values in email
                    $emailval = str_replace($key, $value, $emailval);
                }
                if (sendMail($request->email, $emailval, $subject)) {
                    //return redirect()->route('user.login')->with('success', 'Password reset link has been sent to your email address.');
                    return response()->json(['status' => true, 'redirect_url' => Route('user.login'), 'message' => 'Password reset link has been sent to your email address.']);
                } else {
                    return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'Something went wrong. Please try again.']);
                }
            } else {
                return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'Something went wrong. Please try again.']);
            }
        } else {
            return response()->json(['status' => false,  'message' => 'These email does not match our records.']);
        }
    }

    /* Function for view reset password page */

    public function resetPassword($token,$user_type)
    {
         
        $result = ['title' => 'Reset Password', 'token' => $token, 'user_type' =>$user_type];
        return view('password.user_reset', $result);
    }

    /* Function for reset password */

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|max:12',
            'confirm_password' => 'required|min:8|same:password',
        ]);
        $password = $request->password;
        $token = $request->token;
        $user_type = $request->user_type;
        $tokenData = DB::table('password_resets')->where('token', $token)->where('user_type', $user_type)->first();

        if (!isset($tokenData)) {   // check valid token
            return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'Token Expire.']);
        }
        $user = User::where('email', $tokenData->email)->where('user_type', $user_type)->where('deleted_at', null)->first();

        if (!isset($user)) {    // check user is exist
            return response()->json(['status' => false, 'redirect_url' => '',   'message' => 'User not found.']);
        } else {    // update password
            $user->password = Hash::make($password);
            $user->save();
            DB::table('password_resets')->where('token', $tokenData->token)->where('user_type', $user_type)->delete();
            return response()->json(['status' => true, 'redirect_url' => Route('user.login'),   'message' => 'Your password has been successfully reset please login.']);
        }
    }

    public function curlRequest($url, $post_data)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
    }

    public function getEmailByUser($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
    }

    public function getUser($id)
    {
        $user = User::where('id', $id)->first();
        return $user;
    }
  

    public function pay_now(Request $request)
    {

        $secretKey = Constant::SECRETE_KEY;
        $user_data = $this->getUser(Auth::user()->id);

        $array = [
            "appId" => Constant::APP_ID,
            "orderId" => date('Ymdhis'),
            "orderAmount" => 500,
            "data_id" => 111,
            "orderCurrency" => 'INR',
            "orderNote" => 'Testing note',
            "customerName" => $user_data->first_name ?? '',
            "customerPhone" => $user_data->mobile ?? '',
            "customerEmail" => $user_data->email ?? '',
            "returnUrl" => URL::to('/payment-response'),
            "notifyUrl" => URL::to('/payment-response'),
        ];


        ksort($array);
        $signatureData = "";
        foreach ($array as $key => $value) {
            $signatureData .= $key . $value;
        }
        $signature = hash_hmac('sha256', $signatureData, $secretKey, true);
        $signature = base64_encode($signature);
        $url = "https://test.cashfree.com/billpay/checkout/post/submit";
        $array['signature'] = $signature;
        $this->curlRequest($url, $array);
    }


    function roleCheck($referral_id)
    {
        $data = User::where('referral_id', $referral_id)->first();
        if (isset($data)) {
            $data =   $data->getRoleNames();
            return $data[0];
        } else {
            return null;
        }
    }


    public function response(Request $request)
    {
        $data = new Transaction();
        $data->user_id = Auth::user()->id;
        $data->order_id = $request->get('orderId');
        $data->amount = $request->get('orderAmount');
        $data->reference_id = $request->get('referenceId');
        $data->status = $request->get('txStatus');
        $data->payment_mode = $request->get('paymentMode');
        $data->txn_msg = $request->get('txMsg');
        $data->txn_date_time = $request->get('txTime');
        $data->signature = $request->get('signature');
        if ($data->save()) {
            if ($data->status == 'SUCCESS') {

                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime($start_date . ' + 12 months'));
                User::where('id', Auth::user()->id)->update(['subscription_status' => Constant::ACTIVE, 'start_date' => $start_date, 'end_date' => $end_date]);
                $role =  $this->roleCheck(Auth::user()->perent_referral_id);
                if ($role == 'user') {
                    $total =  $this->refer_user_count(Auth::user()->perent_referral_id);

                    if ($total <= 3) {
                        $ref_user =  User::where(['referral_id' => Auth::user()->perent_referral_id, 'subscription_status' => Constant::ACTIVE])->first();
                        if (isset($ref_user)) {
                            $current_date = $ref_user->end_date;
                            $current_date = date('Y-m-d', strtotime($current_date . ' + 01 months'));
                            $ref_user->end_date = $current_date;
                            $ref_user->save();
                        }
                    }
                } elseif ($role == 'employee') {
                    $employee_commission = getSettingValue('employee_commission');
                    $emloyee =  User::where(['referral_id' => Auth::user()->perent_referral_id])->first();
                    if (isset($emloyee)) {
                        $amount = $emloyee->wallet_amount ?? 0;
                        $total = $amount + $employee_commission;
                        $emloyee->wallet_amount = $total;
                        $emloyee->save();
                        $this->saveEarning($emloyee->id, $employee_commission);
                    }

                    $tm_commission = getSettingValue('tm_commission');
                    $tm =  User::where(['referral_id' => $emloyee->perent_referral_id])->first();
                    if (isset($tm)) {
                        $amount = $tm->wallet_amount ?? 0;
                        $total = $amount + $tm_commission;
                        $tm->wallet_amount = $total;
                        $tm->save();
                        $this->saveEarning($tm->id, $tm_commission);
                    }
                } elseif ($role == 'team leader') {
                    $tm_commission = getSettingValue('tm_commission');
                    $tm =  User::where(['referral_id' => Auth::user()->perent_referral_id])->first();
                    if (isset($tm)) {
                        $amount = $tm->wallet_amount ?? 0;
                        $total = $amount + $tm_commission;
                        $tm->wallet_amount = $total;
                        $tm->save();

                        $this->saveEarning($tm->id, $tm_commission);
                    }
                } elseif ($role == 'business') {
                    $business_commission = getSettingValue('business_commission');
                    $business =  User::where(['referral_id' => Auth::user()->perent_referral_id])->first();
                    if (isset($business)) {
                        $amount = $business->wallet_amount ?? 0;
                        $total = $amount + $business_commission;
                        $business->wallet_amount = $total;
                        $business->save();
                        $this->saveEarning($business->id, $business_commission);
                    }
                }

                return redirect()->route('payment.success', base64_encode($data->id))->with('success', 'Payment successfull');
            } else {
                return redirect()->route('payment.failure', base64_encode($data->id))->with('error', 'Payment Faild');
            }
        } else {
            return view('web.pages.server_error');
        }
    }


    function saveEarning($user_id, $amount)
    {
        $data = new Earning();
        $data->user_id = $user_id;
        $data->amount = $amount;
        $data->save();
    }


    public function success($id)
    {

        $data = Transaction::where('id', base64_decode($id))->first();
        if (isset($data)) {

            if ($data->user_id == Auth::user()->id) {
                $result = ['title' => 'Payment Successfully', 'data' => $data];
                return view('web.pages.payment_success', $result)->with('error', 'Payment faield');
            } else {
                return view('web.pages.server_error');
            }
        } else {
            return view('web.pages.server_error');
        }
    }


    public function failure()
    {
        $result = ['title' => 'Payment Successfully'];
        return view('web.pages.payment_failure', $result)->with('error', 'Payment faield');
    }

    public function importCsv(Request $request)
    {
        $user = Auth::guard('user')->user();
        $extension = $request->file('csv_file')->getClientOriginalExtension();
        if ($extension != 'csv') {
            $statusMsg = json_encode(array("error" => '1', 'error_mess' => 'Only csv file will be allowed to import.'));
        } else {
            if (isset($request->csv_file) && !empty($request->csv_file)) {
                //get contacts from CSV file And added in user contacts table
                $file = $_FILES['csv_file']['tmp_name'];
                $handle = fopen($file, "r");
                $c = 0;
                $inserted_records_total = 0;
                $updated_records_total = 0;
                while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                    if ($c > 0 && $c < 10000 && isset($filesop[1]) && !empty($filesop[1])) {
                        if (isset($filesop[0]) && !empty($filesop[0])) {
                            $email = trim(strtolower($filesop[0]));
                            // check email validation
                            $valid_email = (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
                            if ($valid_email) {
                                // check email is exist or not in contact database for this user
                                $check_contact_exist = UserContacts::where('email', $email)->where('user_id', $user->id)->first();
                                if (isset($check_contact_exist->id) && !empty($check_contact_exist->id)) {
                                    $check_contact_exist->name = ucwords(isset($filesop[1]) ? $filesop[1] : '');
                                    $check_contact_exist->save();
                                    $updated_records_total++;
                                } else {
                                    $new_contact_info = new UserContacts();
                                    $new_contact_info->user_id = $user->id;
                                    $new_contact_info->email = $email;
                                    $new_contact_info->name = ucwords(isset($filesop[1]) ? $filesop[1] : '');
                                    $new_contact_info->save();

                                    $rendom_id = random_strings(40);
                                    $new_contact_info->invitation_url = $new_contact_info->id . $rendom_id;
                                    $new_contact_info->save();

                                    $inserted_records_total++;
                                }
                            }
                        }
                    }
                    $c++;
                }
                $statusMsg = json_encode(array("error" => '0', 'mess' => 'Contacts import successfully', "new_rc" => $inserted_records_total, "up_rc" => $updated_records_total));
            } else {
                $statusMsg = json_encode(array("error" => '1', 'error_mess' => 'Something went wrong.'));
            }
        }

        echo $statusMsg;
    }

    public function getContactList(Request $request)
    {
        $user = Auth::guard('user')->user();

        //get contacts of login user
        $get_contacts = UserContacts::where('user_id', $user->id)->get();
        $html = "";
        $inner_html = "";
        if (isset($get_contacts) && count($get_contacts) > 0) {
            foreach ($get_contacts as $contact) {
                $inner_html .= '
                <div class="contact">
                    <input class="contact_emails" type="checkbox" id="contact_' . $contact->id . '" value="' . $contact->id . '" name="emails[]">
                    <label for="contact_' . $contact->id . '">' . $contact->email . '</label>
                </div>';
            }

            $html .= '
            <div class="contact-container">
            ' . $inner_html . '
            <div>';
        }
        $statusMsg = json_encode(array("error" => '0', 'error_mess' => '', 'html' => $html, "success" => 1, "success_mess" => ''));
        echo $statusMsg;
    }

    public function getContactListCsv(Request $request)
    {
        $user = Auth::guard('user')->user();

        //get contacts of login user
        $get_contacts = UserContacts::where('user_id', $user->id)->get();
        $html = "";
        $inner_html = "";
        if (isset($get_contacts) && count($get_contacts) > 0) {
            foreach ($get_contacts as $contact) {
                $inner_html .= '<div class="contact new_highlight">
                    <input class="contact_emails" type="checkbox" id="contact_' . $contact->id . '" value="' . $contact->id . '" name="emails[]">
                    <label for="contact_' . $contact->id . '">' . $contact->email . '</label>
                </div>';
            }

            $html .= '<style>.new_highlight{
                color: #468847;
                }
            </style>
            <div class="contact-container">
            ' . $inner_html . '
            <div>';
        }
        $statusMsg = json_encode(array("error" => '0', 'error_mess' => '', 'html' => $html, "success" => 1, "success_mess" => ''));
        echo $statusMsg;
    }

    public function inviteUsers(Request $request)
    {
        if (isset($request->emails) && !empty($request->emails)) {
            if (empty(getSettingValue('logo'))) {     // get site logo
                $logo = url('images/logo.png');
            } else {
                $logo = url('uploads/logo') . '/' . getSettingValue('logo');
            }

            $user = Auth::guard('user')->user();
            foreach ($request->emails as $contact_id) {
                // check this user is already invited or not ... if already invited then invitaion email does not sent
                $invitations = UserContacts::where('invitatation_send_or_not', '0')->where('id', $contact_id)->first();

                if (isset($invitations->id) && !empty($invitations->id)) {
                    $content = getEmailContentValue(8);
                    if ($content) {
                        $emailval = $content->description;
                        $subject = $content->subject;
                        $replace_data = [
                            '@user_name' => $invitations->name,
                            '@sender_email' => $user->email,
                            '@message_content' => getSettingValue('invitation_msg'),
                            '@link_value' => url('user?inviter=') . $invitations->invitation_url,
                            '@logo' => $logo,
                        ];

                        foreach ($replace_data as $key => $value) {
                            $emailval = str_replace($key, $value, $emailval);
                        }
                        if (sendMail($invitations->email, $emailval, $subject)) {
                            //update invitation send status
                            $invitations->invitatation_send_or_not = '1';
                            $invitations->save();
                        }
                    }
                }
            }
            $statusMsg = json_encode(array("error" => '0', 'error_mess' => '', "success" => 1, "success_mess" => 'Invitation sent successfully'));
        } else {
            $statusMsg = json_encode(array("error" => '0', 'error_mess' => 'Something went wrong'));
        }
        echo $statusMsg;
    }

    public function clearAddressBook(Request $request)
    {
        $user = Auth::guard('user')->user();
        UserContacts::where('user_id', $user->id)->forceDelete();
        $statusMsg = json_encode(array("error" => '0', 'error_mess' => '', "success" => 1));
        echo $statusMsg;
    }

    public function getUserContact(Request $request)
    {
        if (isset($request->page_limit) && !empty($request->page_limit) && isset($request->q) && !empty($request->q)) {
            $user = Auth::guard('user')->user();
            // get user contacts according to query parameter search
            $all_contact = UserContacts::where('email', 'like', '%' . $request->q . '%')->where('user_id', $user->id)->limit($request->page_limit)->get();
            $store_array = array();
            if (isset($all_contact) && count($all_contact) > 0) {
                foreach ($all_contact as $contact) {
                    array_push($store_array, $contact->email);
                }
            }
            $statusMsg = json_encode(array("error" => '0', 'error_mess' => '', "success" => 1, "success_mess" => '', "contacts" => $store_array));
        } else {
            $statusMsg = json_encode(array("error" => '0', 'error_mess' => 'Something went wrong'));
        }
        echo $statusMsg;
    }
}
