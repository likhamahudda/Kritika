<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Spatie\Permission\Models\Permission;
use DB;
use Imagick;
use App\Models\Signature;

class EsignExampleController extends Controller
{
    protected $page = 'signexample';

    public function __construct(Request $request)
    {
        $this->model = new User();
        $this->sortableColumns = ['id', 'first_name', 'email', 'mobile', 'status', 'updated_at'];
    }

    public function index(Request $request)
    {
        $user_id = 1;
        $rand = mt_rand(10000000, 99999999);
        $data['unkey'] = $this->generate_random_unique($rand);

        $data['signaure'] = Signature::where('user_id', $user_id)->where('signature_type', '1')->orderBy('id', 'desc')->limit(1)->first();

        $data['initials'] = Signature::where('user_id', $user_id)->where('signature_type', '2')->orderBy('id', 'desc')->limit(1)->first();

        return view('admin.' . $this->page . '.index', $data);
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

        $result = DB::select('SELECT document_name,document_id,SUM(total_pages) as total_pages FROM example_document WHERE dockey = ? GROUP BY dockey', [$document_id]);

        $document_name = (isset($result[0]->document_name) ? $result[0]->document_name : '');
        $document_name_without_extenstion = (isset($result[0]->document_id) ? $result[0]->document_id : '');
        $total_pages = (isset($result[0]->total_pages) ? $result[0]->total_pages : '0');

        $html = '<select name="doclist" id="doclist" class="span12">
        <option selected="selected" value="">Select Document</option>
        <option data-page="' . $total_pages . '" data-name="' . $document_name_without_extenstion . '" data-orname="' . $document_name . '" data-delno="0" selected="selected"  value="' . $document_id . '" data-doc="' . $document_id . '">"' . $document_name . '"</option>
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
            $path = 'uploads/documents_pdf/';
            $file_path =  uploadDocumentsKp($request->upl, $path);
            $rand = mt_rand(10000000, 99999999);
            $data = array('document_name' => $file_path, "dockey" => $rand);
            DB::table('example_document')->insert($data);

            $last_id = DB::getPdo()->lastInsertId();
            if (isset($last_id)) {

                //convert pdf file to images
                $imagick = new Imagick();
                $imagick->setResolution(300, 300);
                $imagick->readImage('uploads/documents_pdf/' . $file_path);
                $NumberOfPages = $imagick->getNumberImages();

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

                $imagick->setImageCompressionQuality(100);

                for ($i = 0; $i < $NumberOfPages; $i++) {
                    $imagick->setIteratorIndex($i);
                    $imagick = $imagick->flattenImages();   // for black background problem
                    $imagick->setImageFormat('jpeg');
                    $imagick->scaleImage(680,880); // width , height  
                    $imagick->writeImage('uploads/documents/getdoc/doc/'.$dockey."-".($i+1+$count).".jpeg");
                }

                $imagick->destroy(); 



                //$imagick->writeImages('uploads/documents/getdoc/doc/'.$data['document_id'].'.png', false);

                DB::update('update example_document set dockey =?,document_id=?,unkey=?,total_pages = ? where id = ?', [$dockey, $dockey, $request->unkey, $NumberOfPages, $last_id]);

                $statusMsg = json_encode(array('dockey' => $dockey, 'msg' => 'Uploaded', 'status' => 'success', 'upload_dockey' => $last_id));
            } else {
                $statusMsg = json_encode(array('title' => ucwords('Error'), 'msg' => 'Something went wrong.'));
            }
            echo $statusMsg;
        }
    }
}
