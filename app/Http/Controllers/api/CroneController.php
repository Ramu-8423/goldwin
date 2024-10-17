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

class CroneController extends Controller
{
    
    public function result_declare(){
        date_default_timezone_set('Asia/Kolkata');
        $datetime = date('Y-m-d H:i:s');
        $newdate = date('Y-m-d');
        $time = date('H:i:s');
        
        $currentTime = time();
        
      //  $currentTime = strtotime('yesterday 11:30 PM');
       //  dd($timestamp);

          
        $periodStart = $currentTime - ($currentTime % 300); // 300 seconds = 5 minutes
        $resultAnnouncementTime = date('Y-m-d H:i:00', $periodStart + 300);
        $min_time = date('Y-m-d H:i:00', $periodStart);
    
         
         
        $startToday = strtotime('today 23:00'); // 11 PM today
        $startYesterday = strtotime('yesterday 23:00'); // 11 PM yesterday
        $end = strtotime('today 08:55');
            if (($currentTime >= $startYesterday && $currentTime < $end) || ($currentTime >= $startToday && $currentTime < $end + 86400)) {
               return ;
               // dd(['Message' => 'Current time is between 11 PM and 8:50 AM', 'Data' => date('Y-m-d H:i:s', $currentTime)]);
            } else {
              //  dd(['Message' => 'Current time is outside the range', 'Data' => date('Y-m-d H:i:s', $currentTime)]);
            }
        // chek game on off situation - 
        
         $game_setting =  DB::table('game_settings')->where('id',1)->first();
           $winning_percentage = $game_setting->winning_per;
        
        // status - 1 - on // status - 2 - off
        if($game_setting->status==2){
            $result_insert =  DB::table('results')->insert([
                                   'game_name'=>'12 card 5',
                                   'game_id'=>1,
                                   'card_number'=>null,
                                   'result_time'=>$resultAnnouncementTime,
                                   'created_at'=>$datetime
                               ]);
                               
                $update_bet_logs =  DB::table('bet_logs')->update(['amount'=>0]);
                               return ;
        }
        
        // result_type == 1 - manual // 2 == lucky draw // 3 = auto
        // if result is manual than no result will come only admin prediction will come
        // if result is lucky draw than any card can win where bet placed
        // if result is auto then selected percentage will come
        
        //finding the card , which is going to win.
        
         if($game_setting->result_type==1){
             $randomRow = DB::table('admin_results')
                            ->where('result_time', '>', $min_time)
                            ->where('result_time', '<=', $resultAnnouncementTime)
                            ->orderBy('id','desc')
                            ->first();
                    if($randomRow){
                        $card_number = $randomRow->card_number;
                    }else{
                        $card_number = null;   
                    }
              
         }elseif($game_setting->result_type==2){
                 $randomRow = DB::table('bet_logs')
                            ->inRandomOrder()
                            ->whereRaw('amount != 0 OR NOT EXISTS (SELECT 1 FROM bet_logs WHERE amount != 0)')
                            ->first();
                  $card_number = $randomRow->id;          
                            
            }else{
               $amountStats = DB::table('bet_logs')
                              ->selectRaw('SUM(amount) as total_amount')
                              ->first();
                $sum_total_amount  = $amountStats->total_amount;
                $max_winning_amount = ($sum_total_amount*$winning_percentage)/100;
                $randomRow = DB::table('bet_logs')
                                ->inRandomOrder()
                                ->where('amount', '!=', 0)
                                ->whereRaw('(amount / 5) * 50 <= ?', [$max_winning_amount])
                                ->first();

                   if(!$randomRow){
                        $randomRow = DB::table('bet_logs')->inRandomOrder()->where('amount',0)->first();
                         if(!$randomRow){
                             $randomRow = DB::table('bet_logs')
                                                ->whereRaw('(amount / 5) * 50 >= ?', [$max_winning_amount])
                                                ->orderBy('amount', 'ASC')
                                                ->first();

                         }
                   }
                 $card_number = $randomRow->id;
         }
         
     if(!$card_number){
         $card_number = null;
         $result_insert =  DB::table('results')->insert([
           'game_name'=>'12 card 5',
           'game_id'=>1,
           'card_number'=>$card_number,
           'result_time'=>$resultAnnouncementTime,
           'created_at'=>$datetime
           ]);
       $update_bet_logs =  DB::table('bet_logs')->update(['amount'=>0]);
           return;
     }
       
       $result_insert =  DB::table('results')->insert([
           'game_name'=>'12 card 5',
           'game_id'=>1,
           'card_number'=>$card_number,
           'result_time'=>$resultAnnouncementTime,
           'created_at'=>$datetime
           ]);
       
        $bet_his = DB::table('bets')
            ->where('result_time', '>', $min_time)
            ->where('result_time', '<=', $resultAnnouncementTime)
            ->get();
            
          //  dd($bet_his);
      
      if($bet_his->isNotEmpty()){
        foreach($bet_his as $item){
            $id = $item->id;
            $bet_detail = json_decode($item->bet_details);
            
          foreach($bet_detail as $value){
             $card_points = $value->points;
             $bet_card_number = $value->card_number;
             
             $win_points = $card_points*50;
             
            if($bet_card_number == $card_number){
                $update_bet = DB::table('bets')->where('id',$id)->update(['win_points'=>$win_points,'status'=>3]);
                break;
            }else{
                $update_bet = DB::table('bets')->where('id',$id)->update(['status'=>2]);
            }
            
          }
        }
      }
       $update_bet_logs =  DB::table('bet_logs')->update(['amount'=>0]);
    }
    
    public function result_declare_old(){
        date_default_timezone_set('Asia/Kolkata');
        $datetime = date('Y-m-d H:i:s');
        $newdate = date('Y-m-d');
        $time = date('H:i:s');
        
        $currentTime = time();
        
      //  $currentTime = strtotime('yesterday 11:30 PM');
       //  dd($timestamp);

          
        $periodStart = $currentTime - ($currentTime % 300); // 300 seconds = 5 minutes
        $resultAnnouncementTime = date('Y-m-d H:i:00', $periodStart + 300);
        $min_time = date('Y-m-d H:i:00', $periodStart);
    
         
         
        $startToday = strtotime('today 23:00'); // 11 PM today
        $startYesterday = strtotime('yesterday 23:00'); // 11 PM yesterday
        $end = strtotime('today 08:55');
            if (($currentTime >= $startYesterday && $currentTime < $end) || ($currentTime >= $startToday && $currentTime < $end + 86400)) {
               return ;
               // dd(['Message' => 'Current time is between 11 PM and 8:50 AM', 'Data' => date('Y-m-d H:i:s', $currentTime)]);
            } else {
              //  dd(['Message' => 'Current time is outside the range', 'Data' => date('Y-m-d H:i:s', $currentTime)]);
            }
        // chek game on off situation - 
        
         $game_setting =  DB::table('game_settings')->where('id',1)->first();
        
        if($game_setting->status==2){
            $result_insert =  DB::table('results')->insert([
                                   'game_name'=>'12 card 5',
                                   'game_id'=>1,
                                   'card_number'=>null,
                                   'result_time'=>$resultAnnouncementTime,
                                   'created_at'=>$datetime
                               ]);
                               return ;
        }
        
        
        //dd($resultAnnouncementTime,$min_time);
        $given_amount = 10000; 
        $randomRow = null;

        $amountStats = DB::table('bet_logs')
         ->selectRaw('SUM(amount) as total_amount, MIN(amount) as min_amount, MAX(amount) as max_amount')
        ->first();
        
        $sum_total_amount  = $amountStats->total_amount;
        $min_amount = $amountStats->min_amount;
        $max_amount  = $amountStats->max_amount;
        
        $admin_result = DB::table('admin_results')
            ->where('result_time', '>', $min_time)
            ->where('result_time', '<=', $resultAnnouncementTime)
            ->orderBy('id','desc')
            ->first();
            
           
       
       
       if($admin_result){
         $card_number =  $admin_result->card_number;  
       }elseif($sum_total_amount<=$given_amount){
           $randomRow = DB::table('bet_logs')->inRandomOrder()->first();
           $card_number = $randomRow->id;
       }else{
          $randomRow = DB::table('bet_logs')->orderBy('amount', 'asc')->first();
          $amount = $randomRow->amount;
          $randomRow = DB::table('bet_logs')->where('amount','<=',$amount)->inRandomOrder()->first();
          $card_number = $randomRow->id;
       }
       
       $result_insert =  DB::table('results')->insert([
           'game_name'=>'12 card 5',
           'game_id'=>1,
           'card_number'=>$card_number,
           'result_time'=>$resultAnnouncementTime,
           'created_at'=>$datetime
           ]);
       
        $bet_his = DB::table('bets')
            ->where('result_time', '>', $min_time)
            ->where('result_time', '<=', $resultAnnouncementTime)
            ->get();
            
          //  dd($bet_his);
      
      if($bet_his->isNotEmpty()){
        foreach($bet_his as $item){
            $id = $item->id;
            $bet_detail = json_decode($item->bet_details);
            
          foreach($bet_detail as $value){
             $card_points = $value->points;
             $bet_card_number = $value->card_number;
             
             $win_points = $card_points*50;
             
            if($bet_card_number == $card_number){
                $update_bet = DB::table('bets')->where('id',$id)->update(['win_points'=>$win_points,'status'=>3]);
                break;
            }else{
                $update_bet = DB::table('bets')->where('id',$id)->update(['status'=>2]);
            }
            
          }
        }
      }
        
       $update_bet_logs =  DB::table('bet_logs')->update(['amount'=>0]);
    }




    public function update_bet_logs(){
        date_default_timezone_set('Asia/Kolkata');
        $datetime = date('Y-m-d H:i:s');
        $newdate = date('Y-m-d');
        $time = date('H:i:s');
        
        $currentTime = time();
        
        $periodStart = $currentTime - ($currentTime % 300); // 300 seconds = 5 minutes
        $resultAnnouncementTime = date('Y-m-d H:i:00', $periodStart + 300);
        $min_time = date('Y-m-d H:i:00', $periodStart);
        
        $min_current_time = $currentTime - ($currentTime % 300); 
        $current_announce_time = $min_current_time + 300;
        
        $log_min_time = date('Y-m-d H:i:s',$min_current_time);
        $log_announce_time = date('Y-m-d H:i:s',$current_announce_time);
     
        //dd($log_min_time,$log_announce_time);
        
        $result_his = DB::table('bets')->where('result_time','>',$log_min_time)
            ->where('result_time','<=',$log_announce_time)
            ->where('bet_log_status',0)
            ->get();
      
         foreach($result_his as $value){
             $id = $value->id;
          $update_status = DB::table('bets')->where('id',$id)->update(['bet_log_status'=>1]);
          $bet_details_array = json_decode($value->bet_details);
              foreach($bet_details_array as $item){
                    $point = $item->points;
                    $point_value = $point*5;
                    $card_number = $item->card_number;
                    
                     DB::table('bet_logs')->where('id',$card_number)->update([
                        'amount'=>DB::raw("amount + $point_value")
                        ]);
                }
        }
    }






}