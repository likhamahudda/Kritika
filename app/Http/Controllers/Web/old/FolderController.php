<?php

namespace App\Http\Controllers\Web;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Folder;
use App\Models\MoveDocumentFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FolderController extends Controller
{
    public function manage(Request $request)
    {
        $fid = $request->fid;
        if (isset($fid) && !empty($fid)) {
            $data['data'] = Folder::where('id', $fid)->first();
            $html = view('users.folder.form', $data)->render();
            $statusMsg = json_encode(array('html' => $html, 'message' => '', 'status' => 'success'));
        } else {
            $html = view('users.folder.form', $data = [])->render();
            //echo $html;
            $statusMsg = json_encode(array('html' => $html, 'message' => '', 'status' => 'success',));
        }
        echo $statusMsg;
    }

    public function submit(Request $request)
    {
        $fid = $request->fid;
        if (isset($fid) && !empty($fid)) {
            if (Folder::where('folder_name', '=', $request->get('folder_name'))->where('id', '!=', $fid)->where('deleted_at', '=', null)->count() > 0) {
                echo $statusMsg = json_encode(array('msg' => 'Folder name is already exist.', 'status' => 'error'));
                exit();
            }
            $data = Folder::where('id', $fid)->first();
            $statusMsg = json_encode(array('msg' => 'Folder rename successfully', 'status' => 'success'));
        } else {
            if (Folder::where('folder_name', '=', $request->get('folder_name'))->where('deleted_at', '=', null)->count() > 0) {
                echo $statusMsg = json_encode(array('msg' => 'Folder name is already exist.', 'status' => 'error'));
                exit();
            }
            $data = new Folder();
            $data->user_id = Auth::guard('user')->user()->id;
            $statusMsg = json_encode(array('msg' => 'Folder created successfully', 'status' => 'success'));
        }
        $data->folder_name = $request->folder_name;
        if ($data->save()) {
            $statusMsg = $statusMsg;
        } else {
            $statusMsg = json_encode(array('msg' => 'Something went wrong', 'status' => 'error'));
        }
        echo $statusMsg;
    }

    public function list(Request $request)
    {
        $user = Auth::guard('user')->user();
        $doc_id = $request->docid;
        $data = Folder::where('user_id', $user->id)->get();
        $html = '';
        if (isset($doc_id) && !empty($doc_id)) {
            $data = Folder::where('user_id', $user->id)
            ->whereNotExists(function($query) use ($doc_id)
            {   $query->select(DB::raw(1))
                        ->from('move_documents_folder')
                        ->where('move_documents_folder.prepared_doc_id',$doc_id)
                        ->whereRaw('move_documents_folder.folder_id = user_folders.id')
                        ->whereNull('move_documents_folder.deleted_at');
            })->get();
            
            foreach ($data as $row) {
                $html .= '
                <ul>
                    <li>
                        <label><input folder-id="'.$row->id.'" doc-id="'.$doc_id.'" type="radio" name="move_folder_list" class="move_folder_list"> ' . $row->folder_name . '</label>
                    </li>
                </ul>';
            }
            $html .= '<a href="javascript:void(0)" class="btn action-btn moveDocLink"><i class="icon-white icon-ok-sign"></i> Move</a>';
        } else {  // call for sidebar folder listing
            $data = Folder::where('user_id', $user->id)->get();
            foreach ($data as $row) {
                $html .= '<li class="popup-folder-list-kp"> 
                    <a href="' . url('/assignments/list?fid=' . $row->id) . '" class="corner-all"><i class="icon-folder-close category-icon"></i><span class="sidebar-text">' . $row->folder_name . '</span>
                    </a><a href="javascript:void(0);" class="edit_folder_name" onclick="edit_modal(' . $row->id . ');" folder-id="' . $row->id . '"><i class="icon-pencil"></i></a>
    
                </li>';
            }
        }
        $statusMsg = json_encode(array('html' => $html, 'msg' => '', 'status' => 'success'));
        echo $statusMsg;
    }

    public function folder_assign(Request $request)
    {
        $user = Auth::guard('user')->user();
        if (!empty($request->all())) {
            // Remove this document from another folder
            MoveDocumentFolder::where('user_id',$user->id)->where('prepared_doc_id',$request->docid)->delete();

            // move document into folder
            $dataroles = new MoveDocumentFolder();
            $dataroles->user_id = $user->id;
            $dataroles->folder_id = $request->fId;
            $dataroles->prepared_doc_id = $request->docid;
            $dataroles->save();

            if($dataroles->id > 0){
                $statusMsg = json_encode(array("status" => 'success', 'msg' => 'Document move successfully.'));
            }else{
                $statusMsg = json_encode(array("status" => 'error', 'msg' => 'Something went wrong.'));
            }
        }else{
            $statusMsg = json_encode(array("status" => 'error', 'msg' => 'Something went wrong.'));
        }
        echo $statusMsg;  
    }
}
