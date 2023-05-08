<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\States;
use App\Models\Districts;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
//use Spatie\Permission\Models\Permission;

class DistrictController extends Controller
{
    protected $page = 'districts';

    public function __construct(Request $request)
    {
        $this->model = new Districts();
        $this->sortableColumns = ['districts.id', 'country.name', 'states.name', 'districts.name', 'status', 'districts.updated_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    /* Function for open add or update popup */

    public function form($id = null)
    {
        $data = Districts::select('districts.*')
               ->where('districts.id', $id)
               ->first();
       //// print_r($data);die;
        $country_data=Country::where('status','1')->get();
        $state_data=States::where('status','1')->get();
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $result = ['title' => ucwords($type .'District' ), 'page' => $this->page, 'data' => $data,'country'=>$country_data,'state'=>$state_data];
        return view('admin.' . $this->page . '.form', $result);
    }

    /* Function for add or update data */

    public function manage(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $request->validate([
            'district_name' => 'required',
            'state_id' =>'required',
        ]);
        if(isset($id) && !empty($id)){
            $data_count= Districts::where('name', $request->district_name)->where('id','!=',$id)->whereNull('deleted_at')->count();
            if($data_count >0 ){
                return response()->json(['status' => false,  'message' => 'Districts name already exist.']);
            }

            $data = Districts::where('id', $request->id)->first();
            saveAdminNotification("Update District","District ".$request->get('district_name')." updated by ");
        }else{

            $data_count= Districts::where('name', $request->district_name)->where('id','!=',$id)->whereNull('deleted_at')->count();
            if($data_count >0 ){
                return response()->json(['status' => false,  'message' => 'Districts name already exist.']);
            }

            
            $data = new Districts();
            saveAdminNotification("New District","New District ".$request->get('district_name')." added by ");
            $data->status = '1';
        }

        $data->name = $request->get('district_name'); 
        $data->state_id = $request->get('state_id'); 
        $data->country_id= $request->get('country_id');
         
        if ($data->save()) {
                return response()->json(['status' => true, 'message' => 'District has been '.$type.' succesfully']);
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }

    /* Function for view list of States */

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
            $response->whereBetween('districts.created_at', [$start_date, $end_date]);

            $response = $response->offset($start)->limit($limit)->orderBy('districts.id', 'desc')->get();
            if (!$response) {
                $data = [];
                $paging = [];
            } else {
                $data = $response;
                $paging = $response;
            }
          //  echo "<pre>";
          //  print_r($data);die;

            $datas = [];
            $i = 1;
            foreach ($data as $value) {
                $row['id'] = $start + $i;
                $row['name'] = ucfirst($value->sname);
                $row['country_name'] = $value->cname;
                $row['district_name'] = $value->name;
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
       
        //echo "<pre>";
        //print_r($country_data);die;
        $data = ['title' => ucfirst('Districts Management'), 'page' => $this->page];
        return view('admin.' . $this->page . '.index', $data);
    }

    /* Function for get data */

    public function getData($search = null  ,$orderby = null, $order = null, $request = null)
    {
        $q = Districts::select('districts.*','states.name as sname','country.name as cname')
                    ->where('districts.deleted_at','=',null)
                    ->leftJoin('states', 'states.id', '=', 'districts.state_id')
                    ->leftJoin('country', 'country.id', '=', 'states.country_id');
        $orderby = $orderby ? $orderby : 'districts.created_at';
        $order = $order ? $order : 'desc';

        if ($search && !empty($search)) {
            $q->where(function ($query) use ($search) {
                $query->where('districts.name', 'LIKE', '%' . $search . '%')
                ->orwhere('country.name', 'LIKE', '%' . $search . '%')
                ->orwhere('states.name', 'LIKE', '%' . $search . '%') ;               
                              
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }

    /* Function for view States deatils */

    public function view($id)
    {
        $data = Districts::select('districts.*','states.name as sname','country.name as cname')
                            ->where('districts.id', $id)
                            ->leftJoin('states', 'states.id', '=', 'districts.state_id')
                            ->leftJoin('country', 'country.id', '=', 'states.country_id')
                           ->first();
      
        if (isset($data)) {
            $result = ['title' => ucwords('Districts Details'), 'page' => $this->page, 'data' => $data];
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
        $data = Districts::where('id', $id)->first();
        $data->status = $request->get('status');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'Districts has been '.$status.' succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    /* Function for delete data */
    
    public function delete(Request $request)
    {     
        $id = $request->get('id');
        $data = Districts::where('id', $id)->first();
        $data->deleted_at = date('Y-m-d');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'Districts has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
/* Function For get state */
    public function getStatesByCountry(Request $request)
    { 
        $states = States::where('country_id', $request->country_id)->orderBy('name')->get();
        return response()->json($states);
    }

}
