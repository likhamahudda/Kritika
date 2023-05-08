<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Families; 
use App\Models\Country; 
use App\Models\States;
use App\Models\Districts; 
use App\Models\Tehsils; 
use App\Models\Panchayat; 
use App\Models\Village; 
use App\Models\Occupations;  
use App\Models\Education; 
use App\Models\FamilyMember; 
use App\Models\AdminNotification; 

use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\Export;
use Maatwebsite\Excel\Facades\Excel;
//use Illuminate\Support\Str;
//use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;


class FamiliesController extends Controller
{
    protected $page = 'families';

    public function __construct(Request $request)
    {
        $this->model = new Families();
        $this->sortableColumns = ['id', 'head_family', 'head_father_name', 'sub_caste','kulrishi','kildevi','gender','cname','sname','dname','tname','pname','vname','pin_code', 'updated_at' ];
        // $this->middleware('permission:List Email Template	|View Email Template', ['only' => ['index', 'view']]);
        // $this->middleware('permission:Update Email Template', ['only' => ['index','form', 'manage']]);
    }

    public function form($id = null)
    {
        $data = Families::where('id', $id)->first();
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $result = ['title' => ucwords($type .'User' ), 'page' => $this->page, 'data' => $data];
        return view('admin.' . $this->page . '.form', $result);
    }

    public function manage(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)){ $type='Add '; }else{ $type='Update '; }
        $request->validate([
            'head_family' => 'required',
            'head_father_name' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'district_id' => 'required',
            'tehsil_id' => 'required',
             'panchayat_id' => 'required',
             'village_id' => 'required',
        ]);
        
       // $data = new Families();

        if(isset($id) && !empty($id)){  // add new data
            $data = Families::where('id', $request->id)->first();
           // FamilyMember::where('family_id', $id)->delete();
            DB::table('family_member')
            ->where('family_id', '=',  $id)
            ->delete();

            saveAdminNotification("Update Family","Family record updated by ");

        }else{  // update data 
            $data = new Families();  

           
        }

        $data->head_family = $request->get('head_family');
        $data->head_father_name = $request->get('head_father_name');
        $data->sub_caste = $request->get('sub_caste');
        $data->kulrishi = $request->get('kulrishi');
        $data->kildevi = $request->get('kildevi');
        $data->gender = $request->get('gender');       
        $data->country_id = $request->get('country_id');
        $data->state_id = $request->get('state_id');
        $data->district_id = $request->get('district_id');
        $data->tehsil_id = $request->get('tehsil_id');
        $data->panchayat_id = $request->get('panchayat_id');
        $data->village_id = $request->get('village_id');
        $data->house_village = $request->get('house_village');
        $data->panchayat_committee = $request->get('panchayat_committee');
        $data->pin_code = $request->get('pin_code');
        $data->created_at = date('Y-m-d H:i:s');
        $data->updated_at =  date('Y-m-d H:i:s');       
        $data->status = '1';
        
     
       $json = ['status' => true, 'message' => ucfirst($this->page) . ' has been '.$type.' succesfully', 'redirect_url' => route('families.index')];
        
        if ($data->save()) {  

            saveAdminNotification("New Family","New Family record added by ");

                if(isset($id) && !empty($id)){  // add new data
                  
                    $lastInsertId = $id; 
                }else{  // update data 
                    $lastInsertId = $data->id; 
                    $user = Families::find($lastInsertId);
                    $user->copy_id = $lastInsertId; 
                    $user->save();

                    
                }
                

                $memberData = []; // Create an empty array
 
                
                if(count($request->member_name) > 0){

                foreach ($request->member_name as $key => $value) {

                    if(isset($request->member_name[$key]) && !empty($request->member_name[$key])){
                    $memberData[] = [
                        'member_name' => $request->member_name[$key],
                        'family_id' => $lastInsertId,
                        'relation_type' => $request->relation_type[$key],
                        'relation_name_with' => $request->relation_name_with[$key],
                        'education' => $request->education[$key],
                        'occupation' => $request->occupation[$key],
                        'gender_type' => $request->gender_type[$key],
                        'phone_no' => $request->phone_no[$key],
                        'email_address' => $request->email_address[$key],
                        'married_status' => $request->married_status[$key],
                        'dob' => date("Y-m-d", strtotime($request->dob[$key])),
                        'age' => $request->age[$key],
                        'address' => $request->address[$key],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' =>date('Y-m-d H:i:s'),
                         
                    ];
                }
                }

                FamilyMember::insert($memberData); // Save the data to the database 
            }

                $content = getEmailContentValue(2);
                if($content){
                    $emailval = $content->description;
                    $subject = $content->subject.' - '.$request->get('first_name').' '.$request->get('last_name');
                    //echo getSettingValue('logo'); die();
                    if(empty(getSettingValue('logo'))){
                        $logo = url('images/logo.png');
                    }else{
                        $logo = url('uploads/logo').'/'.getSettingValue('logo');
                    }
                    $replace_data = [
                            '@name0' => $request->get('first_name').' '.$request->get('last_name'),
                            '@username_0' => $request->get('email'),
                            '@password0' => $request->get('password'),
                            '@logo' => $logo,
                        ];

                        foreach ($replace_data as $key => $value) {
                            $emailval = str_replace($key, $value, $emailval);
                        }
                        return response()->json($json);
                    // if (sendMail($request->get('email'), $emailval, $subject)) {
                    //     return response()->json($json);
                    // } else {
                    //     return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again.']);
                    // }
                }
                    // else{
                    //     return response()->json($json);
                    // }
            
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }



    public function index(Request $request)
    {
 

       // Clear the route cache
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        // Get the path to the route cache file
        $path = base_path('bootstrap/cache/routes.php'); 
        // Delete the route cache file
        if (file_exists($path)) {
            app(Filesystem::class)->delete($path);
        }
  
        if ($request->ajax()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $country_id = $request->input('country_id');
            $state_id = $request->input('state_id');
            $district_id = $request->input('district_id');
            $tehsil_id = $request->input('tehsil_id');
            $panchayat_id = $request->input('panchayat_id');
            $village_id = $request->input('village_id');
            $gender = $request->input('gender');
            $kulrishi = $request->input('kulrishi');
            $pin_code = $request->input('pin_code');
            $copy_id = $request->input('copy_id');

            
           
           
            $search = $request['search']['value'];

            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $sortableColumns = $this->sortableColumns;

            
           // $response = $this->getData($name_search,$email_search,$mobile_search,$status_search,$from_date,$to_date, $sortableColumns[$orderby], $order);
            $response = $this->getData($search, $country_id,$state_id,$district_id,$tehsil_id,$panchayat_id, $village_id,$gender,$kulrishi,$pin_code,$copy_id,$sortableColumns[$orderby], $order);

            $totaldata = $response->count();
           // $response->whereBetween('families.created_at', [$start_date, $end_date]);

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
                $row['head_family'] = $value->head_family;
                $row['head_father_name'] = $value->head_father_name;
                $row['sub_caste'] = $value->sub_caste;
                $row['kulrishi'] = $value->kulrishi;
                $row['kildevi'] = $value->kildevi;
                $row['gender'] = $value->gender;
                $row['cname'] = $value->cname;
                $row['sname'] = $value->sname;
                $row['dname'] = $value->dname;
                $row['tname'] = $value->tname;
                $row['pname'] = $value->pname;
                $row['vname'] = $value->vname;
                $row['pin_code'] = $value->pin_code;

              //  $row['status'] = statusAction($value->status,$value->id,array(),'statusAction',$this->page . '.status');
                $row['updated_at'] = default_date_format($value->created_at);

                $edit = '';
                //if(Auth::user()->can('Update Email Template')){
                  //  $edit = editButton($this->page . '.form', ['id' => $value->id]);
                //  url('admin/images/edit-a.png');
                    $edit = '<li><a class="dropdown-item " href="' .url('admin/families/edit/'.$value->id) . '"  type="button" ><img src=" '.url('admin/images/edit-a.png').'"> </a></li>';
                //} 

                $copy = '<li><a class="dropdown-item get_copy" id="'.$value->copy_id.'" type="button" > <img src=" '.url('admin/images/copy-a.png').'"> </a></li>';


                $view = '';
                //if(Auth::user()->can('View Email Template')){
                    $view = viewButton($this->page . '.view', ['id' => $value->id]);
                //}
                    $delete = deleteButton($this->page . '.delete', ['id' => $value->id]);

                $row['actions'] = createButton($edit . $view . $delete.$copy);
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

        $data_country = Families::select('country.name as name','country.id as id')
        ->where('families.deleted_at','=',null)  
        ->leftJoin('country', 'country.id', '=', 'families.country_id') 
        ->groupBy('country.id')
        ->get(); 

        $data_state = Families::select('states.name as name','states.id as id')
        ->where('families.deleted_at','=',null)  
        ->leftJoin('states', 'states.id', '=', 'families.state_id') 
        ->groupBy('states.id')
        ->get(); 

        $data_districts = Families::select('districts.name as name','districts.id as id')
        ->where('families.deleted_at','=',null)  
        ->leftJoin('districts', 'districts.id', '=', 'families.district_id') 
        ->groupBy('districts.id')
        ->get(); 

        $data_tehsils = Families::select('tehsils.name as name','tehsils.id as id')
        ->where('families.deleted_at','=',null)  
        ->leftJoin('tehsils', 'tehsils.id', '=', 'families.tehsil_id') 
        ->groupBy('tehsils.id')
        ->get(); 

        $data_panchayat = Families::select('panchayat.name as name','panchayat.id as id')
        ->where('families.deleted_at','=',null)  
        ->leftJoin('panchayat', 'panchayat.id', '=', 'families.panchayat_id') 
        ->groupBy('panchayat.id')
        ->get(); 
 
        $data_village = Families::select('village.name as name','village.id as id')
        ->where('families.deleted_at','=',null)  
        ->leftJoin('village', 'village.id', '=', 'families.village_id') 
        ->groupBy('village.id')
        ->get(); 


        $data = ['title' => ucfirst('Families Management'), 'page' => $this->page,
        'data_state'=>$data_state,
        'data_country'=>$data_country,
        'data_districts'=>$data_districts,
        'data_tehsils'=>$data_tehsils,
        'data_panchayat'=>$data_panchayat,
        'data_village'=>$data_village
    ];
        return view('admin.' . $this->page . '.index', $data);
    }

    public function getData_old($name_search = null,$email_search = null,$mobile_search = null,$status_search = null,$from_date = null,$to_date = null,$orderby = null, $order = null, $request = null)
    {
        $q = Families::where('id','!=','1')->where('deleted_at','=',null);
        $orderby = $orderby ? $orderby : 'created_at';
        $order = $order ? $order : 'desc';

        if ($name_search && !empty($name_search)) {
                $q->whereRaw('CONCAT(first_name, \' \' , last_name) LIKE \'%'.$name_search.'%\' ');
        }
        if ($email_search && !empty($email_search)) {
            $q->where('email','LIKE', '%'.$email_search.'%');
        }
        if ($mobile_search && !empty($mobile_search)) {
            $q->where('mobile','LIKE', '%'.$mobile_search.'%');
        }
        if ($status_search=='0') {
                $q->where('status','=', '0');
        }elseif($status_search=='1') {
            $q->where('status','=', '1');
        }
        if ($from_date && !empty($from_date)) {
                $q->where('created_at', '>=', $from_date);
        }
        if ($to_date && !empty($to_date)) {
                $q->where('created_at', '<=', $to_date);
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }

    public function getData($search = null, $country_id=null, $state_id=null, $district_id=null, $tehsil_id=null, $panchayat_id=null,$village_id=null,$gender=null,$kulrishi=null,$pin_code=null,$copy_id=null, $orderby = null, $order = null, $request = null)
    {
        $q = Families::select('families.*','tehsils.name as tname','districts.name as dname','panchayat.name as pname','states.name as sname','country.name as cname','village.name as vname')
                    ->where('families.deleted_at','=',null)
                    ->leftJoin('panchayat', 'panchayat.id', '=', 'families.panchayat_id')
                    ->leftJoin('districts', 'districts.id', '=', 'families.district_id')
                    ->leftJoin('tehsils', 'tehsils.id', '=', 'families.tehsil_id')
                    ->leftJoin('states', 'states.id', '=', 'families.state_id')
                    ->leftJoin('village', 'village.id', '=', 'families.village_id')
                    ->leftJoin('country', 'country.id', '=', 'families.country_id');
        $orderby = $orderby ? $orderby : 'families.created_at';
        $order = $order ? $order : 'desc';

        if ($search && !empty($search)) {
            $q->where(function ($query) use ($search) {
                $query->where('families.head_family', 'LIKE', '%' . $search . '%')
                ->orwhere('families.head_father_name', 'LIKE', '%' . $search . '%')
                ->orwhere('families.sub_caste', 'LIKE', '%' . $search . '%')
                ->orwhere('village.name', 'LIKE', '%' . $search . '%')
                ->orwhere('panchayat.name', 'LIKE', '%' . $search . '%')
                ->orwhere('districts.name', 'LIKE', '%' . $search . '%')
                ->orwhere('states.name', 'LIKE', '%' . $search . '%')
                ->orwhere('country.name', 'LIKE', '%' . $search . '%')
                ->orwhere('tehsils.name', 'LIKE', '%' . $search . '%')
                ->orwhere('families.pin_code', 'LIKE', '%' . $search . '%');
               //// ->orwhere('States_code', 'LIKE', '%' . $search . '%') ;               
                              
            });
        }

        if ($country_id && !empty($country_id)) {
            $q->where('families.country_id','=', $country_id);
         }
         if ($state_id && !empty($state_id)) {
            $q->where('families.state_id','=', $state_id);
         }
         if ($district_id && !empty($district_id)) {
            $q->where('families.district_id','=', $district_id);
         }
         if ($tehsil_id && !empty($tehsil_id)) {
            $q->where('families.tehsil_id','=', $tehsil_id);
         }
         if ($panchayat_id && !empty($panchayat_id)) {
            $q->where('families.panchayat_id','=', $panchayat_id);
         }
         if ($village_id && !empty($village_id)) {
            $q->where('families.village_id','=', $village_id);
         }
         if ($gender && !empty($gender)) {
            $q->where('families.gender','=', $gender);
         }
         if ($kulrishi && !empty($kulrishi)) {
            $q->where('families.kulrishi','=', $kulrishi);
         }
         if ($pin_code && !empty($pin_code)) {
            $q->where('families.pin_code','=', $pin_code);
         }

        

          if (isset($copy_id) && !empty($copy_id)) {
            $q->where('families.copy_id', $copy_id);
         }

         
         
         


         $response = $q->orderBy($orderby, $order);
      //   $q->get([$country_id, $panchayat_id, $copy_id]);
      //  $lastQuery = $q->toSql();
       
       // echo $lastQuery;die;
        return $response;
    }

    public function add(Request $request)
    {

        $data_country = Country::where('status', '1')->where('deleted_at', '=', null)->get();
        $data_occupations = Occupations::where('status', '1')->where('deleted_at', '=', null)->get();
        $data_education = Education::where('status', '1')->where('deleted_at', '=', null)->get(); 
  
        $result = ['title' => 'Add New Families', 'page' => $this->page,
        'data_country' => $data_country,  
        'data_occupations' => $data_occupations,
        'data_education' => $data_education
    ];
        return view('admin.families.add', $result);
    }

    public function edit($id)
    {
        $data =  Families::where('id', $id)->first();
       // $role_permission =    $data->getAllPermissions()->pluck('id')->toArray();
       // $all_permission = Permission::all();

       $data_country = Country::where('status', '1')->where('deleted_at', '=', null)->get();
       $data_occupations = Occupations::where('status', '1')->where('deleted_at', '=', null)->get();
       $data_education = Education::where('status', '1')->where('deleted_at', '=', null)->get();
       $data_state = States::where('country_id', $data->country_id)->get();
       $data_district = Districts::where('state_id', $data->state_id)->get();
       $data_tehsils = Tehsils::where('district_id', $data->district_id)->get();
       $data_panchayat = Panchayat::where('tehsil_id', $data->tehsil_id)->get();
       $data_village = Village::where('panchayat_id', $data->panchayat_id)->get();
       $data_familyMember = FamilyMember::where('family_id', $data->id)->get();


         
        // use App\Models\Occupations;  
        // use App\Models\Education; 
        // use App\Models\FamilyMember;

      


        if (isset($data)) {
            $result = ['title' => 'Families Edit',
              'data' => $data,
              'page' => $this->page,
              'data_country' => $data_country,
              'data_state' => $data_state,
              'data_district' => $data_district,
              'data_tehsils' => $data_tehsils,
              'data_panchayat' => $data_panchayat,
              'data_village' => $data_village,
              'data_occupations' => $data_occupations,
              'data_education' => $data_education,
              'data_familyMember' => $data_familyMember
            ];
            return view('admin.' . $this->page . '.edit', $result);
        } else {
            return Redirect::back()->with('error', 'Record Not found');
        }
    }

    public function getCopyFamily(Request $request)
    { 

      if(isset($request->id) && !empty($request->id)){  // add new data
        $copy_data = Families::where('id', $request->id)->first();
           
        $data = new Families(); 
        $data->head_family = $copy_data->head_family;
        $data->copy_id = $copy_data->copy_id;
        $data->head_father_name = $copy_data->head_father_name;
        $data->sub_caste = $copy_data->sub_caste;
        $data->kulrishi = $copy_data->kulrishi;
        $data->kildevi = $copy_data->kildevi;
        $data->gender = $copy_data->gender;       
        $data->country_id = $copy_data->country_id;
        $data->state_id = $copy_data->state_id;
        $data->district_id = $copy_data->district_id;
        $data->tehsil_id = $copy_data->tehsil_id;
        $data->panchayat_id = $copy_data->panchayat_id;
        $data->village_id = $copy_data->village_id;
        $data->house_village = $copy_data->house_village;
        $data->panchayat_committee = $copy_data->panchayat_committee;
        $data->pin_code = $copy_data->pin_code;
        $data->created_at = date('Y-m-d H:i:s');
        $data->updated_at =  date('Y-m-d H:i:s');       
        $data->status = '1'; 
        
        if ($data->save()) {  

                $lastInsertId = $data->id;   
                $memberData = []; // Create an empty array 

                $members_copy_data = FamilyMember::where('family_id', $request->id)->get();
                
                if(count($members_copy_data) > 0){

                foreach ($members_copy_data as $value) { 
                  
                    $memberData[] = [
                        'member_name' => $value->member_name,
                        'family_id' => $lastInsertId,
                        'relation_type' => $value->relation_type,
                        'relation_name_with' => $value->relation_name_with,
                        'education' => $value->education,
                        'occupation' => $value->occupation,
                        'gender_type' => $value->gender_type,
                        'phone_no' => $value->phone_no,
                        'email_address' => $value->email_address,
                        'married_status' => $value->married_status,
                        'dob' => date("Y-m-d", strtotime($value->dob)),
                        'age' => $value->age,
                        'address' => $value->address,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' =>date('Y-m-d H:i:s'),
                         
                    ];
                
                }

                FamilyMember::insert($memberData); // Save the data to the database 
            }

          $json = ['status' => true, 'message' => 'Family data  has been copied  succesfully','id'=>$request->id, 'redirect_url' => route('families.index')];
 
          return response()->json($json);
 
            
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
 

        }else{

            $json = ['status' => false, 'message' =>  ' Something went wrong'];

        }
   }

   public function readNotification(){

 
    AdminNotification::where('status', '0')->update([
        'status' => '1',
        'is_read' => '1',
    ]);

    $json = ['status' => true, 'message' => 'Notification has been read succesfully'];
 
    return response()->json($json);

   }

    public function delete_feature(Request $request)
    {      
        $id = $request->get('id');

        $data_delete = DB::table('family_member')
        ->where('id', '=',  $id)
        ->delete();
      
 
        if ($data_delete) {
            return response()->json(['status' => true, 'message' => 'Member has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    public function getStatesByCountry(Request $request)
    { 
        $states = States::where('country_id', $request->country_id)->orderBy('name')->where('status', '1')->where('deleted_at', '=', null)->get();
        return response()->json($states);
    }
    public function getDistrictsByState(Request $request)
    { 
        $districts = Districts::where('state_id', $request->state_id)->where('status', '1')->where('deleted_at', '=', null)->orderBy('name')->get();
        return response()->json($districts);
    }

    public function getTehsilByState(Request $request)
    { 
        $districts = Tehsils::where('district_id', $request->district_id)->where('status', '1')->where('deleted_at', '=', null)->orderBy('name')->get();
        return response()->json($districts);
    }

    public function getPanchayatBytehsil(Request $request)
    { 
        $districts = Panchayat::where('tehsil_id', $request->tehsil_id)->where('status', '1')->where('deleted_at', '=', null)->orderBy('name')->get();
        return response()->json($districts);
    }

    public function getVillageBytehsil(Request $request)
    { 
        $Village = Village::where('panchayat_id', $request->panchayat_id)->where('status', '1')->where('deleted_at', '=', null)->orderBy('name')->get();
        return response()->json($Village);
    }
  
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:30',
        ]);

        $role = new Role();
        $role->name = $request->get('name');
        $role->guard_name = 'web';
        if ($role->save()) {
            $role->givePermissionTo($request->permission_id);
            return response()->json(['status' => true, 'message' => ucfirst($this->page) . ' has been created succesfully', 'redirect_url' => route('role.index')]);
        } else {
            return response()->json(['status' => false,  'message' => 'Something went wrong']);
        }
    }



    public function view($id)
    {
        $data = Families::select('families.*','tehsils.name as tname','districts.name as dname','panchayat.name as pname','states.name as sname','country.name as cname','village.name as vname')
        ->where('families.deleted_at','=',null)
        ->leftJoin('panchayat', 'panchayat.id', '=', 'families.panchayat_id')
        ->leftJoin('districts', 'districts.id', '=', 'families.district_id')
        ->leftJoin('tehsils', 'tehsils.id', '=', 'families.tehsil_id')
        ->leftJoin('states', 'states.id', '=', 'families.state_id')
        ->leftJoin('village', 'village.id', '=', 'families.village_id')
        ->leftJoin('country', 'country.id', '=', 'families.country_id')
        ->where('families.id', $id)->first();


        $data_familyMember = FamilyMember::select('family_member.*','occupations.name as oname','education.name as ename')
        ->where('family_member.deleted_at','=',null)
        ->leftJoin('occupations', 'occupations.id', '=', 'family_member.occupation')
        ->leftJoin('education', 'education.id', '=', 'family_member.education') 
        ->where('family_id', $id)->get();

 
      //  $data = Families::where('id', $id)->first();
        if (isset($data)) { 
      

            $result = ['title' => ucwords('Families Details'), 'page' => $this->page, 'data' => $data, 'data_familyMember' => $data_familyMember, 'currency' => CONSTANT::CURRENCY];
            return view('admin.' . $this->page . '.view', $result);
        } else {
            $result = ['title' => ucwords('Error'), 'page' => $this->page];
            return view('admin.error.404_popup', $result);
        }
    }

    public function status(Request $request)
    {     
        $id = $request->get('id');
        if($request->get('status')==Constant::ACTIVE){
            $status = 'active';
        }else{
            $status = 'in-active';
        }
        $data = Families::where('id', $id)->first();
        $data->status = $request->get('status');
        if ($data->save()) {
            return response()->json(['status' => true, 'message' => ucfirst($this->page) . ' has been '.$status.' succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
    public function delete(Request $request)
    {     
        $id = $request->get('id');
        $data = Families::where('id', $id)->first();
        $data->deleted_at = date('Y-m-d');
        if ($data->save()) {
            $path = 'uploads/user_profile/';
            if(!empty($data->image)){
                deleteImage($path . $data->image);
            }
            return response()->json(['status' => true, 'message' => ucfirst($this->page) . ' has been deleted succesfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    public function users_export(Request $request) 
    {   
        $name_search = $request->name_search;
        $email_search = $request->email_search;
        $mobile_search = $request->mobile_search;
        $status_search = $request->status_search;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $collection=$this->getData($name_search,$email_search,$mobile_search,$status_search,$from_date,$to_date)->selectRaw('id,CONCAT(first_name, \' \', last_name) as name,email,mobile,status,DATE_FORMAT(created_at,\'%d %M %Y \')')->get();
        foreach ($collection as $data) {
            if($data->status==1){
                $data->status='Active';
            }else{
                $data->status='In-active';
            }
        }

        $headings=["ID", "Name", "Email", "Phone. no", "Status", "Register Date"];
        return Excel::download(new Export($collection,$headings), 'families report.csv');
    }
}
