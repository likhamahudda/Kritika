<?php

use App\Constants\Constant;
use App\Models\BusinessType;
use App\Models\Earning;
use App\Models\PaymentRequest;
use App\Models\User;
use App\Models\Setting;
use App\Models\AdminNotification;
use App\Models\EmailTemplate;
use App\Mail\ForgetPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Http\File;




/******************************************* DROPDOWN BUTTON START ***************************/

function customeRoute($route = null, $params = null)
{
    return route($route, $params);
}

function default_date_format($date)
{
    return date('j F Y', strtotime($date));
}

function saveAdminNotification($title,$message) 
{
    $data = new AdminNotification();  
    $data->message = $message;
    $data->title = $title;
    $data->admin_id = Auth::user()->id;
    $data->admin_name = Auth::user()->first_name.' '.Auth::user()->last_name;
    $data->created_at = date('Y-m-d H:i:s');
    $data->updated_at =  date('Y-m-d H:i:s');       
    $data->status = '0';
    $data->is_read = '0';
    $data->save();
}

// CREATE BUTTON FUNCTION 
function createButton($buttons)
{
    return ' <div class="">
                <ul class="action-menu">
                ' . $buttons . '
                </ul>
            </div>';
}

function like_match($pattern, $subject)
{
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    return (bool) preg_match("/^{$pattern}$/i", $subject);
}

function checkRecaptcha($response) {
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.getSettingValue('secret_key').'&response=' . $response;
        $verifyResponse = file_get_contents($url);
        $responseData = json_decode($verifyResponse);
        if (isset($responseData->success) && $responseData->success == 1) {
            $response = 1;
        } else {
            $response = 0;
        }
        return $response;
    }

//EDIT BUTTON FUNCTION 
function editButton($route, $parms)
{
    return '<li><a class="dropdown-item model_open"  type="button" url="' . customeRoute($route, $parms) . '"> <img src=" '.url('admin/images/edit-a.png').'"> </a></li>';
}

//VIEW BUTTON FUNCTION 
function viewButton($route, $parms)
{
    return '<li><a class="dropdown-item model_open"  type="button" url="' . customeRoute($route, $parms) . '"> <img src=" '.url('admin/images/view-a.png').'"> </a></li>';
}

//CHANGE BUTTON FUNCTION 
function changePasswordButton($route, $parms)
{
    return '<li><a class="dropdown-item model_open" href="#"  url="' . customeRoute($route, $parms) . '"> Change Password </a></li>';
}

//CHANGE BUTTON FUNCTION 
function add_month($route, $parms)
{
    return '<li><a class="dropdown-item model_open" href="#"  url="' . customeRoute($route, $parms) . '"> Give Additional Month </a></li>';
}




//DELETE BUTTON 
function deleteButton($route, $parms)
{
    return '<li><a class="dropdown-item delete_record" type="button" url="' . customeRoute($route, $parms) . '"> <img src=" '.url('admin/images/delete-a.png').'"> </a></li>';
}


//DELETE BUTTON 
function businessDetailButton($route, $parms)
{
    return '<li><a class="dropdown-item" href="#"  url="' . customeRoute($route, $parms) . '"> Business Detail </a></li>';
}






//BACK BUTTON FUNCTION 
function backAction($route, $btn_title)
{
    return '<a href="' . $route . '" class="btn btn-outline-primary rounded-0"><i class="bx bxs-left-arrow-circle"></i>' . $btn_title . ' </a>';
}

// STATUS CHANGE BUTTON 
function statusAction($status, $id, $array = null, $class = 'statusAction', $path = null)
{
    $array = ['Active' => 1, 'Inactive' => 0];

    $html = '<select type="button" name="status" class="form-select ' . $class . '" id="' . $id . '" data-path= "' . customeRoute($path) . '">';

    foreach ($array as $key => $value) {

        $html .= '<option value="' . $value . '"';
        if ($value == $status) {
            $html .= 'selected';
        }
        $html .= ' >' . $key . '</opiton>';
    }
    $html .= '</select>';
    return $html;
}



// verification CHANGE BUTTON 
function verificationAction($status, $id, $array = null, $class = 'verificationAction', $path = null)
{

    
    $array = ['Verified' => 1, 'Un verified' => 0];

    $html = '<select name="verification_status" class="form-select ' . $class . '" id="' . $id . '" data-path= "' . customeRoute($path) . '">';

    foreach ($array as $key => $value) {

        $html .= '<option value="' . $value . '"';
        if ($value == $status) {
            $html .= 'selected';
        }
        $html .= ' >' . $key . '</opiton>';
    }
    $html .= '</select>';
    return $html;
}



function roleName(){
  $data =   Auth::user()->getRoleNames();
  return $data[0];

}



/**********************************  HELPER FUNCTIONS   ********************************/



function totalUser($referral_id)
{
    $query = User::whereHas(
        'roles', function($q){
            $q->where('name', 'user');
        }
    );
    $count = $query->where('perent_referral_id', $referral_id)->count();
    return $count;
}


//Total Employee count 
function totalEmployee($referral_id)
{
    $query = User::whereHas(
        'roles', function($q){
            $q->where('name', 'employee');
        }
    );
    $count = $query->where('perent_referral_id', $referral_id)->count();
    return $count;
}


function totalBusiness($id)
{
    $query = User::whereHas(
        'roles', function($q){
            $q->where('name', 'business');
        }
    );
    $count = $query->where('perent_referral_id', $id)->count();
    return $count;
}



//Total Employee count 
function totalEarning($id)
{
    $earning = Earning::where('user_id',$id)->sum('amount');
  
    return $earning;
}


function paidAmount($id)
{
    $amount = PaymentRequest::where(['user_id' => $id, 'status' => Constant::ACTIVE])->sum('amount');
    return $amount;
}

function unpaidAmount($id)
{
    $earning = Earning::where('user_id',$id)->sum('amount');
    $amount = PaymentRequest::where(['user_id' => $id, 'status' => Constant::ACTIVE])->sum('amount');
    $count = $earning - $amount;
    return $count;

}



function businessType($id)
{
    $data = BusinessType::where('id', $id)->first();
    $d = $data->business_type ?? '-';
    return ucfirst($d);
}




//STAFF ROLE GET FUNCTION 
function staffRole()
{
    $data = Role::where('name', '!=', 'admin')->get();
    return $data;
}

//UPLOAD IMAGE FUNCTION 
function uploadImage($image, $path)
{
    $file = $image;
    //$image_resize = \Image::make($file);
    // $image_resize->resize(500, 500);
    $name = $image->getClientOriginalName();
    $filename = time() . "-" .$name;
    $filename = str_replace(' ', '_', $filename);
    $filename = str_replace('-', '_', $filename);

    // if (!file_exists($path)) { //Verify if the directory exists
    //     mkdir($path, 777, true); //create it if do not exists
    // }
    $image->move($path , $filename);
    return $filename;
}


function uploadDocumentsKp($image, $path)
{
    $file = $image;
    //$image_resize = \Image::make($file);
    // $image_resize->resize(500, 500);
    $name = $image->getClientOriginalName();
    $filename = "kp".date('ymdHis')."doc.pdf";
    $filename = str_replace(' ', '_', $filename);
    $filename = str_replace('-', '_', $filename);

    $image->move($path , $filename);
    return $filename;
}

function uploadSignKp($image, $path)
{
    $file = $image;
    //$image_resize = \Image::make($file);
    // $image_resize->resize(500, 500);
    $name = $image->getClientOriginalName();
    $filename = "sing".date('ymdHis')."kp.png";
    $filename = str_replace(' ', '_', $filename);
    $filename = str_replace('-', '_', $filename);

    $image->move($path , $filename);
    return $filename;
}

function deleteImage($full_path)
{
    if (\File::exists($full_path)) {
        unlink($full_path);
    }
}




//email id , email body , email subject 
function sendMail($email, $emailval, $subject,$pdf_output='',$pdf_name=''){
     Mail::to($email)->send(new ForgetPasswordMail($emailval,$subject,$pdf_output,$pdf_name));
        try {
            return true;
        } catch (Exception $e) {
            return false;
        }
} 


function generateOTP($digits)
{
    $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    return $otp;
}



function totalSaving($id)
{
   return 0;
}





function checkSubscription($id)
{
    $user = User::where(['status' => Constant::ACTIVE, 'subscription_status' => 1 ])->first();
    if(isset($user)){
        return true;
    }else{
        return false;
    }




}


function generateNumericOTP($n)
{
    $digits = $n;
    $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    return $otp;
}


function sendSms($number,$message ){
    // $smsalert 	= (new Smsalert()) 		
    // ->authWithUserIdPwd(Constant::USERNAME,Constant::ACCOUNT_PASSWORD)
    // ->setRoute(Constant::ROUTE)
    // ->setForcePrefix(Constant::PREFIX)
    // ->setSender(Constant::SENDER_ID);
    //     //======== for send sms ================
    //     $result = $smsalert->send($number,$message); // For Send Sms
        return 1;
}






function getSettingValue($slug){
    $data = Setting::where('slug', $slug)->first();
    return $data->value;
}

function getEmailContentValue($id){
    $data = EmailTemplate::where('id', $id)->where('status','1')->first();
    return $data;
}




/***
 **
 * 
 * SAPRATE BUTTON 
 */

//CREATE BUTTON FUNCTION 
function createAction($buttons)
{
    return '<div class="btn-group" role="group" aria-label="Basic example">' . $buttons . '</div>';
}

function addAction($route, $btn_title)
{
    return '<a href="' . $route . '" class="btn btn-outline-primary rounded-0"><i class="bx bxs-plus-square"></i>' . $btn_title . ' </a>';
}


function editAction($route, $parms)
{
    return '<a href="' . customeRoute($route, $parms) . '" class="btn btn-outline-primary rounded-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit"><i class="bx bx-edit"></i></a>';
}


function acceptAction($route, $parms)
{
    return '<a href="' . customeRoute($route, $parms) . '" class="btn btn-outline-primary rounded-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Accept"><i class="bx bx-check"></i></a>';
}

function random_strings($length_of_string)
{
    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shuffle the $str_result and returns substring
    // of specified length
    return substr(str_shuffle($str_result), 0, $length_of_string);
}