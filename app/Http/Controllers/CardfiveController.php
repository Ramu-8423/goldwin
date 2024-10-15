<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Validator;
use App\Models\Admin;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CardfiveController extends Controller
{
    
     public function delete_prediction(Request $request){
         
          $modify_date_time = date('Y-m-d H:i:s', strtotime($request->result_time)); // because it is coming like this - 2024-10-10T12:12 due to input box datetime-local formate
          $unix_modify_time = strtotime($modify_date_time);
          $adjustment = $unix_modify_time % 300;
          if($adjustment==0){
                $min_modify_time = date('Y-m-d H:i:s', $unix_modify_time - $adjustment);
                $max_modify_time = $min_modify_time;
           }else{
                $min_modify_time = date('Y-m-d H:i:s', $unix_modify_time - $adjustment);
                $max_modify_time = date('Y-m-d H:i:s', $unix_modify_time + (300 - $adjustment));
             }

          $delete = DB::table('admin_results')->where('result_time',$max_modify_time)->delete();
          if($delete){
              return redirect()->back()->with('success','Deleted Successfully'); 
          }else{
              return redirect()->back()->with('error','Failed to delete!'); 
          }
     }
     
     public function prediction_history(Request $request){
         $perpage = 10;
         $prediction_history = DB::table('admin_results')->orderBy('id','desc')->paginate($perpage);
         
         return view('admin.predictionHistory')->with('prediction_history',$prediction_history); 
     }
   
    public function admin_prediction(Request $request){
          $custom_date_time= $request->custom_result_date_time;
          if($custom_date_time){
              $custom_date_time = date('Y-m-d H:i:s', strtotime($custom_date_time));
          }
       // dd($custom_date_time);
          $result_time = $request->result_time;
          $number = $request->number;
          $prediction_insert = DB::table('admin_results')->insert(['card_number'=>$number,'result_time'=>$result_time]);
          
          if($prediction_insert){
              return redirect()->back()->with('success','Result Inserted Successfully');
          }else{
              return redirect()->back()->with('error','Result Inserted Successfully');
          }
          
    }
    
    
    public function result_history(Request $request){
            $perPage = 10;
        if ($request->isMethod('post')) {
            if($request->result_time){
                 $modify_date_time = date('Y-m-d H:i:s', strtotime($request->result_time)); // because it is coming like this - 2024-10-10T12:12 due to input box datetime-local formate
                 $unix_modify_time = strtotime($modify_date_time);
                  $adjustment = $unix_modify_time % 300;
                    if($adjustment==0){
                        $min_modify_time = date('Y-m-d H:i:s', $unix_modify_time - $adjustment);
                        $max_modify_time = $min_modify_time;
                        $request->session()->put('result_time',$max_modify_time);
                    }else{
                        $min_modify_time = date('Y-m-d H:i:s', $unix_modify_time - $adjustment);
                        $max_modify_time = date('Y-m-d H:i:s', $unix_modify_time + (300 - $adjustment));
                         $request->session()->put('result_time',$max_modify_time);
                    }
            }
           }
         $result_time  = session('result_time');
         $query = DB::table('results');
               if ($result_time) {
                    $query->where('result_time',$result_time);
                }
        $result_history = $query->orderBy('id','desc')->paginate($perPage);
       
        return view('admin.resulthistory')->with('results',$result_history);
    }
    
  public function bethistory(Request $request) {
    
      $strole_id =null;
      $sbrole_id =null;
      $use_terminal_id =null;
      $stokistid =null;
      $sub_stokistid =null;
    if($request->st_terminal_id)
    {
       $stokist = DB::table('admins')->where('terminal_id',$request->st_terminal_id)->first();
       $stokistid = $stokist->id;
       $strole_id = 2;
    }
    if($request->sub_terminal_id)
    {
       $sub_terminal_id = DB::table('admins')->where('terminal_id',$request->sub_terminal_id)->first();
       $sub_stokistid = $sub_terminal_id->id;
       $sbrole_id = 3;
    }
  
    $bet_history = DB::table('bets')
        ->leftJoin('admins', 'bets.user_id', '=', 'admins.id')
        ->select(
            'bets.*',
            'admins.role_id as admin_role_id',
            'admins.terminal_id as admin_terminal_id',
            'admins.status as admin_status',
            'admins.wallet as admin_wallet',
            'admins.terminal_id as terminal_id',
            'admins.day_wallet as admin_day_wallet',
            'admins.today_add_money as admin_today_add_money',
            'admins.receiveamount as admin_receiveamount'
        )
        ->whereIn('admins.status', [0, 1]);
    if ($strole_id == 2) {
        $bet_history = $bet_history->where('admins.inside_stockist', $stokistid);
    }

    if ($sbrole_id == 3) {
        $bet_history = $bet_history->where('admins.inside_substockist', $sub_stokistid);
    }
    
    if($stokistid != null && $sub_stokistid != null && $strole_id == 2 && $sbrole_id == 3)
    {
         $bet_history = $bet_history->where('admins.inside_stockist', $stokistid)->where('admins.inside_substockist', $sub_stokistid);
    }

    if ($request->has('use_terminal_id') && !empty($request->use_terminal_id)) {
        $bet_history = $bet_history->where('admins.terminal_id', $request->use_terminal_id);
    }else
    {
         $bet_history = $bet_history;
    }

    // Fetch the data with pagination
    $perPage = $request->input('perPage', 10);
    $bet_history = $bet_history->orderBy('bets.id', 'desc')->paginate($perPage);

    // Fetch the list of admins
    $users = DB::table('admins')->get();
    return view('admin.bethistory')->with([
        'results' => $bet_history,
        'users' => $users
       
    ]);
}

      public function reset_bethistory(Request $request){
          	$request->session()->forget('result_time');
    		$request->session()->forget('barcode_number');
    		return redirect()->back();
      }
      
      
      
}