<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
//use Spatie\Permission\Models\Permission;

class FaqsController extends Controller
{
    protected $page = 'faqs';

    public function __construct(Request $request)
    {
        $this->model = new Faqs();
        $this->sortableColumns = ['id', 'faq', 'description', 'type', 'status', 'updated_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    /* Function for open add or update popup */

    public function form($id = null)
    {
        $data = Faqs::where('id', $id)->first();
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $result = ['title' => ucwords($type .'FAQs' ), 'page' => $this->page, 'data' => $data];
        return view('admin.' . $this->page . '.form', $result);
    }

    /* Function for add or update data */

    public function manage(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $request->validate([
            'faq' => 'required',
            'description' => 'required',
            'type' => 'required',
        ]);
        if(isset($id) && !empty($id)){  // add new data
            $data = Faqs::where('id', $request->id)->first();
        }else{  // update data
            
            $data = new Faqs();

            $data->status = '1';
        }

        $data->faq = $request->get('faq');
        $data->description = $request->get('description');
        $data->type = $request->get('type');       
        if ($data->save()) {
                return response()->json(['status' => true, 'message' => 'FAQs has been '.$type.' succesfully']);
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }

    /* Function for view list of faqs */

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
                $row['faq'] = ucfirst($value->faq);
                $row['description'] = Str::words(strip_tags($value->description), 30, '...');
                if($value->type==1){ $type='Pricing'; }
                elseif($value->type==2){ $type='General'; }
                else{ $type='FAQs Page'; }
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
        $data = ['title' => ucfirst('FAQs Management'), 'page' => $this->page];
        return view('admin.' . $this->page . '.index', $data);
    }

    /* Function for get data */

    public function getData($search = null  ,$orderby = null, $order = null, $request = null)
    {
        $q = Faqs::where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'created_at';
        $order = $order ? $order : 'desc';

        if ($search && !empty($search)) {
            $q->where(function ($query) use ($search) {
                $query->where('faq', 'LIKE', '%' . $search . '%')
                ->orwhere('description', 'LIKE', '%' . $search . '%'); 
                if(like_match('%' . $search . '%','FAQs')){
                    $c='0';
                    $query->orwhere('type', '=', $c);
                }
                if(like_match('%' . $search . '%','Pricing')){
                    $c='1';
                    $query->orwhere('type', '=', $c);
                }               
            });
        } 
        $response = $q->orderBy($orderby, $order);
        return $response;
    }

    /* Function for view faqs deatils */

    public function view($id)
    {
        $data = Faqs::where('id', $id)->first();
        if (isset($data)) {
            $result = ['title' => ucwords('FAQs Details'), 'page' => $this->page, 'data' => $data];
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
        $data = Faqs::where('id', $id)->first();
        $data->status = $request->get('status');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'FAQs has been '.$status.' succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    /* Function for delete data */

    public function delete(Request $request)
    {     
        $id = $request->get('id');
        $data = Faqs::where('id', $id)->first();
        $data->deleted_at = date('Y-m-d');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => 'FAQs has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
}
