<?php
 
namespace App\Http\Middleware;
  
use Closure;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
  
class UserAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
 
        if($request->ajax()){


            // check session is exist or not
            if (!auth()->guard('user')->check()) {
                return response()->json(["error" => '1', 'error_mess' => 'Something went wrong.','status' => false
                ,'message'=>"Something went wrong"]);
            }
           
            
            if(auth()->guard('user')->user()->status=='0' || auth()->guard('user')->user()->deleted_at!=NULL){
                auth()->guard('user')->logout();
                /*$statusMsg = json_encode(array("error" => '1', 'error_mess' => 'Something went wrong.','status' => false
                ,'message'=>"Something went wrong")); 
                echo $statusMsg;
                exit(); */
                return response()->json(["error" => '1', 'error_mess' => 'Something went wrong.','status' => false
                ,'message'=>"Something went wrong"]);
            }

        }else{
            if (!auth()->guard('user')->check()) {
                return redirect()->route('user.login');
                //return response()->json('Your account is inactive');
            }
            
            if(auth()->guard('user')->user()->status=='0'){
                auth()->guard('user')->logout();
                return redirect()->route('user.login');
            }

            if(auth()->guard('user')->user()->deleted_at!=NULL){
                auth()->guard('user')->logout();
                return redirect()->route('user.login');
            }
        }
        
        return $next($request);
    }
}