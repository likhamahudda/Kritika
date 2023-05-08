<?php

namespace App\Http\Controllers;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Jobs\OTP as JobsOTP;
use App\Models\Otp;
use App\Models\User;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Requests\UserAuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
//use Spatie\Permission\Models\Permission;

class ProductsController extends Controller
{

    public function __construct(Request $request)
    {
        $this->model = new Products();
        $this->sortableColumns = ['id', 'product_name', 'product_sku'];
    }


    public function product_list(Request $request)
    {
        

        if (auth()->guard('user')->user()->id) {
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

                // $user_id = $request->session()->get('user');
                $user_id = auth()->guard('user')->user()->id;
                //print_r($value);dd2();
                $totaldata = $this->getData($search, $sortableColumns[$orderby], $order, $user_id);
                $totaldata = $totaldata->count();
                $response = $this->getData($search, $sortableColumns[$orderby], $order, $user_id);
                $response = $response->offset($start)->limit($limit)->orderBy('id', 'desc')->get();
                if (!$response) {
                    $data = [];
                    $paging = [];
                } else {
                    $data = $response;
                    $paging = $response;
                }

                $datas = [];
                // print_r($data);die;
                $i = 1;
                //print_r($data);dd();
                foreach ($data as $value) {
                    $img = '<div class="img-tb"><img src="' . url("uploads/product_img/" . $value->product_img) . '"class="image-icon-" alt="profile image" ></div>';
                    $row['id'] = $start + $i;
                    $row['name'] = ucfirst($value->name);
                    $row['product_img'] = $img;
                    $row['category_name'] = $value->category_name;
                    if ($value->status == 1) {
                        $status = '<button type="button" class="btn btn-success">Active</button>';
                    }
                    if ($value->status == 0) {
                        $status = '<button type="button" class="btn btn-danger">Inactive</button>';
                    }
                    $row['status'] = $status;

                    //$row['status'] = statusAction($value->status,$value->id,array(),'statusAction',$this->page . '.status');
                    $row['updated_at'] = date('d M Y ', strtotime($value->created_at));

                    $edit = '';

                    //  $edit = editButton('feed.form', ['id' => $value->id]);


                    $view = '';
                    //if(Auth::Business()->can('View Email Template')){
                    //$view = viewButton($this->page . '.view', ['id' => $value->id]);
                    //}
                    $delete = '';
                    //$delete=deleteButton($this->page . '.delete', ['id' => $value->id]);

                    // comment for custom button

                    //$edit = '<a class="model_open"  type="button" url=""> Edit </a>';
                    //$edit='<a href="javascript:" data-toggle="modal" data-target="#deliveryaddressedit"><i class="zmdi zmdi-hc-fw"></i></a>';




                    $edit = '<a href="#" id="editCompany" data-toggle="modal" data-target="#feedmodal" data-id="' . $value->id . '"><i class="fa-regular fa-pen-to-square"></i></a>';

                    //$delete='<a href="" id="" data-toggle="modal" data-target="#delete" data-id="'.$value->id.'">Delete</a>';
                    $delete = '<a href="" class="delete_button" data-id="' . $value->id . '"><i class="fa-regular fa-trash-can""></i></a>';
                    $row['actions'] = '<div class="action-btn">' . $edit . $delete . '</div>';

                    //$row['actions'] = createButton($edit);
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

            $data['category'] = DB::select("SELECT category_name,id from category where is_delete='0' and status='1' ORDER BY category_name ASC ");

            $data['master_products'] = DB::select("SELECT name,id from master_products where is_delete='0' and status='1' ORDER BY name ASC ");



            $data['title'] = ucfirst('Products List');
            // return view('admin.' . $this->page . '.list', $data);

            return view('users/products', $data);
        } else {
            return redirect('');
        }
    }

    public function update($id)
    {
        $Products = Products::find($id);
        // $item_images = DB::table('business_item_images')->select('image', 'id')->where('item_id', $id)->get();

        return response()->json([
            'data' => $Products,
        ]);
    }

    public function delete($id)
    {
        $data = Products::where('id', $id)->first();
        $data->is_delete = '1';
        $data->save();
        return response()->json(['status' => true, 'message' => ' Product has been deleted successfully']);
    }

    public function removeimage($id)
    {
        $data = DB::table('business_item_images')->where('id', $id)->delete();

        return response()->json(['status' => true, 'message' => ' Image has been deleted successfully', 'id' => $id]);
    }

    public function addProduct(Request $request)
    {
        if (auth()->guard('user')->user()->id) {
            if (!empty($request->get('product_name'))) {
                $user_id = auth()->guard('user')->user()->id;
                $data = new Products();
                $data->product_name = $request->get('product_name');
                $data->product_description = $request->get('product_description');
                $data->category_id = $request->get('category_id');
                $data->company_name = $request->get('company_name');
                $data->seller_id = $user_id;
                $data->user_id = $user_id;
                $data->status = 1;
                $data->is_delete = 0;
                $data->created_at = date('Y-m-d');

                if ($request->hasFile('product_img')) {
                    $path = 'uploads/product_img/';
                    $files = $request->file('product_img');
                    $image_path =  uploadImage($files, $path);
                    $data->product_img = $image_path;
                }

                $data->save();
                $itemId = DB::getPdo()->lastInsertId();
                return response()->json(['status' => true, 'message' => ' Product has been added successfully']);
            } else {

                return response()->json(['status' => false, 'message' => 'Something went wrong please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'All fields are required.']);
        }
    }



    public function getData($search = null, $orderby = null, $order = null, $user_id = '', $request = null)
    {

        //$q = Products::where('user_id','=',$user_id)->where('is_delete','=','0');

        $q = Products::select('products.id', 'product_name', 'master_products.name', 'product_img', 'category_name', 'products.status', 'products.created_at')
            ->leftJoin('category', function ($join) {
                $join->on('category.id', '=', 'products.category_id');
            })
            ->leftJoin('master_products', function ($join) {
                $join->on('master_products.id', '=', 'products.product_name');
            })
            ->where('user_id', $user_id)->where('products.is_delete', '=', '0');

        $orderby = $orderby ? $orderby : 'products.created_at';
        $order = $order ? $order : 'desc';

        if ($search && !empty($search)) {
            $q->where(function ($query) use ($search) {
                $query->where('master_products.name', 'LIKE', '%' . $search . '%')
                    ->orwhere('category_name', 'LIKE', '%' . $search . '%');

                //->orwhere('description', $search);
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }



    public function form($id = null)
    {
        $data = Products::where('id', $id)->first();
        //echo "<pre>";
        //print_r($data);dd();
        if (isset($data) && !empty($data)) {
            $result = ['title' => ucwords('Updatedetails'), 'data' => $data];
        } else {
            $result = ['title' => ucwords('Add Details'),  'data' => $data];
        }
        // return view('admin.' . $this->page . '.form', $result);
        return view('frontend/form', $result);
    }

    public function manage(Request $request)
    {
        if (auth()->guard('user')->user()->id) {
            if (!empty($request->get('product_name'))) {
                $id = $request->get('id');
                $data = Products::where('id', $request->id)->first();
                $data->product_name = $request->get('product_name');
                $data->product_description = $request->get('product_description');
                $data->category_id = $request->get('category_id');
                $data->company_name = $request->get('company_name');
                $data->status = $request->get('status');

                if ($request->hasFile('product_img')) {
                    $path = 'uploads/product_img/';
                    $files = $request->file('product_img');
                    $image_path =  uploadImage($files, $path);
                    $data->product_img = $image_path;
                }

                $data->save();
                $itemId = DB::getPdo()->lastInsertId(); 
                return response()->json(['status' => true, 'message' => ' Product has been updated successfully']);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'All fields are required.']);
        }
    }
}
