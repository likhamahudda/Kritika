<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionFeatures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
//use Spatie\Permission\Models\Permission;

class SubscriptionController extends Controller
{
    protected $page = 'subscription';

    public function __construct(Request $request)
    {
        $this->model = new Subscription();
        $this->sortableColumns = ['id', 'subscription_type', 'plan_name', 'status', 'updated_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    public function form($id = null)
    {
        $data = Subscription::where('id', $id)->first();
        $data_f1 = SubscriptionFeatures::where('subscription_plan_id', $id)->where('type', '1')->first();
        $data_f2 = SubscriptionFeatures::where('subscription_plan_id', $id)->where('type', '2')->first();
        $data_f3 = SubscriptionFeatures::where('subscription_plan_id', $id)->where('type', '3')->first();
        $data_f0 = SubscriptionFeatures::where('subscription_plan_id', $id)->where('type', '0')->get();
        $data_f=array('data_f1'=>$data_f1,'data_f2'=>$data_f2,'data_f3'=>$data_f3,'data_f0'=>$data_f0);
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $result = ['title' => ucwords($type .'Subscription Plan' ), 'page' => $this->page, 'data' => $data, 'data_f' => $data_f];
        return view('admin.' . $this->page . '.form', $result);
    }

    public function manage(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $request->validate([
            'subscription_type' => 'required',
            'plan_name' => 'required',
            'monthly_price' => 'required',
            'plan_yearly_but_montly_price' => 'required',
        ]);
        if(isset($id) && !empty($id)){
            $data = Subscription::where('id', $request->id)->first();
        }else{
            
            $data = new Subscription();

            $data->status = '1';
        }

        $data->subscription_type = $request->get('subscription_type');
        $data->plan_name = $request->get('plan_name');
        $data->monthly_price = $request->get('monthly_price');       
        $data->plan_yearly_but_montly_price = $request->get('plan_yearly_but_montly_price');
        if ($data->save()) {
            foreach ($request->get('count') as $key => $value) {
                if(isset($id) && !empty($id)){
                    $data_count = SubscriptionFeatures::where('subscription_plan_id', $id)->where('type', $key)->first();
                    $data_count->count = $value;
                    $data_count->save();
                }else{
                    $data_count = new SubscriptionFeatures();
                    $data_count->subscription_plan_id = $data->id;
                    $data_count->type = $key;
                    $data_count->count = $value;
                    if($key==1){ $description = 'Monthly Signatures'; }
                    elseif($key==2){ $description = 'Reusable Templates'; }
                    elseif($key==3){ $description = '(Receive & sign)'; }
                    $data_count->description = $description;
                    $data_count->save();              
                }
            }
            if($request->get('description')!==null){
                foreach ($request->get('description') as $key => $value) {
                    $data_f = SubscriptionFeatures::where('id', $key)->first();
                    $data_f->description = $value;
                    $data_f->save();
                }
            }
            if($request->get('description0')!==null){
                foreach ($request->get('description0') as $desc) {
                    $data_f0 = new SubscriptionFeatures();
                    $data_f0->subscription_plan_id = $data->id;
                    $data_f0->type = '0';
                    $data_f0->description = $desc;
                    $data_f0->save();                
                }
            }
            return response()->json(['status' => true, 'message' => 'Subscription plan has been '.$type.' succesfully']);
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }



    public function index(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $search = $request['search']['value'];

            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $sortableColumns = $this->sortableColumns;

            $start_date = ($request->get('start_date')) ? date('Y-m-d 00:00:01', strtotime($request->get('start_date'))) : date('Y-m-d 00:00:01', strtotime('-1 year', strtotime(date('Y-m-d'))));
            $end_date = ($request->get('end_date')) ? date('Y-m-d 23:59:59', strtotime($request->get('end_date'))) : date('Y-m-d 23:59:59');

            $totaldata = $this->getData($search, $sortableColumns[$orderby], $order);

            $totaldata = $totaldata->count();
            $response = $this->getData($search, $sortableColumns[$orderby], $order);
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
                $row['plan_name'] = ucfirst($value->plan_name);
                if($value->subscription_type==1){ $type='Account Plan'; }else{ $type='API Plan'; }
                $row['subscription_type'] = $type;
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
        $data = ['title' => ucfirst('Subscription Management'), 'page' => $this->page];
        return view('admin.' . $this->page . '.index', $data);
    }

    public function getData($search = null  ,$orderby = null, $order = null, $request = null)
    {
        $q = Subscription::where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'created_at';
        $order = $order ? $order : 'desc';

        if ($search && !empty($search)) {
            $q->where(function ($query) use ($search) {
                $query->where('plan_name', 'LIKE', '%' . $search . '%');
                if(like_match('%' . $search . '%','API Plan')){
                    $c='2';
                    $query->orwhere('subscription_type', '=', $c);
                }
                if(like_match('%' . $search . '%','Account Plan')){
                    $c='1';
                    $query->orwhere('subscription_type', '=', $c);
                }
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }


    public function view($id)
    {
        $data = Subscription::where('id', $id)->first();
        $data_f = SubscriptionFeatures::where('subscription_plan_id', $id)->get();
        if (isset($data)) {
            $result = ['title' => ucwords('Subscription Plan Details'), 'page' => $this->page, 'data' => $data, 'data_f' => $data_f];
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
        $data = Subscription::where('id', $id)->first();
        $data->status = $request->get('status');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'Subscription plan has been '.$status.' succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
    public function delete(Request $request)
    {     
        $id = $request->get('id');
        $data = Subscription::where('id', $id)->first();
        $data->deleted_at = date('Y-m-d');
        if ($data->save()) {
            $data_f = SubscriptionFeatures::where('subscription_plan_id', $id)->delete();
            return response()->json(['status' => true, 'message' => 'Subscription plan has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    public function delete_feature(Request $request)
    {     
        $id = $request->get('id');
        $data = SubscriptionFeatures::where('id', $id)->first();
        if ($data->delete()) {
            return response()->json(['status' => true, 'message' => 'Subscription plan feature has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    public function mostpopular(){
        $data1 = Subscription::select('id','plan_name','is_popular')->where('subscription_type', '1')->where('deleted_at','=',null)->where('status','=','1')->get();
        $data2 = Subscription::select('id','plan_name','is_popular')->where('subscription_type', '2')->where('deleted_at','=',null)->where('status','=','1')->get();
        $result = ['title' => ucwords('Select Most Popular Plan' ), 'page' => $this->page, 'data1' => $data1, 'data2' => $data2];
        return view('admin.' . $this->page . '.mostpopular', $result);
    }

    public function managemp(Request $request){
        $account_type = $request->get('account_type');
        $api_type = $request->get('api_type');
        $data0 = Subscription::where('is_popular', '1')->update(array('is_popular' => '0'));
        $data1 = Subscription::where('id', $account_type)->first();
        $data2 = Subscription::where('id', $api_type)->first();
        if(isset($data1) && !empty($data1)){
            $data1->is_popular = '1';
            $data1->save();
        }
        if(isset($data2) && !empty($data2)){
            $data2->is_popular = '1';
            $data2->save();
        }
        // if ($data1->save()) {
        //     if ($data2->save()) {
        //             return response()->json(['status' => true, 'message' => 'Set succesfully']);
        //         } else {
        //             return response()->json(['status' => false, 'message' => 'Something went wrong']);
        //         }
        // } else {
        //     return response()->json(['status' => false, 'message' => 'Something went wrong']);
        // }
        return response()->json(['status' => true, 'message' => 'Set succesfully']);
    }
}
