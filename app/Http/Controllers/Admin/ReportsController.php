<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Testimonial;
use App\Models\Payments;
//use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\Export;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
//use Spatie\Permission\Models\Permission;

class ReportsController extends Controller
{
    protected $page = 'reports';

    public function __construct(Request $request)
    {
        //$this->model = new User();
        $this->sortableColumns = ['id', 'first_name', 'email', 'mobile', 'status', 'updated_at' ];
        $this->sortableColumns1 = ['id', 'name', 'email', 'comment', 'status', 'updated_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    public function index()
    {
    }

    public function users_report(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $name_search = $request->input('name_search');
            $status_search = $request->input('status_search');
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');

            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $sortableColumns = $this->sortableColumns;

            $start_date = ($request->get('start_date')) ? date('Y-m-d 00:00:01', strtotime($request->get('start_date'))) : date('Y-m-d 00:00:01', strtotime('-1 year', strtotime(date('Y-m-d'))));
            $end_date = ($request->get('end_date')) ? date('Y-m-d 23:59:59', strtotime($request->get('end_date'))) : date('Y-m-d 23:59:59');

            $response = $this->getUserData($name_search,$status_search,$from_date,$to_date, $sortableColumns[$orderby], $order);

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
                $row['user_name'] = ucfirst($value->first_name).' '.ucfirst($value->last_name);
                $row['email'] = $value->email;
                $row['mobile'] = $value->mobile;
                if($value->status==1){ $status='Active'; }else{ $status='In-active';}
                $row['status'] = $status;
                $row['updated_at'] = date('j F Y', strtotime($value->created_at));

               
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
        $data = ['title' => ucfirst('Users Report'), 'page' => $this->page];
        return view('admin.' . $this->page . '.users_report', $data);
    }

    public function getUserData($name_search = null,$status_search = null,$from_date = null,$to_date = null,$orderby = null, $order = null, $request = null)
    {
        $q = User::where('id','!=','1')->where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'created_at';
        $order = $order ? $order : 'desc';

        if ($name_search && !empty($name_search)) {
                $q->whereRaw('CONCAT(first_name, \' \' , last_name) LIKE \'%'.$name_search.'%\' ');
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

    public function users_export(Request $request) 
    {   
        $name_search = $request->name_search;
        $status_search = $request->status_search;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $collection=$this->getUserData($name_search,$status_search,$from_date,$to_date)->selectRaw('id,CONCAT(first_name, \' \', last_name) as name,email,mobile,status,DATE_FORMAT(created_at,\'%d %M %Y \')')->get();
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

    public function reviews_report(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $name_search = $request->input('name_search');
            $status_search = $request->input('status_search');
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');

            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $sortableColumns = $this->sortableColumns1;

            $start_date = ($request->get('start_date')) ? date('Y-m-d 00:00:01', strtotime($request->get('start_date'))) : date('Y-m-d 00:00:01', strtotime('-1 year', strtotime(date('Y-m-d'))));
            $end_date = ($request->get('end_date')) ? date('Y-m-d 23:59:59', strtotime($request->get('end_date'))) : date('Y-m-d 23:59:59');

            $response = $this->getReviewData($name_search,$status_search,$from_date,$to_date, $sortableColumns[$orderby], $order);

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
                $row['name'] = ucfirst($value->name);
                $row['email'] = $value->email;
                $row['comment'] = Str::words(strip_tags($value->comment), 20, '...');
                if($value->status==1){ $status='Active'; }else{ $status='In-active';}
                $row['status'] = $status;
                $row['updated_at'] = date('j F Y', strtotime($value->created_at));

               
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
        $data = ['title' => ucfirst('Reviews Report'), 'page' => $this->page];
        return view('admin.' . $this->page . '.reviews_report', $data);
    }

    public function getReviewData($name_search = null,$status_search = null,$from_date = null,$to_date = null,$orderby = null, $order = null, $request = null)
    {
        $q = Testimonial::where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'created_at';
        $order = $order ? $order : 'desc';

        if ($name_search && !empty($name_search)) {
                $q->where('name','LIKE', '%'.$name_search.'%');
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

    public function reviews_export(Request $request) 
    {   
        $name_search = $request->name_search;
        $status_search = $request->status_search;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $collection=$this->getReviewData($name_search,$status_search,$from_date,$to_date)->selectRaw('id,name,email,comment,status,DATE_FORMAT(created_at,\'%d %M %Y \')')->get();
        foreach ($collection as $data) {
            if($data->status==1){
                $data->status='Active';
            }else{
                $data->status='In-active';
            }
        }

        $headings=["ID", "Name", "Email", "Comment", "Status", "Register Date"];
        return Excel::download(new Export($collection,$headings), 'reviews report.csv');
    }

    public function payment_report(Request $request)
    {
    }

    public function trial_report(Request $request)
    {
    }

}
