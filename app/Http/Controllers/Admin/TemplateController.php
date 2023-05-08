<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Imagick;
//use Spatie\Permission\Models\Permission;

class TemplateController extends Controller
{
    protected $page = 'template';

    public function __construct(Request $request)
    {
        $this->model = new Template();
        $this->sortableColumns = ['id', 'title', 'name', 'type', 'price', 'status', 'updated_at'];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    /* Function for open add or update popup */

    public function form($id = null)
    {
        $data = Template::where('id', $id)->first();
        // get template category data  
        $data_ct = TemplateCategory::where('status', '1')->where('deleted_at', '=', null)->get();
        if (empty($id)) {
            $type = 'Add ';
        } else {
            $type = 'Update ';
        }
        $result = ['title' => ucwords($type . 'Template'), 'page' => $this->page, 'data' => $data, 'data_ct' => $data_ct];
        return view('admin.' . $this->page . '.form', $result);
    }

    /* Function for add or update data */

    public function manage(Request $request)
    {
        $id = $request->get('id');
        if (empty($id)) {
            $type = 'Add ';
        } else {
            $type = 'Update ';
        }
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'type' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);
        if (isset($id) && !empty($id)) {  // add template
            if (Template::where('title', '=', $request->get('title'))->where('id', '!=', $request->id)->where('category_id', $request->get('category_id'))->where('deleted_at', '=', null)->count() > 0) {
                return response()->json(['status' => false,  'message' => 'Template title is already exist']);
            }
            $data = Template::where('id', $request->id)->first();
        } else {  // update template
            if (Template::where('title', '=', $request->get('title'))->where('category_id', $request->get('category_id'))->where('deleted_at', '=', null)->count() > 0) {
                return response()->json(['status' => false,  'message' => 'Template title is already exist']);
            }
            $data = new Template();

            $data->status = '1';
        }

        $data->title = $request->get('title');
        $data->category_id = $request->get('category_id');
        $data->type = $request->get('type');
        $data->price = $request->get('price');
        $data->description = $request->get('description');
        if($request->get('type')=='0'){     // set price 0 for type 0 -> Free

            $data->price = '0';
        }

        if (isset($request->file)) {    // upload template pdf file
            $path = 'uploads/template_pdf/';
            if (!empty($data->file)) {
                deleteImage($path . $data->file);
            }
            $image_path =  uploadImage($request->file, $path);
            $data->file = $image_path;

            //$json = ['status' => true, 'src' => url($path) .'/'. $data->file, 'message' => ucfirst($this->page) . ' has been '.$type.' succesfully'];
        }
        // else{
        //     $json = ['status' => true, 'message' => ucfirst($this->page) . ' has been '.$type.' succesfully'];
        // }
        if ($data->save()) {
            $NumberOfPages = 0;
            if (isset($request->file)) {
                
                if (isset($id) && !empty($id)) { 
                    // unlink already added files for this template Id  
                    $dir = "uploads/template_images/";
                    $files = scandir($dir);
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            if (is_file($dir . $file) && strpos($file,$data->id."-template-") !== false) {
                                unlink($dir . $file);
                            }
                        }
                    }
                }
                
                //convert pdf file to images
                $imagick_first = new Imagick();
                //$imagick_first->setResolution(300, 300);
                $imagick_first->readImage('uploads/template_pdf/' . $data->file);
                $NumberOfPages = $imagick_first->getNumberImages();
                //$imagick->setImageCompressionQuality(100);

                for ($i = 1; $i <= $NumberOfPages; $i++) {
                    $imagick = new Imagick();
                    $imagick->setResolution(300, 300);
                    $imagick->readImage('uploads/template_pdf/'.$image_path. "[" . ($i-1) . "]");
                    //$imagick->setIteratorIndex($i);
                    $imagick = $imagick->flattenImages();   // for black background problem
                    $imagick->setImageFormat('jpeg');
                    $imagick->scaleImage(680, 880); // width , height  
                    $imagick->writeImage('uploads/template_images/' . $data->id . "-template-" . ($i) . ".jpeg");
                }
                $imagick->destroy();
            }

            $data->total_pages = $NumberOfPages;  // update page no
            $data->update();

            return response()->json(['status' => true, 'message' => ucfirst($this->page) . ' has been ' . $type . ' succesfully']);
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }

    /* Function for view list of Template */

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
            $response->whereBetween('master_templates.created_at', [$start_date, $end_date]);

            $response = $response->offset($start)->limit($limit)->orderBy('master_templates.id', 'desc')->get();
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
                $row['title'] = ucfirst($value->title);
                $row['name'] = $value->name;
                $price = '-'; // for type 1 show type paid and price - || for type 0 show type free and price 0
                if ($value->type == '1') {
                    $type = 'Paid';
                    $price = Constant::CURRENCY . $value->price;
                } else {
                    $type = 'Free';
                }
                $row['type'] = $type;
                $row['price'] = $price;
                $row['status'] = statusAction($value->status, $value->id, array(), 'statusAction', $this->page . '.status');
                $row['updated_at'] = default_date_format($value->created_at);

                $edit = editButton($this->page . '.form', ['id' => $value->id]);

                $view = viewButton($this->page . '.view', ['id' => $value->id]);

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
        $data = ['title' => ucfirst('Template Management'), 'page' => $this->page];
        return view('admin.' . $this->page . '.index', $data);
    }

    /* Function for get data */

    public function getData($search = null, $orderby = null, $order = null, $request = null)
    {
        //$q = Template::where('id','!=','1')->where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'master_templates.created_at';
        $order = $order ? $order : 'desc';

        $q = DB::table('master_templates')
            ->join('master_template_category', 'master_templates.category_id', '=', 'master_template_category.id')
            ->select('master_templates.*', 'master_template_category.name')
            ->where('master_templates.deleted_at', '=', null);

        if ($search && !empty($search)) {
            $q->where(function ($query) use ($search) {
                $query->where('master_templates.title', 'LIKE', '%' . $search . '%')
                    ->orwhere('master_templates.price', 'LIKE', '%' . $search . '%')
                    ->orwhere('master_template_category.name', 'LIKE', '%' . $search . '%');
                if (like_match('%' . $search . '%', 'Free')) {
                    $c = '0';
                    $query->orwhere('master_templates.type', '=', $c);
                }
                if (like_match('%' . $search . '%', 'Paid')) {
                    $c = '1';
                    $query->orwhere('master_templates.type', '=', $c);
                }
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }

    /* Function for view Template deatils */

    public function view($id)
    {
        //$data = Template::where('id', $id)->first();
        $data = DB::table('master_templates')
            ->join('master_template_category', 'master_templates.category_id', '=', 'master_template_category.id')
            ->select('master_templates.*', 'master_template_category.name')
            ->where('master_templates.deleted_at', '=', null)
            ->where('master_templates.id', $id)->first();
        if (isset($data)) {
            $result = ['title' => ucwords('Template Details'), 'page' => $this->page, 'data' => $data];
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
        if ($request->get('status') == Constant::ACTIVE) {
            $status = 'active';
        } else {
            $status = 'in-active';
        }
        $data = Template::where('id', $id)->first();
        $data->status = $request->get('status');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => ucfirst($this->page) . ' has been ' . $status . ' succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    /* Function for delete data */

    public function delete(Request $request)
    {
        $id = $request->get('id');
        $data = Template::where('id', $id)->first();
        $data->deleted_at = date('Y-m-d');
        if ($data->save()) {
            $path = 'uploads/template_pdf/';
            if (!empty($data->image)) {
                deleteImage($path . $data->image);
            }
            return response()->json(['status' => true, 'message' => ucfirst($this->page) . ' has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
}
