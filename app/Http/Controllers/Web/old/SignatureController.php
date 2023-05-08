<?php

namespace App\Http\Controllers\Web;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Signature;
use App\Models\PreparedDocument;
use App\Models\DocumentRoles;

use Imagick;
use ImagickDraw;
use ImagickPixel;

class SignatureController extends Controller
{
    public function index($sign_initia = "sign_initial", $type = "1", $set_load = "set_load", $verson = "4")
    {
        $user_id = Auth::guard('user')->user()->id;
        $data['user_id'] = $user_id;
        $data['signatures'] = Signature::where('user_id', $user_id)->where('signature_type', $type)->orderBy('id', 'desc')->get();
        $data['version'] = $verson;
        $data['type_in_signatures'] = Signature::where('user_id', $user_id)->where('draw_signature_type', '1')->where('signature_type', $type)->orderBy('id', 'desc')->first();

        $data['title'] = 'Signature';
        $data['type'] = $type;
        return view('users.signature.index', $data);
    }

    public function sign_initial($type = "1", $set_load = "set_load", $verson = "4")
    {
        $result = ['title' => 'Signature'];
        return view('users.signature.index', $result);
    }

    public function savecanvassign(Request $request)
    {
        if (isset($request->user_key) && !empty($request->user_key)) {
            if (isset($request->canvasImage) && !empty($request->canvasImage)) {
                $sing_image = $this->upload_base64Image($request->canvasImage);
                $data = new Signature();
                $data->user_id = $request->user_key;
                $data->signature_type = $request->sign_initial;
                $data->draw_signature_type = '0';
                $data->signature_image = $sing_image;
                $data->signature_base64 = $request->canvasImage;
                if ($data->save()) {
                    return response()->json(['success' => '1', 'message' => 'Signature added succesfully']);
                } else {
                    return response()->json(['success' => '0',  'message' => 'Something went wrong']);
                }
            }
        } else {
            return response()->json(['success' => '0',  'message' => 'User not exist.']);
        }
    }

    public function addtextsign(Request $request)
    {
        //echo $request->canvasImage; die;
        if (isset($request->user_key) && !empty($request->user_key)) {
            if (isset($request->txtSignName) && !empty($request->txtSignName)) {

                /* Create image from text start */
                $image = new Imagick();
                $draw = new ImagickDraw();
                $pixel = new ImagickPixel('white');

                /* New image */
                $image->newImage($request->width, $request->height, $pixel);

                /* Black text */
                $draw->setFillColor('black');
                $font_style = $request->selFont;
                if ($request->selFont == "HoneyScript-SemiBold") {
                    $font_style = "honeyscript-light";
                }
                /* Font properties */
                $draw->setFont("esign_example/frontend-theme/font/signature/" . $font_style . ".woff");
                $draw->setFontSize(preg_replace("/[^0-9]/", '', $request->font_size));

                /* Create text */
                $image->annotateImage($draw, 10, 80, 0, $request->txtSignName);

                /* Give image a format */
                $image->setImageFormat('png');
                $img_name = 'sing' . date('YmdHis') . 'kp.png';
                $image->writeImage('./uploads/signature/' . $img_name);

                /* Create image from text start */

                //$sing_image = $this->upload_base64Image($request->canvasImage);
                $data = new Signature();
                $data->user_id = $request->user_key;
                $data->signature_type = $request->sign_initial;
                $data->draw_signature_type = '1';
                $data->signature_image = $img_name;
                //$data->signature_base64 = $request->canvasImage;
                $data->sign_name = $request->txtSignName;
                $data->font = $request->selFont;
                $data->font_family = $request->font_family;
                $data->width = $request->width;
                $data->height = $request->height;
                $data->font_size = $request->font_size;

                if ($data->save()) {
                    return response()->json(['success' => '1', 'message' => 'Signature added successfully']);
                } else {
                    return response()->json(['success' => '0',  'message' => 'Something went wrong']);
                }
            }
        } else {
            return response()->json(['success' => '0',  'message' => 'User not exist.']);
        }
    }

    public function delsignature(Request $request)
    {
        if (isset($request->sign_key) && !empty($request->sign_key) && isset($request->user_key) && !empty($request->user_key)) {
            $data = Signature::where('id', $request->sign_key)->where('user_id', $request->user_key)->first();
            $data->deleted_at = date('Y-m-d H:i:s');
            if ($data->save()) {
                return response()->json(['success' => '1', 'message' => 'Signature deleted successfully']);
            } else {
                return response()->json(['success' => '0',  'message' => 'Something went wrong']);
            }
        } else {
            return response()->json(['success' => '0',  'message' => 'Something went wrong']);
        }
    }

    public function upload_base64Image($base64Image)
    {
        $img_name = "";
        $image_parts = explode(";base64,", $base64Image);
        //echo "<pre>"; print_r($image_parts); 
        if (isset($image_parts[0]) && isset($image_parts[1])) {
            $check_image = strpos($image_parts[0], "image/");

            if (isset($check_image) && !empty($check_image) && $check_image >= 0) {  // image
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                //echo "base_encode : ".$image_base64;
                $img_name = 'sing' . date('YmdHis') . 'kp.png';
            }

            $success = file_put_contents('./uploads/signature/' . $img_name, $image_base64);
            //print $success ? './uploads/bug_files/' . $img_name : 'Unable to save the file.';

            return $img_name;
        }
    }

    public function selectsignature()
    {   // get last Signature
        $user_id = Auth::guard('user')->user()->id;
        $signaure = Signature::where('user_id', $user_id)->where('signature_type', '1')->orderBy('id', 'desc')->limit(1)->first();
        $html = "";
        if (isset($signaure->signature_image) && !empty($signaure->signature_image)) {
            $html = '<div id="edit_user_sign" class="edit_sign_cont text-center"><a href="#" id="edit_sign_link" class="edit-icon" title="Edit Signature"><i class="icon-edit-sign"></i></a><img class="edit_this_img" src="'.url('/').'/uploads/signature/' . $signaure->signature_image . '"/></div>';
        }
        return response()->json(['error' => "0", "success" => '1', "error_mess" => "", 'success_mess' => 'SIGN_SUCCESS', "file_data" => "", "html" => $html]);
    }

    public function SelectInitial()
    { // get last Initial
        $user_id = Auth::guard('user')->user()->id;

        $initials = Signature::where('user_id', $user_id)->where('signature_type', '2')->orderBy('id', 'desc')->limit(1)->first();
        $html = "";
        if (isset($initials->signature_image) && !empty($initials->signature_image)) {
            $html = '<div id="edit_user_initial" class="edit_sign_cont text-center"><a href="#" id="edit_initial_link" class="edit-icon" title="Edit Initial"><i class="icon-edit-sign"></i></a><img class="edit_this_img" src="'.url('/').'/uploads/signature/' . $initials->signature_image . '"/></div>';
        }

        return response()->json(['error' => "0", "success" => '1', "error_mess" => "", 'success_mess' => 'SIGN_SUCCESS', "file_data" => "", "html" => $html]);
    }

    public function uploadsignimage(Request $request, $sign_initial = "", $sign_initial_type = "", $user_key = 0)
    {
        //echo 'kp'.$sign_initial; die;
        if (isset($request->sign_up_file) && !empty($user_key)) {
            $path = 'uploads/signature/';
            $fileSize = $request->file('sign_up_file')->getSize();
            $size = number_format($fileSize / 1024,2);
            if($size > 1024 || $size == 0 || $size == 0.00){
                $statusMsg = json_encode(array('error' => true, 'error_mess' => 'Invalid file size.'));
            }else if($size < 20){
                $statusMsg = json_encode(array('error' => true, 'error_mess' => 'File size is less than 20 KB.'));
            }else{
                $file_path =  uploadSignKp($request->sign_up_file, $path);
                $data = new Signature();
                $data->user_id = $user_key;
                $data->signature_type = $sign_initial_type;
                $data->draw_signature_type = '2';
                $data->signature_image = $file_path;
    
                if ($data->save()) {
                    $image = new Imagick("uploads/signature/" . $file_path);
                    $width = $image->getImageWidth();
                    $height = $image->getImageHeight();
                    $statusMsg = json_encode(array('error' => false, 'id' => $data->id, 'file_data' => array("sign_key" => $data->signature_image, "sign_height" => $height, "sign_width" => $width)));
                } else {
                    $statusMsg = json_encode(array('error' => true, 'error_mess' => 'Something went wrong.'));
                }
            }           
        } else {
            $statusMsg = json_encode(array('error' => true, 'error_mess' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    public function rotatesignature(Request $request)
    {
        if (isset($request->sign_key) && !empty($request->sign_key) && isset($request->user_key) && !empty($request->user_key) && isset($request->rotate) && !empty($request->rotate)) {
            /* Image Rotate functionality start */
            $imagick = new Imagick("uploads/signature/" . $request->sign_key);
            $imagick->rotateImage(new ImagickPixel(), $request->rotate);
            $imagick->writeImage('./uploads/signature/' . $request->sign_key);
            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();
            $imagick->clear();
            $imagick->destroy();
            /* Image Rotate functionality end */

            $statusMsg = json_encode(array("success" => true, 'success_mess' => "ROTATION_SUCCESS", 'error' => false, 'error_mess' => "", 'file_data' => array("sign_width" => $width, "sign_height" => $height)));
        } else {
            $statusMsg = json_encode(array('error' => true, 'error_mess' => 'Something went wrong.'));
        }
        echo $statusMsg;
    }

    public function cropsave(Request $request)
    {
        if (isset($request->sign_key) && !empty($request->sign_key) && isset($request->user_key) && !empty($request->user_key) && isset($request->crop_cords) && !empty($request->crop_cords)) {
            $crop_cords = explode(",", $request->crop_cords);
            //echo "<pre>"; print_r($crop_cords); die;
            /* Start Image Cropping */
            $x1 = $crop_cords[0];
            $y1 = $crop_cords[1];
            $w = $crop_cords[4];
            $h = $crop_cords[5];

            /* always set format as png */
            $imagick = new Imagick("uploads/signature/" . $request->sign_key);
            $imagick->setImageFormat('png');
            $imagick->writeImage('./uploads/signature/' . $request->sign_key);
            /* always set format as png */

            $image = imagecreatefrompng("uploads/signature/" . $request->sign_key);

            $width = imagesx($image);
            $height = imagesy($image);
            $newwidth = $request->width;
            $newheight = $request->height;

            $thumb = imagecreatetruecolor($newwidth, $newheight);
            $source = imagecreatefrompng("uploads/signature/" . $request->sign_key);

            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagepng($thumb, "uploads/signature/" . $request->sign_key);


            $im = imagecreatefrompng("uploads/signature/" . $request->sign_key);
            $dest = imagecreatetruecolor($w, $h);

            imagecopyresampled($dest, $im, 0, 0, $x1, $y1, $w, $h, $w, $h);
            imagepng($dest, "uploads/signature/" . $request->sign_key);
            /* End Image Cropping */

            $statusMsg = json_encode(array("success" => '1', 'message'=> "Signature added successfully",'success_mess' => "CROP_SAVE_SUCCESS", 'error' => false, 'error_mess' => "", 'file_data' => ""));
        } else {
            $statusMsg = json_encode(array("success" => '0','message'=> "Something went wrong", 'success_mess' => "CROP_SAVE_SUCCESS", 'error' => false, 'error_mess' => "", 'file_data' => ""));
        }
        echo $statusMsg;
    }


    
}
