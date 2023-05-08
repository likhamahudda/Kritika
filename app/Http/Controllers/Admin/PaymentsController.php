<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\Payments;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Exports\Export;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
//use Spatie\Permission\Models\Permission;

class PaymentsController extends Controller
{
    protected $page = 'payments';

    public function __construct(Request $request)
    {
        $this->model = new Payments();
        $this->sortableColumns = ['id', 'user_name', 'plan_name', 'total_subscription_price', 'transaction_status', 'created_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    /* Function for view list of payments */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $name_search = $request->input('name_search');
            $plan_type = $request->input('plan_type');
            $plan_id = $request->input('plan_id');
            $status_search = $request->input('status_search');
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');

            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $sortableColumns = $this->sortableColumns;

            $start_date = ($request->get('start_date')) ? date('Y-m-d 00:00:01', strtotime($request->get('start_date'))) : date('Y-m-d 00:00:01', strtotime('-1 year', strtotime(date('Y-m-d'))));
            $end_date = ($request->get('end_date')) ? date('Y-m-d 23:59:59', strtotime($request->get('end_date'))) : date('Y-m-d 23:59:59');

            $response = $this->getData($name_search,$plan_type,$plan_id,$status_search,$from_date,$to_date, $sortableColumns[$orderby], $order);

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
                $row['user_name'] = ucfirst($value->user_name);
                $row['plan_name'] = ucfirst($value->plan_name);
                $row['amount'] = Constant::CURRENCY.$value->total_subscription_price;
                if($value->transaction_status=='1'){ $transaction_status='Complete'; }
                else{ $transaction_status='Failed'; }
                $row['status'] = $transaction_status;
                $row['updated_at'] = default_date_format($value->created_at);

                $view = viewButton($this->page . '.view', ['id' => $value->id]);
                $send='';
                $download='';
                if($value->transaction_status=='1'){
                    $send='<li><a class="dropdown-item" onclick="send_invoice('.$value->id.');" type="button"> Send Invoice </a></li>';
                    $download='<li><a class="dropdown-item" onclick="download_invoice('.$value->id.');" type="button"> Download Invoice </a></li>';
                }
                $row['actions'] = createButton($view.$send.$download);
                //$row['actions'] = '<div align="center"><i style="font-size:20px;cursor:pointer" class="bx bx-show model_open" url="'.customeRoute($this->page . '.view', ['id' => $value->id]).'" ></i></div>';
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
        $last_month =  Constant::CURRENCY.Payments::whereMonth('created_at', date('m', strtotime('first day of last month')))->whereYear('created_at', date('Y'))->where('transaction_status','1')->where('deleted_at' ,NULL)->sum('total_subscription_price');
        $this_month =  Constant::CURRENCY.Payments::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('transaction_status','1')->where('deleted_at' ,NULL)->sum('total_subscription_price');
        $this_year =  Constant::CURRENCY.Payments::whereYear('created_at', date('Y'))->where('transaction_status','1')->where('deleted_at' ,NULL)->sum('total_subscription_price');
        $total =  Constant::CURRENCY.Payments::where('transaction_status','1')->where('deleted_at' ,NULL)->sum('total_subscription_price');
        $data = ['title' => ucfirst('Payments Management'), 'page' => $this->page, 'last_month' => $last_month, 'this_month' => $this_month, 'this_year' => $this_year, 'total' => $total];
        return view('admin.' . $this->page . '.index', $data);
    }

    /* Function for get data */

    public function getData($name_search = null,$plan_type = null,$plan_id = null,$status_search = null,$from_date = null,$to_date = null,$orderby = null, $order = null, $request = null)
    {
        $q = Payments::where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'created_at';
        $order = $order ? $order : 'desc';
        // $q = DB::table('transaction_management')
        //     ->join('subscription_plan', 'transaction_management.subscription_plan_id', '=', 'subscription_plan.id')
        //     ->join('users', 'users.id', '=', 'transaction_management.user_id')
        //     ->select('transaction_management.*', 'subscription_plan.plan_name', 'subscription_plan.subscription_type', 'users.first_name', 'users.last_name')
        //     ->where('transaction_management.deleted_at','=',null);
        // if ($search && !empty($search)) {
        //     $q->where(function ($query) use ($search) {
        //         $query->where('users.first_name', 'LIKE', '%' . $search . '%')
        //         ->orwhere('subscription_plan.plan_name', 'LIKE', '%' . $search . '%')                
        //         ->orwhere('transaction_management.total_subscription_price', 'LIKE', '%' . $search . '%');                
        //     });
        // }
        if ($name_search && !empty($name_search)) {
                //$q->whereRaw('CONCAT(users.first_name, \' \' , users.last_name) LIKE \'%'.$name_search.'%\' ');
            $q->where('user_name','LIKE','%'.$name_search.'%');
        }
        if ($plan_type && !empty($plan_type)) {
            $q->where('subscription_type',$plan_type);
        }
        if ($plan_id && !empty($plan_id)) {
            $q->where('subscription_plan_id',$plan_id);
        }
        if ($status_search=='0') {
                $q->where('transaction_status','=', '0');
        }elseif($status_search=='1') {
            $q->where('transaction_status','=', '1');
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

    /* Function for view Testimonial deatils */
    
    public function view($id)
    {
        $data = Payments::where('id', $id)->where('deleted_at','=',null)->first();
        // $data = DB::table('transaction_management')
        //     ->join('subscription_plan', 'transaction_management.subscription_plan_id', '=', 'subscription_plan.id')
        //     ->join('users', 'users.id', '=', 'transaction_management.user_id')
        //     ->select('transaction_management.*', 'subscription_plan.plan_name', 'subscription_plan.subscription_type', 'users.first_name', 'users.last_name')
        //     ->where('transaction_management.deleted_at','=',null)
        //     ->where('transaction_management.id', $id)->first();
        if (isset($data)) {
            $result = ['title' => ucwords('Payments Details'), 'page' => $this->page, 'data' => $data];
            return view('admin.' . $this->page . '.view', $result);
        } else {
            $result = ['title' => ucwords('Error'), 'page' => $this->page];
            return view('admin.error.404_popup', $result);
        }
    }

    public function payment_export(Request $request) 
    {   
        $name_search = $request->name_search;
        $plan_type = $request->plan_type;
        $plan_id = $request->plan_id;
        $status_search = $request->status_search;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $collection=$this->getData($name_search,$plan_type,$plan_id,$status_search,$from_date,$to_date)->selectRaw('id,user_name,subscription_type,plan_name,transaction_status,DATE_FORMAT(created_at,\'%d %M %Y \')')->get();
        foreach ($collection as $data) {
            if($data->transaction_status==1){
                $data->transaction_status='Complate';
            }else{
                $data->transaction_status='Failed';
            }
            if($data->subscription_type==1){
                $data->subscription_type='Account Plan';
            }elseif($data->subscription_type==2){
                $data->subscription_type='API Plan';
            }
        }

        $headings=["ID", "Name", "Subscription Type", "Subscription Plan", "Payment Status", "Payment Date"];
        return Excel::download(new Export($collection,$headings), 'payment report.csv');
    }

     public function get_plans(Request $request)
    {
        $type = $request->type;
        $data = Subscription::select('id','plan_name')->where('subscription_type',$type)->where('deleted_at','=',null)->where('status','1')->get();
        echo '<option value="">Subscription Plans</option>';
        foreach ($data as $row) { ?>
            <option value="<?=$row->id?>"><?=$row->plan_name?></option>
        <?php }
    }

     public function send_invoice(Request $request)
    {
        $payment_id = $request->payment_id;
        $data = Payments::where('id', $payment_id)->where('deleted_at','=',null)->first();
        $user = User::select('email')->where('id', $data->user_id)->where('deleted_at','=',null)->first();
        $data->user_email=$user->email;
        $info['data'] = $data;
        $info['currency'] = Constant::CURRENCY;
        if(!empty($user)){
            $content = getEmailContentValue(3);
            if($content){
                $emailval = $content->description;
                $subject = $content->subject.' #'.$data->invoice_id;
                //echo getSettingValue('logo'); die();
                if(empty(getSettingValue('logo'))){     // get site logo
                    $logo = url('images/logo.png');
                }else{
                    $logo = url('uploads/logo').'/'.getSettingValue('logo');
                }
                $replace_data = [
                        '@logo' => $logo,
                        '@user_name' => $data->user_name,
                        '@invoice_number' => '#'.$data->invoice_id,
                    ];

                    foreach ($replace_data as $key => $value) {     // set values in email
                        $emailval = str_replace($key, $value, $emailval);
                    }
                $pdf = PDF::loadView('admin.' . $this->page . '.invoice_template', $info);
                //print_r($pdf->output()); die();
                if (sendMail($user->email, $emailval, $subject,$pdf->output(),'Invoice#'.$data->invoice_id.'.pdf')) {
                    return response()->json(['status' => true,  'message' => 'Invoice Send succesfully!']);
                } else {
                    return response()->json(['status' => false,  'message' => 'Something went wrong!']);
                }
            }
        }else{
            return response()->json(['status' => false,  'message' => 'This User is deleted']);
        }
        //return response()->json(['status' => true,  'message' => 'Invoice Send succesfully!']);
        //return response()->json(['status' => false,  'message' => 'Something went wrong!']);

    }

     public function download_invoice(Request $request)
    {
        $payment_id = $request->payment_id;
        $data = Payments::where('id', $payment_id)->where('deleted_at','=',null)->first();
        $user = User::select('email')->where('id', $data->user_id)->first();
        $data->user_email=$user->email;
        $info['data'] = $data;
        $info['currency'] = Constant::CURRENCY;
        if(!empty($user)){            
            $pdf = PDF::loadView('admin.' . $this->page . '.invoice_template', $info);
            return $pdf->download('Invoice#'.$data->invoice_id.'.pdf');
        }
    }
    
}
