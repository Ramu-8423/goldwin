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
use DateTime;



class ReportController extends Controller
{
    
    public function report(Request $request){
        
            date_default_timezone_set('Asia/Kolkata');
            $datetime = date('Y-m-d H:i:s');
            $newdate = date('Y-m-d');
           $time = date('h:i:s A');
            
            $validator = Validator::make($request->all(), [
              'from' => ['required', 'date_format:Y-m-d'],
              'to' =>['required', 'date_format:Y-m-d'],
              'user_id'=>'required|exists:admins,id'
            ]);
        
            $validator->stopOnFirstFailure();
        
            if($validator->fails()){
                return response()->json(['status' => 200, 'message' => $validator->errors()->first()]);
            }

            $from = $request->from;
            $to = $request->to;
            $user_id =  $request->user_id;// $request->user_id;
            //$report_data = DB::table('bets')->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)->get();
            
            $report_data = DB::table('bets')
                    ->select(
                        DB::raw('COALESCE(SUM(total_points), 0) as total_points'),
                        DB::raw('COALESCE(SUM(CASE WHEN status = 1 THEN total_points ELSE 0 END), 0) as cancel_points'),
                        DB::raw('COALESCE(SUM(CASE WHEN status = 4 THEN win_points ELSE 0 END), 0) as claimed_points'),
                        DB::raw('COALESCE(SUM(CASE WHEN status = 3 THEN win_points ELSE 0 END), 0) as unclaimed_points')
                    )
                    ->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to)
                    ->where('user_id',$user_id)
                    ->first();

                if($report_data){
                 $play_points = $report_data->total_points;
                 $cancel_points = $report_data->cancel_points;
                 $claimed_points = $report_data->claimed_points;
               
                 $net_play_points = $play_points - $cancel_points;
                 $opt_play_points = $net_play_points - $claimed_points;
                
                 $discounted_points = $play_points*0.09;
                 $gross_points = $opt_play_points -  $discounted_points;
                 $bonus_points = 0;
                 $gift_points = 0;
                 $net_pay_points = $gross_points -$bonus_points-$gift_points;
                }else{
                    $play_points = 0;
                    $cancel_points = 0;
                    $claimed_points = 0;
                    $net_play_points = 0;
                    $opt_play_points =0;
                    $discounted_points = 0;
                    $gross_points = 0;
                    $bonus_points = 0;
                    $gift_points =  0;
                    $net_pay_points=0;
                }
               
               $current_report = DB::table('admins')->where('id',$user_id)->first();
               
               if($current_report){
                     $open_points = $current_report->day_wallet;
                    $add_points =$current_report->today_add_money;
                   $current_points = $current_report->wallet;
               }else{
                    $open_points = 0;
                   $current_points = 0;
                   $add_points = 0;
               }
               
                  $total_points = $open_points + $add_points;
                  $used_points = $net_pay_points<0?0:$net_pay_points;
                  
                  
                  $current_points = $total_points - $net_pay_points;
                
             
            $report = [
                'from'=>$from,
                'to'=>$to,
                'play_points'=>(string)$play_points,
                'cancel_points'=>(string)$cancel_points,
                'net_play_points'=>$net_play_points,
                'claim_points'=>(string)$claimed_points,
                'opt_play_points'=>$opt_play_points,
                'discount_points'=>$discounted_points,
                'gross_points'=>$gross_points,
                'bonus_points'=>$bonus_points,
                'gift_points'=>$gift_points,
                'net_pay_points'=>$net_pay_points,
                'open_points'=>$open_points,
                'add_points'=>$add_points,
                'total_points'=>$total_points,
                'used_points'=>$used_points,
                'current_points'=>$current_points,
                'current_time'=>$time,
                'current_date'=>$newdate
                ];
         
            
            if($report_data){
            return response()->json(['status'=>200,'message'=>'Record found.','report'=>$report]);
            }else{
                return response()->json(['status'=>400,'message'=>'No record found!','report'=>[]]);
            }
        
    }
    
}