<?php

namespace App\Http\Controllers\Api;

use App\Constants\Constant;
use App\Models\Payments;
use App\Models\User;
use App\Models\Country; 
use App\Models\States;
use App\Models\Districts; 
use App\Models\Tehsils; 
use App\Models\Panchayat; 
use App\Models\Village; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class ApiController extends BaseController
{



 
public function countryList(Request $request)
{  
        $data_country = Country::select('id', 'name')
                        ->where('status', '1')
                        ->where('deleted_at')
                        ->orderBy('name', 'asc')
                        ->get();

        $response = [
            'success' => true,
            'message' => 'Country List.',
            'data' => $data_country
        ];

        return response()->json($response, 200);
 }

 public function getStateByCountry(Request $request)
 {

    $validator = Validator::make($request->all(), [
        'country_id' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json(['success' => false,'error' => $validator->errors()], 422);
    }
    $countryId = $request->input('country_id'); 
     $country = Country::find($countryId); 
     if (!$country) {
         return response()->json([ 'success' => false,'error' => 'Country not found'], 404);
     }
 
     $cities = States::select('id', 'name')
                 ->where('country_id', $countryId)
                 ->where('status', '1')
                 ->whereNull('deleted_at')
                 ->orderBy('name', 'asc')
                 ->get();
 
    if(count($cities) > 0){

        $response = [
            'success' => true,
            'message' => 'Cities retrieved successfully.',
            'data' => $cities
        ];

    }else{

        $response = [
            'success' => false,
            'message' => 'No state available.',
            'data' => $cities
        ];

    }  
 
     return response()->json($response, 200);
 }

 public function getDistricts(Request $request)
 {

    $validator = Validator::make($request->all(), [
        'state_id' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json(['success' => false,'error' => $validator->errors()], 422);
    }
    $id = $request->input('state_id'); 
     $country = States::find($id); 
     if (!$country) {
         return response()->json([ 'success' => false,'error' => 'state not found'], 404);
     }
 
     $data = Districts::select('id', 'name')
                 ->where('state_id', $id)
                 ->where('status', '1')
                 ->whereNull('deleted_at')
                 ->orderBy('name', 'asc')
                 ->get();
 
    if(count($data) > 0){

        $response = [
            'success' => true,
            'message' => 'Record retrieved successfully.',
            'data' => $data
        ];

    }else{

        $response = [
            'success' => false,
            'message' => 'No state available.',
            'data' => $data
        ];

    }  
 
     return response()->json($response, 200);
 }


 public function getTehsils(Request $request)
 {

    $validator = Validator::make($request->all(), [
        'district_id' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json(['success' => false,'error' => $validator->errors()], 422);
    }
    $id = $request->input('district_id'); 
     $country = Districts::find($id); 
     if (!$country) {
         return response()->json([ 'success' => false,'error' => 'District not found'], 404);
     }
 
     $data = Tehsils::select('id', 'name')
                 ->where('district_id', $id)
                 ->where('status', '1')
                 ->whereNull('deleted_at')
                 ->orderBy('name', 'asc')
                 ->get();
 
    if(count($data) > 0){

        $response = [
            'success' => true,
            'message' => 'Record retrieved successfully.',
            'data' => $data
        ];

    }else{

        $response = [
            'success' => false,
            'message' => 'No state available.',
            'data' => $data
        ];

    }  
 
     return response()->json($response, 200);
 }


 public function getPanchayat(Request $request)
 {

    $validator = Validator::make($request->all(), [
        'tehsil_id' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json(['success' => false,'error' => $validator->errors()], 422);
    }
    $id = $request->input('tehsil_id'); 
     $country = Tehsils::find($id); 
     if (!$country) {
         return response()->json([ 'success' => false,'error' => 'Panchayat not found'], 404);
     }
 
     $data = Panchayat::select('id', 'name')
                 ->where('tehsil_id', $id)
                 ->where('status', '1')
                 ->whereNull('deleted_at')
                 ->orderBy('name', 'asc')
                 ->get();
 
    if(count($data) > 0){

        $response = [
            'success' => true,
            'message' => 'Record retrieved successfully.',
            'data' => $data
        ];

    }else{

        $response = [
            'success' => false,
            'message' => 'No state available.',
            'data' => $data
        ];

    }  
 
     return response()->json($response, 200);
 }
 

 public function getVillage(Request $request)
 {

    $validator = Validator::make($request->all(), [
        'panchayat_id' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json(['success' => false,'error' => $validator->errors()], 422);
    }
    $id = $request->input('panchayat_id'); 
     $country = Panchayat::find($id); 
     if (!$country) {
         return response()->json([ 'success' => false,'error' => 'Panchayat not found'], 404);
     }
 
     $data = Village::select('id', 'name')
                 ->where('panchayat_id', $id)
                 ->where('status', '1')
                 ->whereNull('deleted_at')
                 ->orderBy('name', 'asc')
                 ->get();
 
    if(count($data) > 0){

        $response = [
            'success' => true,
            'message' => 'Record retrieved successfully.',
            'data' => $data
        ];

    }else{

        $response = [
            'success' => false,
            'message' => 'No state available.',
            'data' => $data
        ];

    }  
 
     return response()->json($response, 200);
 }



 
    


}
