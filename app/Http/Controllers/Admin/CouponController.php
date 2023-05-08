<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
//use Spatie\Permission\Models\Permission;

class CouponController extends Controller
{
    protected $page = 'coupon';

    public function __construct(Request $request)
    {
        $this->model = new Coupon();
        $this->sortableColumns = ['id', 'code', 'price', 'start_date', 'expiry_date', 'type', 'status', 'updated_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    /* Function for open add or update popup */

    public function form($id = null)
    {
        $data = Coupon::where('id', $id)->first();
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $result = ['title' => ucwords($type .'Coupon Code' ), 'page' => $this->page, 'data' => $data];
        return view('admin.' . $this->page . '.form', $result);
    }

    /* Function for add or update data */

    public function manage(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $request->validate([
            'code' => 'required',
            'price' => 'required',
            'start_date' => 'required',
            'expiry_date' => 'required|after_or_equal:start_date',
            'no_of_use' => 'required',
            'type' => 'required',
            'description' => 'required',
        ]);
        if(isset($id) && !empty($id)){  // add new data
            if(Coupon::where('code', '=', $request->get('code'))->where('id','!=', $request->id)->where('deleted_at','=',null)->count() > 0){
                return response()->json(['status' => false,  'message' => 'Coupon code is already exist']);
            }
            $data = Coupon::where('id', $request->id)->first();
        }else{  // update data
            if(Coupon::where('code', '=', $request->get('code'))->where('deleted_at','=',null)->count() > 0){
                return response()->json(['status' => false,  'message' => 'Coupon code is already exist']);
            }
            $data = new Coupon();

            $data->status = '1';
        }

        $data->code = $request->get('code');
        $data->price = $request->get('price');
        $data->start_date = $request->get('start_date');
        $data->expiry_date = $request->get('expiry_date');
        $data->no_of_use = $request->get('no_of_use');
        $data->type = $request->get('type');
        $data->description = $request->get('description');
        if ($data->save()) {
                return response()->json(['status' => true, 'message' => 'Coupon code has been '.$type.' succesfully']);
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }

    /* Function for view list of Coupon Code */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $name_search = $request->input('name_search');
            $status_search = $request->input('status_search');
            $type_search = $request->input('type_search');
            $start_date = $request->input('start_date');
            $expiry_date = $request->input('expiry_date');
            //$search = $request['search']['value'];

            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $sortableColumns = $this->sortableColumns;

            $start_date = ($request->get('start_date')) ? date('Y-m-d 00:00:01', strtotime($request->get('start_date'))) : date('Y-m-d 00:00:01', strtotime('-1 year', strtotime(date('Y-m-d'))));
            $end_date = ($request->get('end_date')) ? date('Y-m-d 23:59:59', strtotime($request->get('end_date'))) : date('Y-m-d 23:59:59');

            //$totaldata = $this->getData($search, $sortableColumns[$orderby], $order);
            $response = $this->getData($name_search,$type_search,$status_search,$start_date,$expiry_date, $sortableColumns[$orderby], $order);

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
                $row['code'] = $value->code;
                $row['price'] = Constant::CURRENCY.$value->price;
                $row['start_date'] = date('j F Y',strtotime($value->start_date));
                $row['expiry_date'] = date('j F Y',strtotime($value->expiry_date));
                if($value->type=='1'){ $type = 'Template';}else{ $type = 'Subscription';}
                $row['type'] = $type;
                $row['status'] = statusAction($value->status,$value->id,array(),'statusAction',$this->page . '.status');
                $row['updated_at'] = default_date_format($value->created_at);

                $edit = '';
                //if(Auth::user()->can('Update Email Template')){
                    $edit = editButton($this->page . '.form', ['id' => $value->id]);
                //}

                $view = '';
                //if(Auth::user()->can('View Email Template')){
                    $view = viewButton($this->page . '.view', ['id' => $value->id]);

                    $send = '<li>
        <a class="dropdown-item model_open" type="button" url="'.route('coupon.send_coupon').'email?id='.$value->id.'"> Send via Email </a>
    </li>
    <li>
        <a class="dropdown-item model_open" type="button" url="'.route('coupon.send_coupon').'sms?id='.$value->id.'"> Send via SMS </a>
    </li>';
                //}
                    $delete = deleteButton($this->page . '.delete', ['id' => $value->id]);

                $row['actions'] = createButton($edit . $view . $delete . $send);
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
        $data = ['title' => ucfirst('Coupon Code'), 'page' => $this->page];
        return view('admin.' . $this->page . '.index', $data);
    }

    /* Function for get data */

    public function getData($name_search = null ,$type_search = null ,$status_search = null ,$start_date = null ,$expiry_date = null ,$orderby = null, $order = null, $request = null)
    {
        $q = Coupon::where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'created_at';
        $order = $order ? $order : 'desc';

        if ($name_search && !empty($name_search)) {
            $q->where('code', 'LIKE', '%' . $name_search . '%');
        }
        if ($type_search=='0') {
                $q->where('type','=', '0');
        }elseif($type_search=='1') {
            $q->where('type','=', '1');
        }
        if ($status_search=='0') {
                $q->where('status','=', '0');
        }elseif($status_search=='1') {
            $q->where('status','=', '1');
        }
        if ($start_date && !empty($start_date)) {
                $q->where('start_date', '>=', date('Y-m-d',strtotime($start_date)));
        }
        if ($expiry_date && !empty($expiry_date)) {
                $q->where('expiry_date', '<=', date('Y-m-d',strtotime($expiry_date)));
        }
        // if ($search && !empty($search)) {
        //     $q->where(function ($query) use ($search) {
        //         $query->where('code', 'LIKE', '%' . $search . '%')             
        //         ->where('price', 'LIKE', '%' . $search . '%');             
        //     });
        // }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }

    /* Function for view Coupon Code deatils */

    public function view($id)
    {
        $data = Coupon::where('id', $id)->first();
        if (isset($data)) {
            $result = ['title' => ucwords('Coupon Code Details'), 'page' => $this->page, 'data' => $data,'currency'=>Constant::CURRENCY];
            return view('admin.' . $this->page . '.view', $result);
        } else {
            $result = ['title' => ucwords('Error'), 'page' => $this->page];
            return view('admin.error.404_popup', $result);
        }
    }

    /* Function for change status - active or inactive */

    public function status(Request $request)
    {     
        $id = $request->get('id');
        if($request->get('status')==Constant::ACTIVE){
            $status = 'active';
        }else{
            $status = 'in-active';
        }
        $data = Coupon::where('id', $id)->first();
        $data->status = $request->get('status');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'Coupon code has been '.$status.' succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    /* Function for delete data */

    public function delete(Request $request)
    {     
        $id = $request->get('id');
        $data = Coupon::where('id', $id)->first();
        $data->deleted_at = date('Y-m-d');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'Coupon code has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    /* Function for Send Coupon deatils via Email or SMS */

    public function send_coupon($via)
    {
        $users = User::selectRaw('CONCAT(first_name, \' \', last_name) as name,id')->where('deleted_at', null)->where('status', '1')->get();
        $id = $_GET['id'];
        if (isset($via)) {
            $result = ['title' => ucwords('Send Coupon Code Via'), 'page' => $this->page, 'users' => $users, 'via' => $via, 'id' => $id];
            return view('admin.' . $this->page . '.sendCoupon', $result);
        } else {
            $result = ['title' => ucwords('Error'), 'page' => $this->page];
            return view('admin.error.404_popup', $result);
        }
    }

    public function send_via_email(Request $request)
    {
        $via = $request->get('via');
        $id = $request->get('id');
        $user = $request->get('user');
        if($via=='email' && !empty($id)){
            $data = Coupon::where('id', $id)->where('deleted_at', null)->where('status', '1')->first();
            $content = getEmailContentValue(6);
                if($content){
                    //echo getSettingValue('logo'); die();
                    if(empty(getSettingValue('logo'))){
                        $logo = url('images/logo.png');
                    }else{
                        $logo = url('uploads/logo').'/'.getSettingValue('logo');
                    }
                    foreach ($user as $id) {
                        $users = User::selectRaw('CONCAT(first_name, \' \', last_name) as name,email')->where('deleted_at', null)->where('status', '1')->where('id', $id)->first();
                        $emailval = $content->description;
                        $subject = $content->subject.' - '.$users->name;
                        $replace_data = [
                                '@user_name' => $users->name,
                                '@expiry_date' => date('j F Y',strtotime($data->expiry_date)),
                                '@coupon_code' => $data->code,
                                '@coupon_description' => nl2br($data->description),
                                '@logo' => $logo,
                            ];

                        foreach ($replace_data as $key => $value) {
                            $emailval = str_replace($key, $value, $emailval);
                        }
                        if (sendMail($users->email, $emailval, $subject)) {
                            //return response()->json($json);
                        } else {
                            return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again.']);
                        }
                    }
                    return response()->json(['status' => true, 'message' => 'Email send succesfully to selected users']);
                }
        }else{
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    public function send_via_sms(Request $request)
    {
        $via = $request->get('via');
        $id = $request->get('id');
        if($via=='sms' && !empty($id)){
            return response()->json(['status' => false, 'message' => 'SMS getway is not available']);
        }else{
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
}
