<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country; 
use App\Models\States;
use App\Models\Districts; 
use App\Models\Tehsils; 
use App\Models\Panchayat; 
use App\Models\Village; 
use App\Models\Occupations;  
use App\Models\Education; 
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\Export;
use Maatwebsite\Excel\Facades\Excel;
//use Illuminate\Support\Str;
//use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem;

class UserController extends Controller
{
    protected $page = 'users';

    public function __construct(Request $request)
    {
        $this->model = new User();
        $this->sortableColumns = ['id', 'first_name', 'email', 'mobile', 'status', 'updated_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    public function form($id = null)
    {
        $data = User::where('id', $id)->first();
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $result = ['title' => ucwords($type .'User' ), 'page' => $this->page, 'data' => $data];
        return view('admin.' . $this->page . '.form', $result);
    }

    public function manage(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'address' => 'required',
            'gender' => 'required',
            //'zip_code' => 'required',
            //'password' => 'required',
        ]);
        if($request->get('email')==Auth::user()->email){
            return response()->json(['status' => false,  'message' => "Can't use this email id"]);
        }
        if(isset($id) && !empty($id)){
            $data = User::where('id', $request->id)->first();
            if(User::where('email', '=', $request->get('email'))->where('id','!=', $request->id)->where('deleted_at','=',null)->count() > 0){
                return response()->json(['status' => false,  'message' => 'User is already exist with given email id']);
            }
            if(User::where('mobile', '=', $request->get('mobile'))->where('id','!=', $request->id)->where('deleted_at','=',null)->count() > 0){
                return response()->json(['status' => false,  'message' => 'User is already exist with given phone number']);
            }
            if($request->get('is_change')=='1'){
                $data->password =  Hash::make($request->get('password'));
            }
        }else{
            if(User::where('email', '=', $request->get('email'))->where('deleted_at','=',null)->count() > 0){
                return response()->json(['status' => false,  'message' => 'User is already exist with given email id']);
            }
            if(User::where('mobile', '=', $request->get('mobile'))->where('deleted_at','=',null)->count() > 0){
                return response()->json(['status' => false,  'message' => 'User is already exist with given phone number']);
            }
            $data = new User();
            //$data->assignRole('user');
            //echo $data->getRoleNames(); die();

            $data->status = '1';
            $data->email_verified_at = date('Y-m-d');
            $data->verification_status = '1';
            $data->password =  Hash::make($request->get('password'));
        }

        $data->first_name = $request->get('first_name');
        $data->last_name = $request->get('last_name');
        $data->email = $request->get('email');
        $data->mobile = $request->get('mobile');
        $data->address = $request->get('address');
        $data->gender = $request->get('gender');
        //$data->zip_code = $request->get('zip_code');
        
        // if (isset($request->image)) {
        //     $path = 'uploads/user_profile/';
        //     if(!empty($data->image)){
        //         deleteImage($path . $data->image);
        //     }
        //     $image_path =  uploadImage($request->image, $path);
        //     $data->image = $image_path;
        //     $json = ['status' => true, 'src' => url($path) .'/'. $data->image, 'message' => ucfirst($this->page) . ' has been '.$type.' succesfully'];
        // }else{
                $json = ['status' => true, 'message' => ucfirst($this->page) . ' has been '.$type.' succesfully'];
        // }
        if ($data->save()) {
            if(empty($id)){
                $content = getEmailContentValue(2);
                if($content){
                    $emailval = $content->description;
                    $subject = $content->subject.' - '.$request->get('first_name').' '.$request->get('last_name');
                    //echo getSettingValue('logo'); die();
                    if(empty(getSettingValue('logo'))){
                        $logo = url('images/logo.png');
                    }else{
                        $logo = url('uploads/logo').'/'.getSettingValue('logo');
                    }
                    $replace_data = [
                            '@name0' => $request->get('first_name').' '.$request->get('last_name'),
                            '@username_0' => $request->get('email'),
                            '@password0' => $request->get('password'),
                            '@logo' => $logo,
                        ];

                        foreach ($replace_data as $key => $value) {
                            $emailval = str_replace($key, $value, $emailval);
                        }
                    if (sendMail($request->get('email'), $emailval, $subject)) {
                        return response()->json($json);
                    } else {
                        return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again.']);
                    }
                }
                    // else{
                    //     return response()->json($json);
                    // }
            }else{
                return response()->json($json);
            }
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }



    public function index(Request $request)
    {


    

  // Clear the route cache
        Artisan::call('route:clear'); 
        // Get the path to the route cache file
        $path = base_path('bootstrap/cache/routes.php'); 
        // Delete the route cache file
        if (file_exists($path)) {
            app(Filesystem::class)->delete($path);
        }
  
        if ($request->ajax()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $name_search = $request->input('name_search');
            $email_search = $request->input('email_search');
            $mobile_search = $request->input('mobile_search');
            $status_search = $request->input('status_search');
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
            //$search = $request['search']['value'];

            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $sortableColumns = $this->sortableColumns;

            $start_date = ($request->get('start_date')) ? date('Y-m-d 00:00:01', strtotime($request->get('start_date'))) : date('Y-m-d 00:00:01', strtotime('-1 year', strtotime(date('Y-m-d'))));
            $end_date = ($request->get('end_date')) ? date('Y-m-d 23:59:59', strtotime($request->get('end_date'))) : date('Y-m-d 23:59:59');

            $response = $this->getData($name_search,$email_search,$mobile_search,$status_search,$from_date,$to_date, $sortableColumns[$orderby], $order);

            $totaldata = $response->count();
            $response->whereBetween('created_at', [$start_date, $end_date]);

            $response = $response->offset($start)->limit($limit)->orderBy('id', 'desc')->get();
            if (!$response) {
                $data = [];
                $paging = [];
            } else {
                $data = $response;
                $paging = $response;
            }

            $datas = [];
            $i = 1;
            foreach ($data as $value) {
                $row['id'] = $start + $i;
                $row['name'] = ucfirst($value->first_name).' '.ucfirst($value->last_name);
                $row['email'] = $value->email;
                $row['mobile'] = $value->mobile;
                $row['status'] = statusAction($value->status,$value->id,array(),'statusAction',$this->page . '.status');
                $row['updated_at'] = default_date_format($value->created_at);

                $edit = '';
                //if(Auth::user()->can('Update Email Template')){
                    $edit = editButton($this->page . '.form', ['id' => $value->id]);
                //}

                $view = '';
                //if(Auth::user()->can('View Email Template')){
                    $view = viewButton($this->page . '.view', ['id' => $value->id]);
                //}
                    $delete = deleteButton($this->page . '.delete', ['id' => $value->id]);

                $row['actions'] = createButton($edit . $view . $delete);
                $datas[] = $row;
                $i++;
                unset($u);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($totaldata),
                "recordsTotal" => intval($totaldata),
                "data" => $datas,
            ];
            return $return;
        }
        $data = ['title' => ucfirst('User Management'), 'page' => $this->page];
        return view('admin.' . $this->page . '.index', $data);
    }

    public function getData($name_search = null,$email_search = null,$mobile_search = null,$status_search = null,$from_date = null,$to_date = null,$orderby = null, $order = null, $request = null)
    {
        $q = User::where('id','!=','1')->where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'created_at';
        $order = $order ? $order : 'desc';

        if ($name_search && !empty($name_search)) {
                $q->whereRaw('CONCAT(first_name, \' \' , last_name) LIKE \'%'.$name_search.'%\' ');
        }
        if ($email_search && !empty($email_search)) {
            $q->where('email','LIKE', '%'.$email_search.'%');
        }
        if ($mobile_search && !empty($mobile_search)) {
            $q->where('mobile','LIKE', '%'.$mobile_search.'%');
        }
        if ($status_search=='0') {
                $q->where('status','=', '0');
        }elseif($status_search=='1') {
            $q->where('status','=', '1');
        }
        if ($from_date && !empty($from_date)) {
                $q->where('created_at', '>=', $from_date);
        }
        if ($to_date && !empty($to_date)) {
                $q->where('created_at', '<=', $to_date);
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }

    public function add(Request $request)
    {

        $data_country = Country::where('status', '1')->where('deleted_at', '=', null)->get();
        $data_occupations = Occupations::where('status', '1')->where('deleted_at', '=', null)->get();
        $data_education = Education::where('status', '1')->where('deleted_at', '=', null)->get();


  
        $result = ['title' => 'Add New Families', 'page' => $this->page,
        'data_country' => $data_country,
        'data_occupations' => $data_occupations,
        'data_education' => $data_education
    ];
        return view('admin.users.add', $result);
    }

    public function getStatesByCountry(Request $request)
    { 
        $states = States::where('country_id', $request->country_id)->orderBy('name')->get();
        return response()->json($states);
    }
    public function getDistrictsByState(Request $request)
    { 
        $districts = Districts::where('state_id', $request->state_id)->orderBy('name')->get();
        return response()->json($districts);
    }

    public function getTehsilByState(Request $request)
    { 
        $districts = Tehsils::where('district_id', $request->district_id)->orderBy('name')->get();
        return response()->json($districts);
    }

    public function getPanchayatBytehsil(Request $request)
    { 
        $districts = Panchayat::where('tehsil_id', $request->tehsil_id)->orderBy('name')->get();
        return response()->json($districts);
    }

    public function getVillageBytehsil(Request $request)
    { 
        $Village = Village::where('panchayat_id', $request->panchayat_id)->orderBy('name')->get();
        return response()->json($Village);
    }
  
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:30',
        ]);

        $role = new Role();
        $role->name = $request->get('name');
        $role->guard_name = 'web';
        if ($role->save()) {
            $role->givePermissionTo($request->permission_id);
            return response()->json(['status' => true, 'message' => ucfirst($this->page) . ' has been created succesfully', 'redirect_url' => route('role.index')]);
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }



    public function view($id)
    {
        $data = User::where('id', $id)->first();
        if (isset($data)) {
        $payment_active = Payments::where('user_id', $id)->where('transaction_status', '1')
        //->whereRaw("FORMAT(expire_date,'yyyy-MM-dd') >= ".date('Y-m-d'))->get();
        ->where('expire_date','>=',date('Y-m-d'))->get();
        $payment_expire = Payments::where('user_id', $id)->where('transaction_status', '1')
        ->where('expire_date','<',date('Y-m-d'))->get();
            $result = ['title' => ucwords('User Details'), 'page' => $this->page, 'data' => $data, 'payment_expire' => $payment_expire, 'payment_active' => $payment_active, 'currency' => CONSTANT::CURRENCY];
            return view('admin.' . $this->page . '.view', $result);
        } else {
            $result = ['title' => ucwords('Error'), 'page' => $this->page];
            return view('admin.error.404_popup', $result);
        }
    }

    public function status(Request $request)
    {     
        $id = $request->get('id');
        if($request->get('status')==Constant::ACTIVE){
            $status = 'active';
        }else{
            $status = 'in-active';
        }
        $data = User::where('id', $id)->first();
        $data->status = $request->get('status');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => ucfirst($this->page) . ' has been '.$status.' succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
    public function delete(Request $request)
    {     
        $id = $request->get('id');
        $data = User::where('id', $id)->first();
        $data->deleted_at = date('Y-m-d');
        if ($data->save()) {
            $path = 'uploads/user_profile/';
            if(!empty($data->image)){
                deleteImage($path . $data->image);
            }
            return response()->json(['status' => true, 'message' => ucfirst($this->page) . ' has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    public function users_export(Request $request) 
    {   
        $name_search = $request->name_search;
        $email_search = $request->email_search;
        $mobile_search = $request->mobile_search;
        $status_search = $request->status_search;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $collection=$this->getData($name_search,$email_search,$mobile_search,$status_search,$from_date,$to_date)->selectRaw('id,CONCAT(first_name, \' \', last_name) as name,email,mobile,status,DATE_FORMAT(created_at,\'%d %M %Y \')')->get();
        foreach ($collection as $data) {
            if($data->status==1){
                $data->status='Active';
            }else{
                $data->status='In-active';
            }
        }

        $headings=["ID", "Name", "Email", "Phone. no", "Status", "Register Date"];
        return Excel::download(new Export($collection,$headings), 'users report.csv');
    }
}
