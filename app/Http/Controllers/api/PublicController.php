<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    
     public function login(Request $request){
         
            $validator = Validator::make($request->all(), [
                'terminal_id' =>'required',
                'password' => 'required'
            ]);
            
            $validator->stopOnFirstFailure();
            
            if($validator->fails()){
                 return response()->json(['status' => 400,'message' => $validator->errors()->first()]);
            }
            
            $terminal_id = $request->terminal_id;
            $password = $request->password;
            
            $login_status = DB::table('admins')->where('terminal_id',$terminal_id)->where('password',$password)->where('status',1)->where('role_id',4)->first();
            
           if($login_status){
               return response()->json(['status'=>200,'message'=>'login successfully.','id'=>$login_status->id,'terminal_id'=>$login_status->terminal_id]);
           }else{
                return response()->json(['status'=>400,'message'=>'Invalid user or credentials!']);
           }
     }
    
    
    public function profile(?string $id){
         $validator = Validator::make(['id'=>$id],[
             'id'=>'required|exists:admins,id'
             ]);
            $validator->stopOnFirstFailure();
            if($validator->fails()){
                return response()->json(['status' => 400,'message' => $validator->errors()->first()]);
            }
     $data = DB::table('admins')->where('id',$id)->where('status',1)->where('role_id',4)->first();
     
     if($data){
         return response()->json(['status'=>200,'message'=>'Admin record found','wallet'=>$data->wallet]);
     }else{
        return response()->json(['status'=>400,'message'=>'Invalid credentials or user!']); 
     }
        
    }
    
         public function change_password(Request $request){
         
            $validator = Validator::make($request->all(), [
                'id' =>'required|exists:admins,id',
                'old_key' => 'required|exists:admins,password',
                'new_key'=>'required',
                'confirm_key'=>'required'
            ]);
            
            $validator->stopOnFirstFailure();
            
            if($validator->fails()){
                 return response()->json(['status' => 400,'message' => $validator->errors()->first()]);
            }
            
            
            $id = $request->id;
            $old_key = $request->old_key;
            $new_key = $request->new_key;
            $confirm_key = $request->confirm_key;
            
            if(!($new_key ==$confirm_key)){
                return response()->json(['status'=>400,'message'=>'Confirm key not matched!']);
            }
        
            $update_password = DB::table('admins')->where('id',$id)->update([
                'password'=>$new_key
                ]);
                
                if($update_password){
                    return response()->json(['status'=>200,'message'=>'Password updated successfully.']);
                }else{
                    return response()->json(['status'=>400,'message'=>'Failed to update password!']);
                }
    
         }
    
    
    
}