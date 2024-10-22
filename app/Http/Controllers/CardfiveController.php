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
    //dd($request->all());
    date_default_timezone_set('Asia/Kolkata');
    $authid = session('id');
    $authdata = DB::table('admins')->where('id', $authid)->first();
    $authrole = $authdata->role_id;
    $strole_id = null;
    $sbrole_id = null;
    $selected_role_id = null;
    $use_terminal_id = null;
    $stokistid = null;
    $sub_stokistid = null;
    $date = null;
    $start_date =null;
    $end_date=null;
    $barcode=null;
    $bet_status=null;
    if ($request->st_terminal_id) {
        $stokist = DB::table('admins')->where('terminal_id', $request->st_terminal_id)->first();
        $stokistid = $stokist->id;
        $strole_id = 2;
        $selected_role_id = 2;
    }

    if ($request->sub_terminal_id) {
        
        $sub_stokistid = $request->sub_terminal_id;  //actually it is id of that terminal id
        $sbrole_id = 3;
        $selected_role_id = 3;
    }
    
    if($request->use_terminal_id){
        $user_id = DB::table('admins')->where('terminal_id', $request->use_terminal_id)->value('id');
        $selected_role_id = 4;
       
    }
    if($request->start_date && $request->end_date){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
       
    }
     if($request->barcode ){
        $barcode = $request->barcode;
       // dd($barcode);
       
    }
    

  $bet_history = DB::table('bets')
        ->leftJoin('admins', 'bets.user_id', '=', 'admins.id')
        ->select(
            'bets.*',
            'bets.barcode_number',
            'admins.role_id as admin_role_id',
            'admins.terminal_id as admin_terminal_id',
            'admins.status as admin_status',
            'admins.wallet as admin_wallet',
            'admins.day_wallet as admin_day_wallet',
            'admins.inside_stockist as inside_stockist',
            'admins.inside_substockist as inside_substockist',
        );

    // Filter based on authrole
    if ($authrole == 2) {
        $bet_history = $bet_history->where('admins.inside_stockist', $authid);
    }
    
        if ($authrole == 3) {
            $bet_history = $bet_history->where('admins.inside_substockist', $authid);
       }

    // Existing filters
        if ($selected_role_id == 2) {
            $bet_history = $bet_history->where('admins.inside_stockist', $stokistid);
            // ->where('admins.inside_substockist',null);
       }elseif($selected_role_id == 3){
              $bet_history = $bet_history->where('admins.inside_substockist', $sub_stokistid);
      }elseif($selected_role_id==4){
           $bet_history = $bet_history->where('admins.id',$user_id);
      }
      if(is_numeric($request->bet_status))
        {
          $bet_history = $bet_history->where('bets.status',$request->bet_status);
        }

      if($barcode){
     $bet_history = $bet_history->where('bets.barcode_number',$barcode);
    }
    if($start_date && $end_date){
        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:59';
     $bet_history = $bet_history->whereBetween('bets.result_time', [$start_date, $end_date]);
    }
   
    $perPage = $request->input('perPage', 150);
    $bet_history = $bet_history->orderBy('bets.id', 'desc')->paginate($perPage);
    // for the depend dropdown
        $admins = DB::table('admins')
        ->leftjoin('admins as admin_stockist','admins.inside_stockist','=','admin_stockist.id')
        ->leftjoin('admins as admin_substockist','admins.inside_substockist','=','admin_substockist.id')
        ->select(
        'admins.terminal_id as terminal_id',
        'admins.role_id as adminrole_id',
        'admins.id as adminid',
        );
    if ($authrole == 2) {
        $admins = $admins->where('admins.inside_stockist', $authid);
    }
    if ($authrole == 3) {
        $admins = $admins->where('admins.inside_substockist', $authid);
    }
    if ($request->has('terminal_id') && !empty($request->terminal_id)) {
        $admins = $admins->where('admins.terminal_id', $request->terminal_id);
    }
    // Fetching the data
    $admins = $admins->orderBy('admins.id', 'desc')->get();
    // for the depend dropdown end
    // Fetch the list of admins
    $users = $admins;
    return view('admin.bethistory')->with([
        'results' => $bet_history,
        'users' => $users,
        'authrole' => $authrole
    ]);
}




     public function reset_bethistory(Request $request){
         	$request->session()->forget('result_time');
		$request->session()->forget('barcode_number');
		return redirect()->back();
     }
    public function getStockistSubordinates($stockist_terminal_id) {
    $stokist = DB::table('admins')->where('terminal_id', $stockist_terminal_id)->first();
    $stokistid = $stokist->id;
    $substockists = Admin::where('inside_stockist', $stokistid)
                         ->where('role_id', 3)
                         ->get();
    return response()->json($substockists);
}
      public function getSubstockistUsers($substockist_terminal_id) {
           $authid = session('id');
           $authdata = DB::table('admins')->where('id', $authid)->first();
           $authrole = $authdata->role_id;
           if($authrole ==1)
           {
               $users = Admin::where('inside_substockist', $substockist_terminal_id)
                  ->where('role_id', 4)
                  ->get(); 
           }
           elseif($authrole ==2){
                  $substokists = DB::table('admins')->where('id', $substockist_terminal_id)->first();
                 $stokistid = $substokists->id;
                  $users = Admin::where('inside_substockist', $stokistid)
                  ->where('role_id', 4)
                  ->get();
           }
    return response()->json($users);
}

public function calculation(Request  $request){
     date_default_timezone_set('Asia/Kolkata');
    //Dependent dropdowns banane ke liye  
    $authid = session('id');
    $authdata = DB::table('admins')->where('id', $authid)->first();
    $authrole = $authdata->role_id;
       $admins = DB::table('admins')
       ->leftjoin('admins as admin_stockist','admins.inside_stockist','=','admin_stockist.id')
       ->leftjoin('admins as admin_substockist','admins.inside_substockist','=','admin_substockist.id')
       ->select(
       'admins.terminal_id as terminal_id',
       'admins.role_id as adminrole_id',
       'admins.id as adminid',
        );
    if ($authrole == 2) {
        $admins = $admins->where('admins.inside_stockist', $authid);
    }
    if ($authrole == 3) {
        $admins = $admins->where('admins.inside_substockist', $authid);
    }
    $admins = $admins->orderBy('admins.id', 'desc')->get();
                     //Dependent dropdowns end code 
                    //For request data who submited by blade tamplet end code 
                     $inside_stockist = null;
                     $inside_substockist = null;
                     $user_id = null;
                     $all_user_search_id = null;
                     $selected_role_id = null;
                     $start_date =null;
                     $end_date=null;
                     $bet_status=null;
                     
                         if ($request->st_terminal_id) {
                             $inside_stockist = DB::table('admins')->where('terminal_id', $request->st_terminal_id)->value('id');
                             $selected_role_id =2;
                         }
                         if ($request->sub_terminal_id) {
                             $inside_substockist = $request->sub_terminal_id;  //actually it is id of that terminal id
                              $selected_role_id =3;
                         }
                         if($request->use_terminal_id){
                             $user_id = DB::table('admins')->where('terminal_id', $request->use_terminal_id)->value('id');
                              $selected_role_id =4;
                         }
                          if($request->all_user_search_id){
                             $all_user_search_id = DB::table('admins')->where('terminal_id', $request->all_user_search_id)->value('id');
                         }
                         if($request->start_date && $request->end_date){
                             $start_date = $request->start_date;
                             $end_date = $request->end_date;
                         }
                         
                        //  if ($request->bet_status) {
                        //  $bet_status = $request->bet_status;
                        //  }
             // $query = DB::table('bets')
             
             $query = DB::table('bets')
                ->leftJoin('admins', 'bets.user_id', '=', 'admins.id')
                ->select(
                    'bets.*',
                    'admins.role_id as admin_role_id',
                    'admins.terminal_id as admin_terminal_id',
                    'admins.status as admin_status',
                    'admins.wallet as admin_wallet',
                    'admins.day_wallet as admin_day_wallet',
                    'admins.inside_stockist as inside_stockist',
                    'admins.inside_substockist as inside_substockist',
                );
            $sum = DB::table('bets')
            ->select(
                DB::raw('SUM(CASE WHEN bets.status >= 0 THEN bets.total_points ELSE 0 END) AS total_bet_points'),
                DB::raw('SUM(CASE WHEN bets.status = 0 THEN bets.total_points ELSE 0 END) AS total_pending_points'),
                DB::raw('SUM(CASE WHEN bets.status = 1 THEN bets.total_points ELSE 0 END) AS total_cancel_points'),
                DB::raw('SUM(CASE WHEN bets.status = 2 THEN bets.total_points ELSE 0 END) AS total_loss_points'),
                DB::raw('SUM(CASE WHEN bets.status = 3 THEN bets.win_points ELSE 0 END) AS total_unclaimed_points'),
                DB::raw('SUM(CASE WHEN bets.status = 4 THEN bets.win_points ELSE 0 END) AS total_claimed_points')
            )
            ->leftJoin('admins', 'bets.user_id', '=', 'admins.id');
                     if($authrole ==2)
                     {
                         $results = $query->where('admins.inside_stockist', $authid);
                         $sumresults = $sum->where('admins.inside_stockist', $authid);
                     }
                     if($authrole ==3)
                     {
                         $results = $query->where('admins.inside_substockist', $authid);
                         $sumresults = $sum->where('admins.inside_substockist', $authid);
                     }
                     if($all_user_search_id)
                     {
                         $results = $query->where('admins.id', $all_user_search_id);
                         $sumresults = $sum->where('admins.id', $all_user_search_id);
                     }
                     if ($start_date && $end_date){
                         $start_date = $start_date . ' 00:00:00';
                         $end_date = $end_date . ' 23:59:59';
                         $results = $query->whereBetween('bets.result_time', [$start_date, $end_date]);
                         $sumresults = $sum->whereBetween('bets.result_time', [$start_date, $end_date]);
                         }else{
                         $date = date('Y-m-d');
                         $results = $query->whereDate('bets.result_time','=', $date);
                         $sumresults = $sum->whereDate('bets.result_time','=', $date);
                         }
                        if($selected_role_id == 2) {
                            $results = $query->where('admins.inside_stockist', $inside_stockist);
                            $sumresults = $sum->where('admins.inside_stockist', $inside_stockist);
                           }
                       elseif($selected_role_id == 3){
                            $results = $query->where('admins.inside_substockist', $inside_substockist);   
                            $sumresults = $sum->where('admins.inside_substockist', $inside_substockist);   
                           }
                       elseif($selected_role_id==4){
                                 $results = $query->where('admins.id',$user_id);
                                 $sumresults = $sum->where('admins.id',$user_id);
                           }
                           if(is_numeric($request->bet_status))
                           {
                                $results = $query->where('bets.status',$request->bet_status);
                           }
                          
                        $results = $query->get();
                        $sumresults = $sum->first();
                        return view('admin.calculation')->with([
                              'authrole' => $authrole,
                              'users' => $admins,
                              'sumresults' => $sumresults,
                              'results' => $results
                            ]);
        
        
    }

}