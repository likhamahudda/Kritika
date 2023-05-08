<?php

namespace App\Http\Controllers\Web;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\TemplateCategory;
use App\Models\Template;

class UserTemplateController extends Controller
{
    public function template_list(Request $request)
    {
        $user_id = Auth::guard('user')->user()->id;
        $data['limit'] = 2;
        $data['page_no'] = 1;
        // get template category
        $data['template_category'] = TemplateCategory::where('status', "1")->get();

        //get all free template
        $data['free_templats'] = Template::where('status', "1")->where('type', "0")->orderBy('id','desc')->limit($data['limit'])->get();
        // free template count
        $data['total_free_template'] = Template::where('status', "1")->where('type', "0")->count();

        //get all paid template
        $data['paid_templats'] = Template::where('status', "1")->where('type', "1")->orderBy('id','desc')->limit($data['limit'])->get();

        // paid template count
        $data['total_paid_template'] = Template::where('status', "1")->where('type', "1")->count();

        $html = view('users.template.list', $data)->render();

        $statusMsg = json_encode(array('html' => $html, 'message' => '', 'status' => 'success'));
        echo $statusMsg;
    }

    public function getSysTemplateDetails(Request $request)
    {
        if (!empty($request->all())) {
            // get document count 
            $html = '';
            $temp_key = $request->temp_key;
            $temp_type = $request->type;
            $result = Template::where('id', $temp_key)->first();
            if (isset($result) && !empty($result)) {
                // create html according to count 
                $html .= '
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <div class="controls">
                                    <span class="details-main">Template Name: </span>&nbsp;&nbsp;<span class="">'.$result->title.'</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <span class="details-main">Template Desc: </span>&nbsp;&nbsp;<span class=""><p>'.$result->description.'</p>
                                    </span>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <span class="details-main" for="search_tags">Total Document Pages :</span>&nbsp;&nbsp;<span class=""><i class="icon-copy"></i>'.$result->total_pages.'</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <span class="details-main">Template Cost: </span>&nbsp;&nbsp;<span class="">$ '.$result->price.'</span>
                                </div>
                            </div>
                        </div>
                    </div>';

                $statusMsg = json_encode(array("error"=> 0,"status" => 'success', 'message' => 'Success', 'html' => $html));
            } else {
                $statusMsg = json_encode(array("status" => 'error', 'error_mess' => 'Document not found.'));
            }
        } else {
            $statusMsg = json_encode(array("status" => 'error', 'error_mess' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    public function get_ajax_pagination_tmp_list(Request $request){
        $page_no = $request->query('page');
        if (isset($request->type) && $request->type != ""){
            $html = '';
            $template_type = $request->type;
            $category_id = $request->cat_id;
            if($template_type == '1'){ // purchased template
                $type = ""; 
            }else if($template_type == '2'){ // free
                $type = "0";
            }else{
                $type = '1';  // Paid
            }
            $data['template_type'] = $template_type; 
            $data['limit'] = '2';
            $data['page_no'] = (isset($page_no) && !empty($page_no) ? $page_no : '1');
            $start = ($data['page_no'] * $data['limit']) - $data['limit'];
            //get template category wise
            if(isset($category_id) && !empty($category_id)){
                $data['templats'] = Template::where('status', "1")->where('category_id',$category_id)->where('type',$type)->orderBy('id','desc')->offset($start)->limit($data['limit'])->get();
                // free template count
                $data['total_free_template'] = Template::where('status', "1")->where('category_id',$category_id)->where('type',$type)->count();

                // paid template count
                $data['total_paid_template'] = Template::where('status', "1")->where('category_id',$category_id)->where('type',$type)->count(); 
            }else{
                $data['templats'] = Template::where('status', "1")->where('type',$type)->orderBy('id','desc')->offset($start)->limit($data['limit'])->get();
                // free template count
                $data['total_free_template'] = Template::where('status', "1")->where('type',$type)->count();

                // paid template count
                $data['total_paid_template'] = Template::where('status', "1")->where('type',$type)->count();
            }
            
            $html = view('users.template.category_wise_list', $data)->render();

            $statusMsg = json_encode(array("error"=> 0,"status" => 'success', 'message' => 'Success', 'html' => $html));
            
        } else {
            $statusMsg = json_encode(array("status" => 'error', 'error_mess' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }
}
