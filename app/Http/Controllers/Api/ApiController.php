<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use DB;
class ApiController extends Controller
{
    //

    public function list($key,Request $request)
    {
		 

    	$validator = Validator::make($request->all(),[
            'start'    => 'date',
            'end' => 'date|after:start',
        ]);

        if($validator->fails()){
 
            return response()->json([
                'error' => 'true',
                'message' => $validator->errors()
            ],200);
        }


    	$user = User::where('api_key',$key)->first();
    	
    	if(!$user){
    		return response()->json([
                'error' => 'true',
                'message' => "Invalid key"
            ],200);
    	}
    	if($user->blocked=="1"){
    		return response()->json([
                'error' => 'true',
                'message' => "Account blocked"
            ],200);
    	}


 
    	$messages = Message::select(DB::raw('id as query_id'),DB::raw('from_name as person_name'),DB::raw('from_email as email'),DB::raw('from_phone as mobile'),'location','address',DB::raw('city as state'),'pincode','drugs_license','have_gst_number','purchase_period','call_back_time',DB::raw('message as requirement'),DB::raw('created_at as submit_time'),'profession','minimum_investment')->where('to_user_id',$user->id)->where(function($query)use($request){


    			if($request->start && $request->end){
    				$query->whereBetween('created_at',[$request->start,$request->end]);
    			} 
    			 
    	})->orderBy('id','desc')->get();

     
    	 return response()->json([
                'error' => 'false',
                'data' => $messages
            ],200);
    }
}
