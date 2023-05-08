<?php

namespace App\Http\Controllers\Web;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Spatie\Permission\Models\Permission;
use DB;
use Imagick;
use ImagickDraw;
use ImagickPixel;
use App\Models\Signature;
use App\Models\PreparedDocument;
use App\Models\DocumentRoles;
use App\Models\DocumentExample;
use App\Models\SignRequestEmail;
use App\Models\UserContacts;
use PDF;

class AssignmentController extends Controller
{
    protected $page = 'signexample';

    public function __construct(Request $request)
    {
        $this->model = new User();
        $this->sortableColumns = ['id', 'first_name', 'email', 'mobile', 'status', 'updated_at'];
    }

    public function index(Request $request)
    {
        $user_id = Auth::guard('user')->user()->id;
        $rand = mt_rand(10000000, 99999999);
        $data['unkey'] = $this->generate_random_unique($rand);

        //$data['signaure'] = Signature::where('user_id', $user_id)->where('signature_type', '1')->orderBy('id', 'desc')->limit(1)->first();

        //$data['initials'] = Signature::where('user_id', $user_id)->where('signature_type', '2')->orderBy('id', 'desc')->limit(1)->first();

        return view('users.' . $this->page . '.index', $data);
    }

    public function generate_random_unique($random_number)
    {
        $result = DB::select('SELECT * FROM example_document WHERE unkey = "' . $random_number . '"');
        if (isset($result[0]) && count($result[0]) > 0) {
            $rand = mt_rand(10000000, 99999999);
            $this->generate_random_unique($rand);
        } else {
            return $random_number;
        }
    }

    public function getUpdatedDocList(Request $request)
    {
        $document_id =  $request->get('docKey');

        $result = DB::select('SELECT document_name,document_id,SUM(total_pages) as total_pages,GROUP_CONCAT(deleted_pages_range) as deleted_pages_range FROM example_document WHERE dockey = ? GROUP BY dockey', [$document_id]);

        $document_name = (isset($result[0]->document_name) ? $result[0]->document_name : '');
        $document_name_without_extenstion = (isset($result[0]->document_id) ? $result[0]->document_id : '');
        $total_pages = (isset($result[0]->total_pages) ? $result[0]->total_pages : '0');
        $deleted_pages = (isset($result[0]->deleted_pages_range) && !empty($result[0]->deleted_pages_range) ? $result[0]->deleted_pages_range : '0');

        $html = '<select name="doclist" id="doclist" class="span12">
        <option selected="selected" value="">Select Document</option>
        <option data-page="' . $total_pages . '" data-name="' . $document_name_without_extenstion . '" data-orname="' . $document_name . '" data-delno="' . $deleted_pages . '" selected="selected"  value="' . $document_id . '" data-doc="' . $document_id . '">"' . $document_name . '"</option>
        </select>';

        $statusMsg = json_encode(array('error' => 0, 'error_mess' => '', 'html' => $html, 'success' => '1', 'success_mess' => ''));

        echo $statusMsg;
    }

    public function convertBaseDoc(Request $request)
    {
        $document_id = $request->get('doc_key');
        if (isset($document_id) && !empty($document_id)) {
            echo json_encode(array("msg" => "doc converted", "success" => 1));
            /*
            // get document name
            $result = DB::select('SELECT * FROM example_document WHERE id = "'.$document_id.'"');
            $document_name = (isset($result[0]->document_name) ? $result[0]->document_name : '');
            
            if(!empty($document_name)){

                //convert pdf file to images
                $imagick = new Imagick();
                $imagick->readImage('uploads/documents_pdf/'.$document_name);
                $imagick->writeImages('uploads/documents/getdoc/doc/'.$result[0]->document_id.'.png', false);
            }
            echo json_encode(array("msg" => "doc converted", "success" => 1));*/
        }
    }

    public function upl(Request $request)
    {
        if (isset($request->upl)) {
            $extension = $request->file('upl')->getClientOriginalExtension();
            if ($extension == 'pdf') {
                $path = 'uploads/documents_pdf/';
                $file_path =  uploadDocumentsKp($request->upl, $path);
                $rand = mt_rand(10000000, 99999999);
                $data = array('document_name' => $file_path, "dockey" => $rand);
                DB::table('example_document')->insert($data);

                $last_id = DB::getPdo()->lastInsertId();
                if (isset($last_id)) {

                    //convert pdf file to images
                    $imagick_first = new Imagick();
                    $imagick_first->readImage('uploads/documents_pdf/' . $file_path);
                    $NumberOfPages = $imagick_first->getNumberImages();

                    $result = DB::select('SELECT * FROM example_document WHERE unkey = "' . $request->unkey . '"');
                    if (isset($result) && count($result) > 0) {
                        $dockey = $result[0]->dockey;
                        $count = 0;
                        foreach ($result as $pages) {
                            $count +=  $pages->total_pages;
                        }
                    } else {
                        $dockey = $rand;
                        $count = 0;
                    }

                    /* for ($x = 1; $x <= $NumberOfPages; $x++) {
                    $imagick->setImageResolution(150,150);
                    $imagick->readImage('uploads/documents_pdf/'.$file_path. "[" . ($x-1) . "]");
                    $imagick = $imagick->flattenImages();   // for black background problem
                    $imagick->setImageCompression(imagick::COMPRESSION_JPEG); 
                    $imagick->setImageCompressionQuality(150);
                    $imagick->setImageFormat("jpeg");
                    $imagick->scaleImage(680,880); // width , height  
                    $imagick->writeImage('uploads/documents/getdoc/doc/'.$dockey."-".($x+$count).".jpeg");
                } */

                    //$imagick->setImageCompressionQuality(100);

                    for ($i = 1; $i <= $NumberOfPages; $i++) {
                        $imagick = new Imagick();
                        $imagick->setResolution(300, 300);
                        $imagick->readImage('uploads/documents_pdf/' . $file_path . "[" . ($i - 1) . "]");
                        //$imagick->setIteratorIndex($i);
                        $imagick = $imagick->flattenImages();   // for black background problem
                        $imagick->setImageFormat('jpeg');
                        $imagick->scaleImage(680, 880); // width , height  
                        $imagick->writeImage('uploads/documents/getdoc/doc/' . $dockey . "-" . ($i + $count) . ".jpeg");
                    }

                    $imagick->destroy();



                    //$imagick->writeImages('uploads/documents/getdoc/doc/'.$data['document_id'].'.png', false);

                    DB::update('update example_document set dockey =?,document_id=?,unkey=?,total_pages = ? where id = ?', [$dockey, $dockey, $request->unkey, $NumberOfPages, $last_id]);

                    $statusMsg = json_encode(array('dockey' => $dockey, 'msg' => 'Uploaded', 'status' => 'success', 'upload_dockey' => $last_id));
                } else {
                    $statusMsg = json_encode(array('title' => ucwords('Error'), 'msg' => 'Something went wrong.'));
                }
            }else{
                $statusMsg = json_encode(array('error_details' => "Invalid File Format.","has_error"=>true, "msg" => "Invalid File Format.", "status" =>"error"));
            }

            echo $statusMsg;
        }
    }
    // code by mitali //

    public function save_display($type)
    {
        $user_id = Auth::guard('user')->user()->id;
        $data = User::where('id', $user_id)->first();
        if ($type == '0') {
            $data->document_list_view = '0';
        } elseif ($type == '1') {
            $data->document_list_view = '1';
        } else {
            return redirect('assignments/list');
        }
        $data->update();
        Auth::guard('user')->user()->document_list_view = $type;
        return back();
    }

    public function list(Request $request)
    {
        //phpinfo(); die;
        $data['document_list_view'] = Auth::guard('user')->user()->document_list_view;
        $user_id = Auth::guard('user')->user()->id;
        $folder_id = $request->query('fid');
        $data['user_id'] = $user_id;

        // get all documents of this user
        $query = PreparedDocument::leftJoin('move_documents_folder', 'move_documents_folder.prepared_doc_id', '=', 'prepared_documents.id')
            ->where('prepared_documents.user_id', '=', $user_id)
            ->whereNull('move_documents_folder.deleted_at');
        // If post request receive then filter will be apply
        if (isset($folder_id) && !empty($folder_id)) {
            $query->where('move_documents_folder.folder_id', $folder_id);
        }
        if (isset($request->title) && !empty($request->title)) {
            $query->where('assign_desc', 'like', '%' . $request->title . '%');
        }

        $data['prepared_docs'] =  $query->orderBY('prepared_documents.id', 'desc')->get();
        $data['search_assign_status'] = $request->search_assign_status;
        return view('users.signature.document_list', $data);
    }

    public function preparedoc(Request $request)
    {
        $statusMsg = json_encode(array("success" => true, 'success_mess' => "CROP_SAVE_SUCCESS", 'error' => false, 'error_mess' => ""));
        echo $statusMsg;
    }

    public function addassignment(Request $request)
    {
        $user = Auth::guard('user')->user(); // will be set session user id
        if (!empty($request->all())) {

            if (empty(getSettingValue('logo'))) {     // get site logo
                $logo = url('images/logo.png');
            } else {
                $logo = url('uploads/logo') . '/' . getSettingValue('logo');
            }

            $data = new PreparedDocument();
            $data->user_id = $user->id;
            $data->document_ids = $request->doclist;
            $data->data_json = $request->docdata;
            $data->hdn_roles_ids = $request->hdn_roles_ids;
            $data->assign_title = $request->assign_title;
            $data->assign_desc = $request->assign_desc;
            $data->assignment_list = $request->assignmentList;
            $data->template_done = $request->templateDone;
            $data->is_chain = $request->is_chain;
            $data->is_ignored = $request->is_ignored;
            $data->is_notify = $request->is_notify;
            if ($data->save()) {
                // Add signatures , initials and checkbox textbox etc
                $response = $this->addsignatureInitialsOnImage($data->document_ids, $data->data_json);

                // Insert Roles Data
                if (isset($request->recep_email_txt) && !empty($request->recep_email_txt)) {
                    foreach ($request->recep_email_txt as $key_i => $emails) {
                        if (isset($emails) && !empty($emails)) {
                            // Convert response to json and store receipent wise in the database
                            $manage_key = $key_i + 1;
                            $response_json = "";
                            if (isset($response[$manage_key]) && !empty($response[$manage_key])) {
                                $response_json = json_encode($response[$manage_key]);
                            }

                            // Update Pending/Complete status according to Login User
                            $peding_complete_status = '0';
                            if ($response_json == "") {   // here 1 means is "later me"
                                $peding_complete_status = '1';
                            }

                            //check email is exist or not in user table ... if email is not exist then create new user with rendom password
                            $check_users_info = User::where('email', $emails)->first();
                            if (isset($check_users_info->id) && !empty($check_users_info->id)) {
                                $receiver_user_id = $check_users_info->id;
                            } else {
                                //Create New User and send mail of credentials
                                $password = rand(100000000, 999999999);
                                $receiver_user = new User();
                                $receiver_user->status = '1';
                                $receiver_user->email_verified_at = date('Y-m-d H:i:s');
                                $receiver_user->verification_status = '1';
                                $receiver_user->email = $emails;
                                $receiver_user->password =  Hash::make($password);
                                if ($receiver_user->save()) {
                                    $receiver_user_id = $receiver_user->id;
                                    $content = getEmailContentValue(2);
                                    if ($content) {
                                        $emailval = $content->description;
                                        $subject = $content->title;
                                        $replace_data = [
                                            '@name0' => ((isset($request->recep_txt[$key_i]) && !empty($request->recep_txt[$key_i])) ? $request->recep_txt[$key_i] : ''),
                                            '@username_0' => $emails,
                                            '@password0' => $password,
                                            '@logo' => $logo,
                                        ];

                                        foreach ($replace_data as $key => $value) {     // set values in email
                                            $emailval = str_replace($key, $value, $emailval);
                                        }
                                        sendMail($emails, $emailval, $subject);
                                    }
                                }
                            }

                            // check Email is exist in contact or not ... if not exist than added in user contacts
                            $check_contact_exist = UserContacts::where('email', $emails)->where('user_id', $user->id)->first();
                            if (isset($check_contact_exist->id) && !empty($check_contact_exist->id)) {
                                $check_contact_exist->name = ucwords(((isset($request->recep_txt[$key_i]) && !empty($request->recep_txt[$key_i])) ? $request->recep_txt[$key_i] : ''));
                                $check_contact_exist->save();
                            } else {
                                $new_contact_info = new UserContacts();
                                $new_contact_info->user_id = $user->id;
                                $new_contact_info->email = $emails;
                                $new_contact_info->name = ucwords(ucwords(((isset($request->recep_txt[$key_i]) && !empty($request->recep_txt[$key_i])) ? $request->recep_txt[$key_i] : '')));
                                $new_contact_info->save();

                                $rendom_id = random_strings(40);
                                $new_contact_info->invitation_url = $new_contact_info->id . $rendom_id;
                                $new_contact_info->save();
                            }

                            $dataroles = new DocumentRoles();
                            $dataroles->prepared_document_ids = $data->id;
                            $dataroles->user_id = $receiver_user_id;
                            $dataroles->role_txt = ((isset($request->role_txt[$key_i]) && !empty($request->role_txt[$key_i])) ? $request->role_txt[$key_i] : '');
                            $dataroles->recep_txt = ((isset($request->recep_txt[$key_i]) && !empty($request->recep_txt[$key_i])) ? $request->recep_txt[$key_i] : '');
                            $dataroles->recep_email_txt = $emails;
                            $dataroles->signature_request_json = $response_json;
                            $dataroles->signature_status = $peding_complete_status;
                            $dataroles->save();

                            // update unique Id
                            $rendom_id = random_strings(40);
                            $dataroles->unique_request_id = $dataroles->id . $rendom_id;
                            $dataroles->save();

                            if (isset($response[$manage_key]) && !empty($response[$manage_key])) {
                                // Send Email to all receivers 
                                if ($manage_key != "1") {  // here 1 means admin youself
                                    $content = getEmailContentValue(7);
                                    if ($content) {
                                        $emailval = $content->description;
                                        $subject = $content->subject . ' From ' . $user->email;
                                        $replace_data = [
                                            '@user_name' => ((isset($request->recep_txt[$key_i]) && !empty($request->recep_txt[$key_i])) ? $request->recep_txt[$key_i] : ''),
                                            '@sender_name' => $user->first_name . " " . $user->last_name,
                                            '@message_content' => $request->assign_desc,
                                            '@link_value' => url('request/signdoc?request=') . $dataroles->unique_request_id,
                                            '@logo' => $logo,
                                        ];

                                        foreach ($replace_data as $key => $value) {
                                            $emailval = str_replace($key, $value, $emailval);
                                        }
                                        if (sendMail($emails, $emailval, $subject)) {
                                            //store emails in email table
                                            $signemail = new SignRequestEmail();
                                            $signemail->sender_id = $user->id;
                                            $signemail->sender_email = $user->email;
                                            $signemail->receiver_id = $receiver_user_id;
                                            $signemail->receiver_email = $emails;
                                            $signemail->read_unread = '0';
                                            $signemail->request_id = $dataroles->unique_request_id;
                                            $signemail->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $statusMsg = json_encode(array("status" => 'success', 'msg' => "Document saved successfully"));
            } else {
                $statusMsg = json_encode(array("status" => 'error', 'error_mess' => 'Something went wrong.'));
            }
        } else {
            $statusMsg = json_encode(array("status" => 'error', 'error_mess' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    /*public function testkp()
    {
        $json = '[{"id":"","div_id":"tool_component_1","position":{"top":54.78125,"left":0},"position2":{"top":54.78125,"left":0},"type_inside":{"type":"cc_sign","val":true,"change":false},"page":0,"width":100,"height":40,"dropdown_value_update":"2","dropdown_value":"2","required":true,"adv_val":"","font_size":"","dynamic_key":""},{"id":"","div_id":"tool_component_2","position":{"top":0,"left":581},"position2":{"top":0,"left":581},"type_inside":{"type":"cc_initials","val":true,"change":false},"page":0,"width":100,"height":40,"dropdown_value_update":"3","dropdown_value":"3","required":true,"adv_val":"","font_size":"","dynamic_key":""},{"id":"","div_id":"tool_component_3","position":{"top":300.78125,"left":581},"position2":{"top":300.78125,"left":581},"type_inside":{"type":"cc_textbox","val":true,"change":false},"page":0,"width":100,"height":25,"dropdown_value_update":"2","dropdown_value":"2","required":true,"adv_val":"","font_size":"","dynamic_key":""},{"id":"","div_id":"tool_component_4","position":{"top":395.78125,"left":0},"position2":{"top":395.78125,"left":0},"type_inside":{"type":"cc_sign","val":true,"change":false},"page":0,"width":100,"height":40,"dropdown_value_update":"1","dropdown_value":"1","required":true,"adv_val":"","font_size":"","dynamic_key":""}]';
        $json_data = json_decode($json, true);
        //echo "<pre>"; print_r($json_data); die;
        $store_data_assign_wise = array();
        foreach ($json_data as $json_val) {
            $dropdown_value = $json_val['dropdown_value'];
            if (!isset($store_data_assign_wise[$dropdown_value])) {
                $store_data_assign_wise[$dropdown_value] = array();
            }
            array_push($store_data_assign_wise[$dropdown_value], $json_val);
        }

        foreach ($store_data_assign_wise as $key => $test) {
            echo json_encode($test) . "<br>";
        }

        echo "<pre>";
        print_r($store_data_assign_wise);
        die;


        $all_emails = array("2" => "abc@gmail.com", "3" => "abcd@gmail.com");
        if (isset($all_emails) && !empty($all_emails)) {
            foreach ($all_emails as $key => $emails) {
                if (isset($emails) && !empty($emails)) {
                    // get data from json according to "dropdown_value"
                }
            }
        }
    } */

    public function addsignatureInitialsOnImage($document_id, $json_data)
    {
        if (!empty($json_data)) {
            // get all documents (with deleted) from document_example table 
            $all_documents = DB::select('SELECT SUM(total_pages) as total_pages,GROUP_CONCAT(deleted_pages_range) as deleted_pages_range FROM example_document WHERE deleted_at IS NOT NULL and dockey = ? ', [$document_id]);
            $total_pages = DocumentExample::where('dockey', $document_id)->sum("total_pages");
            $all_deleted_document_ids = (isset($all_documents[0]->deleted_pages_range) && !empty($all_documents[0]->deleted_pages_range) ? $all_documents[0]->deleted_pages_range : '0');
            $deleted_doc_array = explode(',', $all_deleted_document_ids);

            // Update Total Pages
            $preparedDocument = PreparedDocument::where('document_ids', $document_id)->first();
            $preparedDocument->total_pages = $total_pages;
            $preparedDocument->save();

            // Copy Not Deleted Images into assign_doc folder
            if (!empty($total_pages)) {
                $inc = 1;
                for ($i = 1; $i <= $total_pages; $i++) {
                    if (!in_array($i, $deleted_doc_array)) {
                        $sourcePath = "uploads/documents/getdoc/doc/" . $document_id . "-" . $i . ".jpeg";
                        $newPath = "uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $inc . ".jpeg";
                        $copied = copy($sourcePath, $newPath);
                        $inc++;
                    }
                }
            }

            $data_array = json_decode($json_data, true);
            //echo "<pre>"; print_r($data_array); die;
            $store_data_assign_wise = array();
            foreach ($data_array as $data) {
                // Create Array According Dropdown Value
                $dropdown_value = $data['dropdown_value'];
                if (!isset($store_data_assign_wise[$dropdown_value])) {
                    $store_data_assign_wise[$dropdown_value] = array();
                }
                array_push($store_data_assign_wise[$dropdown_value], $data);


                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_sign') {
                    if ($data['dropdown_value_update'] == 'me_now') {
                        // Open the original image
                        $image = new Imagick();
                        $page_no = $data['page']  + 1;
                        $image->readImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                        $width = $image->getImageWidth();
                        $height = $image->getImageHeight();
                        //echo $width."/".$height; die;
                        // Open the watermark
                        $watermark = new Imagick();
                        $watermark->readImage("uploads/signature/" . $data['type_inside']['val']);
                        $watermark->scaleImage($data['width'], $data['height']);   // width , height

                        // Overlay the watermark on the original image   // COMPOSITE_DISPLACE
                        $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $data['position2']['left'], $data['position2']['top']);

                        $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                    }
                }
                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_initials') {
                    if ($data['dropdown_value_update'] == 'me_now') {
                        // Open the original image
                        $image = new Imagick();
                        $page_no = $data['page']  + 1;
                        $image->readImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                        //$width = $image->getImageWidth();
                        //$height = $image->getImageHeight();
                        //echo $width."/".$height; die;
                        // Open the watermark
                        $watermark = new Imagick();
                        $watermark->readImage("uploads/signature/" . $data['type_inside']['val']);
                        $watermark->scaleImage($data['width'], $data['height']);   // width , height

                        // Overlay the watermark on the original image   // COMPOSITE_DISPLACE
                        $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $data['position2']['left'], $data['position2']['top']);

                        $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                    }
                }
                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_textbox') {
                    if ($data['dropdown_value_update'] == 'me_now') {
                        $draw = new ImagickDraw();
                        $page_no = $data['page']  + 1;
                        $image = new Imagick("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");


                        $draw->setGravity(Imagick::GRAVITY_NORTHWEST);

                        /* Black text */
                        $draw->setFillColor('black');

                        /* Font properties */
                        //$draw->setFont('Bookman-DemiItalic');
                        $draw->setFontSize(14);

                        /* Create text */
                        $text_value = (isset($data['adv_val']) ? $data['adv_val'] : '') . $data['type_inside']['val'];
                        $image->annotateImage(
                            $draw,
                            $data['position2']['left'],
                            $data['position2']['top'],
                            0,
                            $text_value
                        );

                        /* Give image a format */
                        $image->setImageFormat('jpeg');
                        $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                    }
                }
                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_signdate') {
                    if ($data['dropdown_value_update'] == 'me_now') {
                        $draw = new ImagickDraw();
                        $page_no = $data['page']  + 1;
                        $image = new Imagick("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");


                        $draw->setGravity(Imagick::GRAVITY_NORTHWEST);

                        /* Black text */
                        $draw->setFillColor('black');

                        /* Font properties */
                        //$draw->setFont('Bookman-DemiItalic');
                        $draw->setFontSize(14);

                        /* Create text */
                        $image->annotateImage(
                            $draw,
                            $data['position2']['left'],
                            $data['position2']['top'],
                            0,
                            $data['type_inside']['val']
                        );

                        /* Give image a format */
                        $image->setImageFormat('jpeg');
                        $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                    }
                }
                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_checkbox') {
                    if ($data['dropdown_value_update'] == 'me_now') {
                        $draw = new ImagickDraw();
                        $page_no = $data['page']  + 1;
                        $image = new Imagick("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");


                        // Open the watermark
                        $watermark = new Imagick();
                        $watermark->readImage("themes/frontend-theme/img/icons/checkbox_icon.png");
                        $watermark->scaleImage($data['width'], $data['height']);   // width , height

                        // Overlay the watermark on the original image   // COMPOSITE_DISPLACE
                        $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $data['position2']['left'], $data['position2']['top']);

                        /* Give image a format */
                        $image->setImageFormat('jpeg');
                        $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                    }
                }
            }
        }
        return $store_data_assign_wise;
    }

    public function openDownloadModal(Request $request)
    {
        if (!empty($request->all())) {
            // get document count 
            $html = '';
            $assign_key = $request->assign_key;
            $result = DocumentExample::where('dockey', $assign_key)->get();
            if (isset($result) && count($result) > 0) {
                $count = count($result);
                // create html according to count 
                if ($count > 1) {
                    $html .= '<style>
                    n.download-title{
                        font-size:16px;
                        margin:0 0 15px;
                        font-weight:500;
                    }
                    n.download-input-row{
                        margin-bottom:10px;
                        position:relative;
                    }
                    n.download-input-row.last{
                        margin-bottom:30px;
                    }
                    n.download-input-row input{
                        opacity:0;
                        position:absolute;
                        left:0;
                    }
                    n.download-input-row input+label{
                        position:relative;
                        padding-left:25px;
                        color:#333;
                        line-height:20px;
                        font-size:14px;
                    }
                    n.download-input-row input+label:after{
                        content:"";
                        width:15px;
                        height:15px;
                        border:1px solid #f36523;
                        border-radius:100%;
                        position:absolute; left:0; top:2px; 
                    }
                    n.download-input-row input+label:before{
                        content:"";
                        background:#f36523;
                        width:9px;
                        height:9px;
                        border-radius:100%;
                        position:absolute;
                        left:4px; top:6px;
                        display:none;
                    }n.download-input-row input:checked+label:before{
                        display:block;
                    }
                    </style>
                    <h5 class="download-title">Select which files you would like to download</h5>
                            <form action="/assignments/dnAssignment" method="post" id="download_form">
                                <input type="hidden" name="assignid" value="' . $assign_key . '"/>
                                <div class="download-input-row">
                                    <input type="radio" name="dn_type" value="one" id="radio1" checked="checked">
                                    <label for="radio1">Combine all PDFs into one file</label>
                                </div>
                                <div class="download-input-row">
                                    <input type="radio" name="dn_type" value="merge" id="radio2">
                                    <label for="radio2">Documents (' . $count . ' PDFs)</label>
                                </div>
                                <div class="download-input-row last">
                                    <input type="radio" name="dn_type" value="audit_summary" id="radio3">
                                    <label for="radio3">Download audit trail</label>
                                </div>
                                <input type="submit" id="download_assign_doc" class="btn btn-primary" value="Download">
                            </form>';
                } else {
                    $html .= '<style>
                    n.download-title{
                        font-size:16px;
                        margin:0 0 15px;
                        font-weight:500;
                    }
                    n.download-input-row{
                        margin-bottom:10px;
                        position:relative;
                    }n.download-input-row.last{
                        margin-bottom:30px;
                    }n.download-input-row input{
                        opacity:0; position:absolute; left:0;
                    }n.download-input-row input+label{
                        position:relative;
                        padding-left:25px;
                        color:#333;
                        line-height:20px;
                        font-size:14px;
                    }n.download-input-row input+label:after{
                        content:"";
                        width:15px;
                        height:15px;
                        border:1px solid #f36523;
                        border-radius:100%;
                        position:absolute;
                        left:0; top:2px;
                    }
                    n.download-input-row input+label:before{
                        content:"";
                        background:#f36523;
                        width:9px; height:9px;
                        border-radius:100%;
                        position:absolute;
                        left:4px; top:6px;
                        display:none;
                    }n.download-input-row input:checked+label:before{
                        display:block;
                    }
                    </style>
                    <form action="/assignments/dnAssignment" method="post" id="download_form">
                        <label>Click on download button to download your document.</label>
                        <input type="hidden" name="assignid" value="' . $assign_key . '"/>
                        <input type="submit" id="download_assign_doc" class="btn btn-primary" value="Download">
                    </form>';
                }
                $statusMsg = json_encode(array("status" => 'success', 'message' => '', 'html' => $html));
            } else {
                $statusMsg = json_encode(array("status" => 'error', 'error_mess' => 'Document not found.'));
            }
        } else {
            $statusMsg = json_encode(array("status" => 'error', 'error_mess' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    // This function is used for download document pdf
    public function dnAssignment(Request $request)
    {
        if (!empty($request->all())) {
            $assignid = $request->assignid;
            if (isset($assignid) && !empty($assignid)) {

                $dn_type = $request->dn_type;
                if ($dn_type == 'one') {  // download combine pdf
                    // count total number of pages of document
                    $total_pages = DocumentExample::where('dockey', $assignid)->sum("total_pages");

                    $data = ['assignid' => $assignid, 'total_pages' => $total_pages];
                    $pdf = PDF::loadView('users.pdf.document_combine_pdf', $data);
                    //$html = view('users.pdf.document_combine_pdf', $data)->render();
                    //echo $html; die; 
                    $document_name = 'document' . date('YmdHis') . '.pdf';
                    return $pdf->download($document_name);
                } else if ($dn_type == 'merge') {   // when single document is uploaded [zip will be downloaded]
                    // count total number of pages of document
                    $all_documents = DocumentExample::where('dockey', $assignid)->get();
                    $total_pages = DocumentExample::where('dockey', $assignid)->sum("total_pages");

                    $zip_file = 'document' . date('YmdHis') . '.zip'; // Name of our archive to download

                    // Initializing PHP class
                    $zip = new \ZipArchive();
                    $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                    $store_file_path_array = array();
                    if (!empty($all_documents)) {
                        $start_page = '1';
                        $end_page = "0";
                        foreach ($all_documents as $key => $document) {
                            $end_page = $end_page + $document->total_pages;
                            $data = ['assignid' => $assignid, 'start_page' => $start_page, 'end_page' => $end_page];

                            // start create pdf and save in directory
                            $pdf = PDF::loadView('users.pdf.document_single_pdf', $data);
                            $saved_pdf_path = 'uploads/documents_pdf/';
                            $saved_pdf_name = $key . date('YmdHis') . "single.pdf";
                            $full_saved_path = $saved_pdf_path . $saved_pdf_name;
                            $pdf->save($full_saved_path);
                            array_push($store_file_path_array, $full_saved_path);
                            // end create pdf and save in directory

                            $start_page = $start_page + $end_page;

                            // Adding file: second parameter is what will the path inside of the archive
                            // So it will create another folder called "storage/" inside ZIP, and put the file there.
                            $source_file = $full_saved_path;
                            $file_name = $document->document_name;
                            $zip->addFile($source_file, $file_name);
                        }
                    }

                    $zip->close();

                    // unlink newly created pdfs for single download
                    if (!empty($store_file_path_array)) {
                        foreach ($store_file_path_array as $file) {
                            // unlink saved pdf from directory after added in zip
                            unlink($file);
                        }
                    }

                    // We return the file immediately after download
                    return response()->download($zip_file);
                } else {
                    // count total number of pages of document
                    $total_pages = DocumentExample::where('dockey', $assignid)->sum("total_pages");
                    $data = ['assignid' => $assignid, 'total_pages' => $total_pages];
                    $pdf = PDF::loadView('users.pdf.document_combine_pdf', $data);
                    //$html = view('users.pdf.document_combine_pdf', $data)->render();
                    //echo $html; die; 
                    $document_name = 'document' . date('YmdHis') . '.pdf';
                    return $pdf->download($document_name);
                }
            } else {
                $statusMsg = json_encode(array("status" => 'error', 'error_mess' => 'Something went wrong.'));
            }
        } else {
            $statusMsg = json_encode(array("status" => 'error', 'error_mess' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    // This function is used for remove prepared doc from assigment list
    public function rmAssignment(Request $request)
    {
        if (!empty($request->all())) {
            PreparedDocument::where('document_ids', $request->assignid)->delete();
            $statusMsg = json_encode(array("status" => 'success', 'msg' => 'Document deleted successfully.'));
        } else {
            $statusMsg = json_encode(array("status" => 'error', 'msg' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    public function removeFile(Request $request)
    {
        if (!empty($request->all())) {
            //echo $request->doc."-".$request->upload_dockey; die; 
            if (!empty($request->upload_dockey)) {
                $documents = DocumentExample::where("id", $request->upload_dockey)->first();

                $before_page = DocumentExample::where("id", "<", $request->upload_dockey)->where('dockey', $documents->dockey)->sum("total_pages");

                //echo $before_page; die;
                // remove documents form folder
                $deleleted_doc_pages_range = ($before_page + 1) . "," . ($documents->total_pages + $before_page);
                $deleleted_doc_pages = "";
                if (isset($documents->total_pages) && !empty($documents->total_pages)) {
                    for ($i = ($before_page + 1); $i <= ($documents->total_pages + $before_page); $i++) {
                        $deleleted_doc_pages .= $i . ",";
                        // unlink images from documents
                        // unlink('uploads/documents/getdoc/doc/'.$documents->dockey."-".$i.".jpeg");
                    }
                }

                $deleleted_doc_pages = rtrim($deleleted_doc_pages, ",");
                // delete documents
                $documents->deleted_at = date('Y-m-d H:i:s');
                $documents->deleted_pages_range =  $deleleted_doc_pages;
                $documents->save();

                $statusMsg = json_encode(array("status" => 'success', 'msg' => 'Document remove successfully.', 'dockey' => $documents->dockey, 'deldoc' => $deleleted_doc_pages));
            } else {
                $statusMsg = json_encode(array("status" => 'error', 'msg' => 'Document not found.'));
            }
        } else {
            $statusMsg = json_encode(array("status" => 'error', 'msg' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    public function signdoc(Request $request)
    {
        $user = Auth::guard('user')->user();
        $data['user_id'] = $user->id;
        $request_id = $request->query('request');
        $data['request_id'] = $request_id;
        if (isset($request_id) && !empty($request_id)) {
            // Update Receiver View Date 
            DocumentRoles::where('unique_request_id', $request_id)->update(["view_by_receiver_date" => date('Y-m-d H:i:s'), "read_unread" => '1']);

            $data['document_info'] = DocumentRoles::where('unique_request_id', $request_id)->first();
            $prepared_document_info = PreparedDocument::where('id', $data['document_info']->prepared_document_ids)->first();
            $data['document_info']->document_title = $prepared_document_info->assign_title;
            $data['document_info']->document_ids = $prepared_document_info->document_ids;
            if (isset($data['document_info']->signature_request_json) && !empty($data['document_info']->signature_request_json)) {
                // Convert json to new fomat for edit signature
                $json_data = json_decode($data['document_info']->signature_request_json, true);
                $store_string = "";
                $pages_array = array();
                foreach ($json_data as $key => $json) {
                    //echo $json['page']; die;
                    if (!in_array($json['page'], $pages_array)) {
                        array_push($pages_array, $json['page']);
                    }
                    if ($json['type_inside']['type'] == 'cc_sign') {
                        $field_type = "sign";
                    } elseif ($json['type_inside']['type'] == 'cc_initials') {
                        $field_type = "initials";
                    } elseif ($json['type_inside']['type'] == 'cc_textbox') {
                        $field_type = "textbox";
                    } elseif ($json['type_inside']['type'] == 'cc_checkbox') {
                        $field_type = "checkbox";
                    } else {
                        $field_type = "signdate";
                    }
                    $key++;
                    $inner_array = array(
                        "div_id" => $user->id . "_" . $key, // $json['div_id'],   // generate random ID 
                        "position" => array(
                            "top" => $json['position']['top'],
                            "left" => $json['position']['left']
                        ),
                        "extra_options" => array(
                            "height" => $json['height'],
                            "width" => $json['width'],
                            "font_size" => $json['font_size'],
                            "page" => $json['page'],
                            "position2" => array(
                                "top" => $json['position2']['top'],
                                "left" => $json['position2']['left']
                            ),
                            "adv_val" => $json['adv_val']
                        ),
                        "field_type" => $field_type,
                        "field_value" => $json['type_inside']['val'],
                        "field_change" => ($json['type_inside']['change']) ? '1' : '0',
                        "dropdown_value" => $json['dropdown_value'],
                        "field_label" => "",
                        "required" => ($json['required']) ? '1' : '0',
                    );

                    $store_string .= '"' . $key . '":' . json_encode($inner_array) . ",";
                }
                //echo "[{".rtrim($store_string,',')."}]"; die;
                asort($pages_array);
                $data['pages_array'] = $pages_array;
                $data['document_info']->signature_request_json = "[{" . rtrim($store_string, ',') . "}]";
                //$data['document_info']->signature_request_json = json_encode($outer_array,true);
            }
            //echo "<pre>"; print_r($data['document_info']->pages_array); die;
            return view('users.signature.edit_signature', $data);
        } else {
            return redirect()->route('user.login');
        }
    }

    public function updateSigningRequestStatus(Request $request)
    {
        $statusMsg = json_encode(array("status" => 'success', 'msg' => 'Success'));
        echo $statusMsg;
    }

    public function processdoc(Request $request)
    {
        if (!empty($request->all())) {
            $docdata = $request->docdata;
            $request_id = $request->request_id;

            //get documents id from request Id
            $document_roles_info = DocumentRoles::where('unique_request_id', $request_id)->first();
            if ($document_roles_info->signature_status == '1') {
                $statusMsg = json_encode(array("error" => '1', "error_type" => "", 'error_mess' => 'Document already signed.'));
            } else {
                $prepared_document_info = PreparedDocument::where('id', $document_roles_info->prepared_document_ids)->first();
                $document_id = $prepared_document_info->document_ids;

                $this->prepareDocument($document_id, $docdata);

                // Update Signature response
                $document_roles_info->signature_response_json = $docdata;
                $document_roles_info->signature_status = '1';
                $document_roles_info->signed_by_receiver_date = date('Y-m-d H:i:s');
                $document_roles_info->save();


                $statusMsg = json_encode(array("error" => '0', 'error_type' => "", 'error_mess' => '', "output" => "", "success" => "0", "success_mess" => ""));
            }
        } else {
            $statusMsg = json_encode(array("error" => '1', "error_type" => "", 'error_mess' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    // This function is used for edit the signature 
    public function prepareDocument($document_id, $docdata)
    {
        if (!empty($docdata)) {
            $data_array = json_decode($docdata, true);
            //echo "<pre>"; print_r($data_array); die;
            foreach ($data_array as $data) {
                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_sign') {
                    // Open the original image
                    $image = new Imagick();
                    $page_no = $data['page']  + 1;
                    $image->readImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                    $width = $image->getImageWidth();
                    $height = $image->getImageHeight();
                    //echo $width."/".$height; die;

                    // Open the watermark
                    $watermark = new Imagick();
                    $watermark->readImage("uploads/signature/" . $data['type_inside']['val']);
                    $watermark->scaleImage($data['width'], $data['height']);   // width , height

                    // Overlay the watermark on the original image   // COMPOSITE_DISPLACE
                    $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $data['position2']['left'], $data['position2']['top']);

                    $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                }
                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_initials') {
                    // Open the original image
                    $image = new Imagick();
                    $page_no = $data['page']  + 1;
                    $image->readImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                    //$width = $image->getImageWidth();
                    //$height = $image->getImageHeight();
                    //echo $width."/".$height; die;
                    // Open the watermark
                    $watermark = new Imagick();
                    $watermark->readImage("uploads/signature/" . $data['type_inside']['val']);
                    $watermark->scaleImage($data['width'], $data['height']);   // width , height

                    // Overlay the watermark on the original image   // COMPOSITE_DISPLACE
                    $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $data['position2']['left'], $data['position2']['top']);

                    $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                }
                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_textbox') {

                    $draw = new ImagickDraw();
                    $page_no = $data['page']  + 1;
                    $image = new Imagick("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");


                    $draw->setGravity(Imagick::GRAVITY_NORTHWEST);

                    /* Black text */
                    $draw->setFillColor('black');

                    /* Font properties */
                    //$draw->setFont('Bookman-DemiItalic');
                    $draw->setFontSize(14);

                    /* Create text */
                    $text_value = (isset($data['adv_val']) ? $data['adv_val'] : '') . $data['type_inside']['val'];
                    $image->annotateImage(
                        $draw,
                        $data['position2']['left'],
                        $data['position2']['top'],
                        0,
                        $text_value
                    );

                    /* Give image a format */
                    $image->setImageFormat('jpeg');
                    $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                }
                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_signdate') {

                    $draw = new ImagickDraw();
                    $page_no = $data['page']  + 1;
                    $image = new Imagick("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");

                    $draw->setGravity(Imagick::GRAVITY_NORTHWEST);

                    /* Black text */
                    $draw->setFillColor('black');

                    /* Font properties */
                    //$draw->setFont('Bookman-DemiItalic');
                    $draw->setFontSize(14);

                    /* Create text */
                    $image->annotateImage(
                        $draw,
                        $data['position2']['left'],
                        $data['position2']['top'],
                        0,
                        $data['type_inside']['val']
                    );

                    /* Give image a format */
                    $image->setImageFormat('jpeg');
                    $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                }
                if (isset($data['type_inside']['type']) && $data['type_inside']['type'] == 'cc_checkbox') {
                    $draw = new ImagickDraw();
                    $page_no = $data['page']  + 1;
                    $image = new Imagick("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");

                    // Open the watermark
                    $watermark = new Imagick();
                    $watermark->readImage("themes/frontend-theme/img/icons/checkbox_icon.png");
                    $watermark->scaleImage($data['width'], $data['height']);   // width , height

                    // Overlay the watermark on the original image   // COMPOSITE_DISPLACE
                    $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $data['position2']['left'], $data['position2']['top']);

                    /* Give image a format */
                    $image->setImageFormat('jpeg');
                    $image->writeImage("uploads/documents/getdoc/assign_doc/" . $document_id . "-" . $page_no . ".jpeg");
                }
            }
        }
    }



    public function finalizeProcessDoc(Request $request)
    {
        $statusMsg = json_encode(array('error' => '0', 'error_mess' => '', 'success' => '0', 'success_mess' => ''));
        echo $statusMsg;
    }

    public function addAttachmentToEmail(Request $request)
    {
        if (!empty($request->all())) {

            $html = view('users.signature.send_doc_html', $data = ["assign_id" => $request->assignid])->render();
            $statusMsg = json_encode(array("status" => 'success', "html" => $html));
        } else {
            $statusMsg = json_encode(array("error" => 'error', "error_type" => "", 'error_mess' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    public function sendAttachment(Request $request)
    {
        $user = Auth::guard('user')->user();
        if (!empty($request->all())) {
            if (empty(getSettingValue('logo'))) {     // get site logo
                $logo = url('images/logo.png');
            } else {
                $logo = url('uploads/logo') . '/' . getSettingValue('logo');
            }

            // get document from assign id
            $prepared_document_info = PreparedDocument::where('id', $request->assign_key)->first();
            $document_id = $prepared_document_info->document_ids;

            // count total number of pages of document
            $all_documents = DocumentExample::where('dockey', $document_id)->get();
            $total_pages = DocumentExample::where('dockey', $document_id)->sum("total_pages");

            $store_file_path_array = array();
            if (!empty($all_documents)) {
                $start_page = '1';
                $end_page = "0";
                foreach ($all_documents as $key => $document) {
                    $end_page = $end_page + $document->total_pages;
                    $data = ['assignid' => $document_id, 'start_page' => $start_page, 'end_page' => $end_page];

                    // start create pdf and save in directory
                    $pdf = PDF::loadView('users.pdf.document_single_pdf', $data);
                    $saved_pdf_path = 'uploads/send_doc/';
                    $saved_pdf_name = $key . date('YmdHis') . "single.pdf";
                    $full_saved_path = $saved_pdf_path . $saved_pdf_name;
                    $pdf->save($full_saved_path);
                    array_push($store_file_path_array, $full_saved_path);
                    // end create pdf and save in directory

                    $start_page = $start_page + $end_page;
                }
            }


            // if document is exist then send attachment by emai;
            if (!empty($store_file_path_array) && isset($request->recep_email_txt) && !empty(isset($request->recep_email_txt))) {
                foreach ($request->recep_email_txt as $receiver_email) {
                    $content = getEmailContentValue(9);
                    if ($content) {
                        $emailval = $content->description;
                        $subject = $content->subject;
                        $replace_data = [
                            '@sender_name' => $user->first_name . " " . $user->last_name,
                            '@logo' => $logo,
                        ];

                        foreach ($replace_data as $key => $value) {
                            $emailval = str_replace($key, $value, $emailval);
                        }

                        $files = implode(",", $store_file_path_array);
                        sendMail($receiver_email, $emailval, $subject, $files, $files);
                    }
                }
            }

            $statusMsg = json_encode(array("error" => '0', "msg" => 'Attachment sent successfully'));
        } else {
            $statusMsg = json_encode(array("error" => '1', "msg" => 'Something went wrong.'));
        }
        echo $statusMsg;
    }
}
