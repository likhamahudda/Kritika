<?php

namespace App\Http\Controllers;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Products;
use App\Models\RequestQuotation;
use App\Models\RequestQuotationVendors;
use Illuminate\Http\Request;
use App\Http\Requests\UserAuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
//use Spatie\Permission\Models\Permission;

class BuyerController extends Controller
{

    public function __construct(Request $request)
    {
        $this->model = new Products();
        $this->sortableColumns = ['id', 'product_name', 'product_sku'];
    }


    public function request_quote_filter_list()
    {

        $user_id =   Auth::guard('user')->user()->id;
        $user_type =   Auth::guard('user')->user()->user_type;

        $rfq_data = RequestQuotation::select('request_for_quotation.id', 'master_products.name', 'request_for_quotation.buyer_id', 'products.product_img', 'request_for_quotation.created_at');
        $rfq_data->leftJoin('products', function ($join) {
            $join->on('request_for_quotation.product_id', '=', 'products.id');
        });
        $rfq_data->leftJoin('master_products', function ($join) {
            $join->on('master_products.id', '=', 'products.product_name');
        });

        if(isset($user_type) && $user_type == 1){ //buyer
        $rfq_data->leftJoin('request_quotation_vendors', function ($join) {
            $join->on('request_quotation_vendors.rfq_id', '=', 'request_for_quotation.id');
        });
         }

        if (isset($_POST['product_name'])  && !empty($_POST['product_name'])) {
            $rfq_data->where('master_products.name', 'LIKE', '%' . $_POST['product_name'] . '%');
        }

        if (isset($_POST['fromDate'])  && !empty($_POST['fromDate'])) {
            $newDate = date("Y-m-d", strtotime($_POST['fromDate']));
            $rfq_data->where('request_for_quotation.created_at', '>=', $newDate);
        }
        if (isset($_POST['toDate'])  && !empty($_POST['toDate'])) {
            $newDate = date("Y-m-d", strtotime($_POST['toDate']));
            $rfq_data->where('request_for_quotation.created_at', '<=', $newDate);
        }
        if(isset($user_type) && $user_type == 0){ //buyer

            $rfq_data->where('request_for_quotation.buyer_id', $user_id)->orderBy('request_for_quotation.created_at', 'desc');

        }else{
            $rfq_data->where('request_quotation_vendors.seller_id', $user_id) ->groupBy('rfq_id')->orderBy('request_for_quotation.created_at', 'desc');


        }

        $result =  $rfq_data->get();

        if (count($result) > 0) {

            foreach ($result as $val) {

                if(isset($user_type) && $user_type == 0){ //buyer
                    $url = url("buyer/request-quote-detail/" . $val->id);
                }else{
                    $url = url("seller/seller-quote-detail/" . $val->id);
                }
           ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card-item">


                        <a href="<?php echo $url; ?>">
                            <div class="card-img">
                                <img src="<?php echo url("uploads/product_img/" . $val->product_img); ?>" alt="">
                            </div>
                            <div class="card-text">
                                <h3><?php echo $val->name;  ?></h3>
                                <span>Create Date: <?php echo  date("d-m-Y", strtotime($val->created_at));  ?> </span>
                            </div>
                        </a>

                    </div>
                </div>
          <?php }
        }else{ 
            ?>
            <div class="col-md-12 col-sm-12">
            <div class="alert alert-warning" role="alert" style="text-align:center">
            No matching records found!
            </div>
            </div>
          <?php

        }
    }


    public function create_rfq()
    {

        $user = Auth::guard('user')->user();
        if ($user->user_type == 0) {

            $data['products'] = DB::select("SELECT name,products.id,master_products.id as master_pro_id from master_products RIGHT join products on products.product_name=master_products.id  where master_products.is_delete='0' and master_products.status='1' GROUP BY master_products.id ORDER BY name ASC ");

            $data['title'] = 'Add RFQ';
            return view('users.buyer.create_rfq', $data);
        } else {
        }
    }

    public function get_product_seller($id)
    {

        //   DB::enableQueryLog();
        $seller_data = Products::select('seller_id', 'first_name')->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'products.seller_id');
        })->where('product_name', $id)->where('users.user_type', '1')->orderBy('users.first_name', 'asc')->get();

        //   dd(DB::getQueryLog());
        //  print_r($seller_data);
        $data_html = '<option value="">Select Seller</option>';
        foreach ($seller_data as $val) {

            $data_html .= ' <option value="' . $val->seller_id . '">' . $val->first_name . '</option>';
        }
        echo $data_html;

        // return response()->json(['status' => true, 'message' => ' Product has been deleted successfully']);
    }

    public function send_rfq_seller(Request $request)
    {

        if (Auth::guard('user')->user()->id) {
            $user_id = Auth::guard('user')->user()->id;
            $request->validate([
                'product_id' => 'required',
                'quantity' => 'required',
                'seller_id' => 'required',
                'description' => 'required',
            ]);

            $data = new RequestQuotation();
            $data->buyer_id = $user_id;
            $data->product_id = $request->get('product_id');
            $data->qty = $request->get('quantity');
            $data->buyer_msg = $request->get('description');
            $data->status = 1;
            $data->is_delete = 0;
            $data->created_at = date('Y-m-d h:i:s');

            if ($request->hasFile('document_file')) {
                $path = 'uploads/document_file/';
                $files = $request->file('document_file');
                $image_path =  uploadImage($files, $path);
                $data->document = $image_path;
            }

            $data->save();
            $rfq_id = $data->id; 

            if (count($request->get('seller_id')) > 0) {

                foreach ($request->get('seller_id') as $SellerVal) {
                    $data_seller = new  RequestQuotationVendors();
                    $data_seller->rfq_id = $rfq_id;
                    $data_seller->seller_id = $SellerVal;
                    $data_seller->status = 1;
                    $data_seller->is_read = 0;
                    $data_seller->created_at = date('Y-m-d h:i:s');
                    $data_seller->save();
                }
            }


            return response()->json(['status' => true, 'message' => ' Request for quotation has been sent successfully']);
        } else {
            return response()->json(['status' => false,  'message' => 'User not found.']);
        }
    }


    public function request_quote_detail($id)
    {

        if (isset($id) && !empty($id)) {
            $user = Auth::guard('user')->user();

            $user_id =   Auth::guard('user')->user()->id;

            $rfq_data = RequestQuotation::select('request_for_quotation.id', 'master_products.name', 'request_for_quotation.buyer_id', 'products.product_img', 'request_for_quotation.created_at', 'product_description', 'buyer_msg', 'qty', 'document')
                ->leftJoin('products', function ($join) {
                    $join->on('request_for_quotation.product_id', '=', 'products.id');
                })
                ->leftJoin('master_products', function ($join) {
                    $join->on('master_products.id', '=', 'products.product_name');
                })
                ->where('request_for_quotation.id', $id)->first();


            $rfq_seller = RequestQuotationVendors::select('request_quotation_vendors.id', 'users.first_name', 'rfq_proposal','rfq_id','seller_id','buyer_id')
                ->leftJoin('users', function ($join) {
                    $join->on('request_quotation_vendors.seller_id', '=', 'users.id');
                })
                ->groupBy('seller_id')
                ->where('request_quotation_vendors.rfq_id', $id)->get();


            $data['title'] = 'Detail RFQ';
            $data['rfq_detail'] = $rfq_data;
            $data['rfq_seller'] = $rfq_seller;
            return view('users.buyer.request_quote_detail', $data);
        } else {
            return redirect('/404');
        }
    }

    public function seller_quote_detail($id)
    {

        if (isset($id) && !empty($id)) {
            $user = Auth::guard('user')->user();

            $user_id =   Auth::guard('user')->user()->id;

            $rfq_data = RequestQuotation::select('request_for_quotation.id', 'master_products.name', 'request_for_quotation.buyer_id', 'products.product_img', 'request_for_quotation.created_at', 'product_description', 'buyer_msg', 'qty', 'document')
                ->leftJoin('products', function ($join) {
                    $join->on('request_for_quotation.product_id', '=', 'products.id');
                })
                ->leftJoin('master_products', function ($join) {
                    $join->on('master_products.id', '=', 'products.product_name');
                })
                ->where('request_for_quotation.id', $id)->first();


            $rfq_seller = RequestQuotationVendors::select('seller_quote_file', 'rfq_proposal')
                ->where('request_quotation_vendors.seller_id', $user_id)
                ->where('request_quotation_vendors.rfq_id', $rfq_data->id)
                ->get();


            $data['title'] = 'Detail RFQ';
            $data['rfq_detail'] = $rfq_data;
            $data['rfq_seller'] = $rfq_seller;

            return view('users.buyer.seller_quote_response', $data);
        } else {
            return redirect('/404');
        }
    }



    public function quote_seller($id)
    {
        $Products = RequestQuotationVendors::find($id);
        // $item_images = DB::table('business_item_images')->select('image', 'id')->where('item_id', $id)->get();

        return response()->json([
            'data' => $Products,
        ]);
    }

    public function seller_quote_send(Request $request)
    {
        if (auth()->guard('user')->user()->id) {
            if (!empty($request->get('product_description'))) {
                $user_id = auth()->guard('user')->user()->id;

                $count = RequestQuotationVendors::whereNull('rfq_proposal')->where('rfq_id', '=', $request->get('rfq_id'))->where('seller_id', '=', $user_id)->count();

                if ($count == 1) {

                    $data = RequestQuotationVendors::where('rfq_id', $request->get('rfq_id'))->where('seller_id', '=', $user_id)->first();
                } else {
                    $data = new RequestQuotationVendors();
                }

                $data->rfq_proposal = $request->get('product_description');
                $data->is_read = 0;
                $data->seller_id = $user_id;
                $data->rfq_id = $request->get('rfq_id');
                $data->created_at = date('Y-m-d h:i:s');

                if ($request->hasFile('seller_quote_file')) {
                    $path = 'uploads/seller_response_file/';
                    $files = $request->file('seller_quote_file');
                    $image_path =  uploadImage($files, $path);
                    $data->seller_quote_file = $image_path;
                }
                $data->save();



                $itemId = DB::getPdo()->lastInsertId();
                return response()->json(['status' => true, 'message' => ' Quote has been sent successfully']);
            } else {

                return response()->json(['status' => false, 'message' => 'Something went wrong please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'All fields are required.']);
        }
    }


    public function get_message_chat()
    {

        $user_id =   Auth::guard('user')->user()->id;
        $user_type =   Auth::guard('user')->user()->user_type;

        $rfq_data = RequestQuotation::select('request_for_quotation.id', 'master_products.name', 'request_for_quotation.buyer_id', 'products.product_img', 'request_for_quotation.created_at');
        $rfq_data->leftJoin('products', function ($join) {
            $join->on('request_for_quotation.product_id', '=', 'products.id');
        });
        $rfq_data->leftJoin('master_products', function ($join) {
            $join->on('master_products.id', '=', 'products.product_name');
        });

        if(isset($user_type) && $user_type == 1){ //buyer
        $rfq_data->leftJoin('request_quotation_vendors', function ($join) {
            $join->on('request_quotation_vendors.rfq_id', '=', 'request_for_quotation.id');
        });
         }

        if (isset($_POST['product_name'])  && !empty($_POST['product_name'])) {
            $rfq_data->where('master_products.name', 'LIKE', '%' . $_POST['product_name'] . '%');
        }

        if (isset($_POST['fromDate'])  && !empty($_POST['fromDate'])) {
            $newDate = date("Y-m-d", strtotime($_POST['fromDate']));
            $rfq_data->where('request_for_quotation.created_at', '>=', $newDate);
        }
        if (isset($_POST['toDate'])  && !empty($_POST['toDate'])) {
            $newDate = date("Y-m-d", strtotime($_POST['toDate']));
            $rfq_data->where('request_for_quotation.created_at', '<=', $newDate);
        }
        if(isset($user_type) && $user_type == 0){ //buyer

            $rfq_data->where('request_for_quotation.buyer_id', $user_id)->orderBy('request_for_quotation.created_at', 'desc');

        }else{
            $rfq_data->where('request_quotation_vendors.seller_id', $user_id) ->groupBy('rfq_id')->orderBy('request_for_quotation.created_at', 'desc');


        }

        $result =  $rfq_data->get();
        $chat_html ='';

        if (count($result) > 0) {

            foreach ($result as $val) {

                if(isset($user_type) && $user_type == 0){ //buyer
                    $url = url("buyer/request-quote-detail/" . $val->id);
                }else{
                    $url = url("seller/seller-quote-detail/" . $val->id);
                }
           
                 $chat_html = '<div class="chat-message-right pb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:33 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
									<div class="font-weight-bold mb-1">You</div>
									Lorem ipsum dolor sit amet, vis erat denique in, dicunt prodesset te vix.
								</div>
							</div>

							<div class="chat-message-left pb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:34 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
									<div class="font-weight-bold mb-1">Sharon Lessman</div>
									Sit meis deleniti eu, pri vidit meliore docendi ut, an eum erat animal commodo.
								</div>
							</div>';

            }
        } else { 
            
            $chat_html = '<div class="col-md-12 col-sm-12">
            <div class="alert alert-warning" role="alert" style="text-align:center">
            No matching records found!
            </div>
            </div>';
          

        }

        $result = array('success' => "1", 'user_name' =>'likhaa', 'user_id' => 1, 'chat_html' => $chat_html);    
      
        $result = array_map('utf8_encode', $result);  
        
        return response()->json($result);

}
}



