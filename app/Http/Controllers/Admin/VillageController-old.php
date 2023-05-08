<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\States;
use App\Models\Districts;
use App\Models\Country;
use App\Models\Tehsils;
use App\Models\Panchayat;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
//use Spatie\Permission\Models\Permission;

class VillageController extends Controller
{
    protected $page = 'village';

    public function __construct(Request $request)
    {
        $this->model = new States();
        $this->sortableColumns = ['districts.id', 'name', 'status', 'updated_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    /* Function for open add or update popup */

    public function form($id = null)
    {
        $data = Village::select('village.*','panchayat.id as pid','states.id as sid','country.id as cid','districts.id as did','tehsils.id as tid')
                            ->where('village.id', $id)
                            ->leftJoin('panchayat', 'panchayat.id', '=', 'village.panchayat_id')
                            ->leftJoin('tehsils', 'tehsils.id', '=', 'panchayat.tehsil_id')
                            ->leftJoin('districts', 'districts.id', '=', 'tehsils.district_id')
                            ->leftJoin('states', 'states.id', '=', 'districts.state_id')
                            ->leftJoin('country', 'country.id', '=', 'states.country_id')
                            ->first();
                          ///print_r($data);dd();
        $country_data=Country::where('status','1')->get();
        $state_data=States::where('status','1')->get();
        $district_data=Districts::where('status','1')->get();
        $tehsil_data=Tehsils::where('status','1')->get();
        $panchayat_data=Panchayat::where('status','1')->get();
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $result = ['title' => ucwords($type .'Village' ), 'page' => $this->page, 'data' => $data,'country'=>$country_data,'state'=>$state_data,'district'=>$district_data,'tehsil'=>$tehsil_data,'panchayat'=>$panchayat_data];
        return view('admin.' . $this->page . '.form', $result);
    }

    /* Function for add or update data */

    public function manage(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $request->validate([
            'village_name' => 'required',
           
        ]);
        if(isset($id) && !empty($id)){
            $data = Village::where('id', $request->id)->first();
        }else{
            
            $data = new Village();

            $data->status = '1';
        }

        $data->name = $request->get('village_name'); 
        $data->village_history = $request->get('village_history'); 
        
        $data->panchayat_id = $request->get('panchayat_id'); 
         
        if ($data->save()) {
                return response()->json(['status' => true, 'message' => 'Village has been '.$type.' succesfully']);
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
          /// echo "<pre>";
          /// print_r($data);die;

            $datas = [];
            $i = 1;
            foreach ($data as $value) {
                $row['id'] = $start + $i;
                $row['name'] = ucfirst($value->name);
                $row['country_name'] = $value->cname;
                $row['state_name'] = $value->sname;
                $row['district_name'] = $value->dname;
                $row['tehsil_name'] = $value->tname;
                $row['panchayat_name'] = $value->pname;
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
        $data = ['title' => ucfirst('Villages Management'), 'page' => $this->page];
        return view('admin.' . $this->page . '.index', $data);
    }

    /* Function for get data */

    public function getData($search = null  ,$orderby = null, $order = null, $request = null)
    {
        $q = Village::select('village.*','tehsils.name as tname','districts.name as dname','panchayat.name as pname','states.name as sname','country.name as cname')
                    ->where('village.deleted_at','=',null)
                    ->leftJoin('panchayat', 'panchayat.id', '=', 'village.panchayat_id')
                    ->leftJoin('districts', 'districts.state_id', '=', 'districts.id')
                    ->leftJoin('tehsils', 'tehsils.id', '=', 'panchayat.tehsil_id')
                    ->leftJoin('states', 'states.id', '=', 'districts.state_id')
                    ->leftJoin('country', 'country.id', '=', 'states.country_id');
        $orderby = $orderby ? $orderby : 'districts.created_at';
        $order = $order ? $order : 'desc';

        if ($search && !empty($search)) {
            $q->where(function ($query) use ($search) {
                $query->where('village.name', 'LIKE', '%' . $search . '%')
                ->orwhere('panchayat.name', 'LIKE', '%' . $search . '%')
                ->orwhere('districts.name', 'LIKE', '%' . $search . '%')
                ->orwhere('states.name', 'LIKE', '%' . $search . '%')
                ->orwhere('country.name', 'LIKE', '%' . $search . '%')
                ->orwhere('tehsils.name', 'LIKE', '%' . $search . '%');
               //// ->orwhere('States_code', 'LIKE', '%' . $search . '%') ;               
                              
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }

    /* Function for view States deatils */

    public function view($id)
    {
        $data = Village::where('id', $id)->first();
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
        $data = Village::where('id', $id)->first();
        $data->status = $request->get('status');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'Village has been '.$status.' succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    /* Function for delete data */
    
    public function delete(Request $request)
    {     
        $id = $request->get('id');
        $data = Village::where('id', $id)->first();
        $data->deleted_at = date('Y-m-d');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'Village has been deleted succesfully']);
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
/* Function For get Districts */
    public function getDistrictsByState(Request $request)
    { 
        $districts = Districts::where('state_id', $request->state_id)->orderBy('name')->get();
        return response()->json($districts);
    }

/* Function For get Districts */
    public function gettehsilByDistricts(Request $request)
    { 
        $tehsils = Tehsils::where('district_id', $request->district_id)->orderBy('name')->get();
        return response()->json($tehsils);
    }

/* Function For get Districts */
public function getpanchayatByTehsil(Request $request)
{ 
    $panchayat = Panchayat::where('tehsil_id', $request->tehsil_id)->orderBy('name')->get();
    return response()->json($panchayat);
}



}
