<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
//use Spatie\Permission\Models\Permission; 

class CountriesController extends Controller
{
    protected $page = 'countries';

    public function __construct(Request $request)
    {
        $this->model = new Country();
        $this->sortableColumns = ['id', 'name', 'country_code', 'status', 'updated_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    /* Function for open add or update popup */

    public function form($id = null)
    {
        $data = Country::where('id', $id)->first();
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $result = ['title' => ucwords($type .'Country' ), 'page' => $this->page, 'data' => $data];
        return view('admin.' . $this->page . '.form', $result);
    }

    /* Function for add or update data */

    public function manage(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $request->validate([
            'name' => 'required',
            'code'=> 'required',
        ]);

        if(isset($id) && !empty($id)){
            $data_country = Country::where('name', $request->name)->where('id','!=',$id)->whereNull('deleted_at')->count();
            if($data_country >0 ){
                return response()->json(['status' => false,  'message' => 'Country name already exist.']);
            }
            $data = Country::where('id', $request->id)->first();

            saveAdminNotification("Update Country","Country ".$request->get('name')." updated by ");

        }else{
            $data_country = Country::where('name', $request->name)->whereNull('deleted_at')->count();
            if($data_country >0 ){
                return response()->json(['status' => false,  'message' => 'Country name already exist.']);
            }
            $data = new Country();

            $data->status = '1';
            saveAdminNotification("New Country","New Country ".$request->get('name')." added by ");
        }

        $data->name = $request->get('name'); 
        $data->country_code = $request->get('code');     
        if ($data->save()) {
                return response()->json(['status' => true, 'message' => 'Country has been '.$type.' succesfully']);
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }

    /* Function for view list of Country */

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
                $row['name'] = ucfirst($value->name);
                $row['country_code'] = $value->country_code;
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
        $data = ['title' => ucfirst('Countries Management'), 'page' => $this->page];
        return view('admin.' . $this->page . '.index', $data);
    }

    /* Function for get data */

    public function getData($search = null  ,$orderby = null, $order = null, $request = null)
    {
        $q = Country::where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'created_at';
        $order = $order ? $order : 'desc';

        if ($search && !empty($search)) {
            $q->where(function ($query) use ($search) {
                $query->where('country.name', 'LIKE', '%' . $search . '%')
                ->orwhere('country.country_code', 'LIKE', '%' . $search . '%') ;               
                              
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }

    /* Function for view Country deatils */

    public function view($id)
    {
        $data = Country::where('id', $id)->first();
        if (isset($data)) {
            $result = ['title' => ucwords('Country Details'), 'page' => $this->page, 'data' => $data];
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
        $data = Country::where('id', $id)->first();
        $data->status = $request->get('status');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'Country has been '.$status.' succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    /* Function for delete data */
    
    public function delete(Request $request)
    {     
        $id = $request->get('id');
        $data = Country::where('id', $id)->first();
        $data->deleted_at = date('Y-m-d');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'Country has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
}
