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

class BillingController extends Controller
{
    public function select_fee_system_template(Request $request)
    {
        $user_id = Auth::guard('user')->user()->id;
        $temp_key = $request->temp_key;
        //get template by template Id
        $template = Template::where('id',$temp_key)->first();
        
        $statusMsg = json_encode(array('error'=> 0,"doc_key"=>$template->id,"doc_name"=>$template->file, 'message' => '', 'status' => 'success'));
        echo $statusMsg;
    }

    public function uplCloud(Request $request)
    {
        $user_id = Auth::guard('user')->user()->id;
        
        //get template by template Id
        $template = Template::where('id',$request->link)->first();

        $rand = mt_rand(10000000, 99999999);
        $data = array("dockey" => $rand);
        DB::table('example_document')->insert($data);
        $last_id = DB::getPdo()->lastInsertId();
        if(isset($last_id)){
            $result = DB::select('SELECT * FROM example_document WHERE unkey = "' . $request->unkey . '"');
            if (isset($result) && count($result) > 0) {
                $dockey = $result[0]->dockey;
                $count = 0;
                foreach ($result as $pages) {
                    $count +=  $pages->total_pages;
                }
            }else {
                $dockey = $rand;
                $count = 0;
            }
        }

        // Copy Template PDF to Document PDF
        $destination_pdf_file_name = "kp".date('YmdHis')."doc.pdf";
        $source_pdf_url = "uploads/template_pdf/".$template->file;
        $destination_pdf_url = "uploads/documents_pdf/".$destination_pdf_file_name;
        copy($source_pdf_url , $destination_pdf_url);

        // Copy Template images into document folder
        if(isset($template->total_pages) && !empty($template->total_pages)){
            for($i = 1; $i <= $template->total_pages; $i++){
                // get template image
                $sourcePath = "uploads/template_images/".$template->id."-template-".$i.".jpeg";
                $newPath = "uploads/documents/getdoc/doc/". $dockey . "-" . ($i + $count) . ".jpeg"; 
                $copied = copy($sourcePath , $newPath);
            }
        }

        DB::update('update example_document set dockey =?,document_id=?,unkey=?,total_pages = ?,document_name=? where id = ?', [$dockey, $dockey, $request->unkey, $template->total_pages,$destination_pdf_file_name, $last_id]);
        $statusMsg = json_encode(array('status'=>'success','msg' =>'Uploaded',"dockey"=>$dockey,'last_id'=>$last_id));
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
