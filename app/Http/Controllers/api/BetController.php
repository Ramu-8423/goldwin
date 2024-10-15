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

class BetController extends Controller
{
    
    
    public function test(){
        $game_settings = 1234; 
    }
    
            
            protected function generateBarCodeNumber($column_name){
                do {
                    $barcodeNumber = mt_rand(100000, 999999);
                    $exists = DB::table('bets')->where($column_name,$barcodeNumber)->exists();
                } while ($exists); 
                return $barcodeNumber;
            }



    
        public function bet(Request $request){
            
            date_default_timezone_set('Asia/Kolkata');
            $datetime = date('Y-m-d H:i:s');
            $newdate = date('Y-m-d');
            $currentTime = time();
            
            $status = 0;
            
            $validator = Validator::make($request->all(), [
               'user_id'=>'required|exists:admins,id',
              'time' => ['required', 'date_format:H:i:s'],
              'quantity' => 'required|integer',
              'total_points' => 'required|integer',
              'game_name' => 'required|string',
              'bet_details' => 'required',
            ]);
            //user_id field will be taken also but currently using static value
        
            $validator->stopOnFirstFailure();
        
            if($validator->fails()){
                return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
            }
            
            $user_id =$request->user_id;
            $time = $request->time;
            $formattedTime = str_replace(":", "", $time);
            $result_announce_time = $newdate.' '.$time;
          
            //2024-07-06 12:59:04
           // dd($result_announce_time,$datetime);
           
          // $current_min_time = $currentTime - ($currentTime % 300); // 300 seconds = 5 minutes
          // $current_max_time = date('Y-m-d H:i:00', $periodStart + 300);
              
             $bet_result_announce_time = strtotime($result_announce_time);
             $current_min_time = $currentTime - ($currentTime % 300);
             $current_announce_time = $current_min_time + 300;
           
               if($bet_result_announce_time<=$current_announce_time){
                   $status = 1;
               }
            
            $quantity = $request->quantity;
            $total_points = $request->total_points;
            $game_name = $request->game_name;
            $bet_detail = $request->bet_details;
            $bet_details = base64_decode($bet_detail);
            $bet_details_array = json_decode($bet_details);
            
           $barcode_number = $this->generateBarCodeNumber('barcode_number');
           $order_id = $this->generateBarCodeNumber('order_id');


            $wallet = DB::table('admins')->where('id',$user_id)->value('wallet');
            
            if($wallet<$total_points){
                return response()->json(['status'=>400,'message'=>'Insufficient funds!']);
            }
        
            $update_wallet = DB::table('admins')->where('id',$user_id)->update([
                'wallet'=>DB::raw("wallet - $total_points")
                ]);
                
            // $insert_bet = DB::table('bets')->insert([
            //     'result_time'=>$result_announce_time,
            //     'quantity'=>$quantity,
            //     'total_points'=>$total_points,
            //     'game_name' => $game_name,
            //     'bet_details'=>$bet_details,
            //     'created_at' =>$datetime,
            //     'treminal_id'=>7711010603,
            //     'barcode_number' =>$barcode_number,
            //     'order_id' =>$order_id
            //     ]);   
            
            
            
           $insert_bet = DB::table('bets')->insertGetId([
                    'user_id'=>$user_id,
                    'result_time' => $result_announce_time,
                    'quantity' => $quantity,
                    'total_points' => $total_points,
                    'game_name' => $game_name,
                    'bet_details' => $bet_details,
                    'created_at' => $datetime,
                    'treminal_id' => 7711010603,
                    'barcode_number' => $barcode_number,
                    'bet_log_status'=>$status,
                    'order_id' => $order_id
                ]);
                
                if($status==1){
                foreach($bet_details_array as $item){
                    $point = $item->points;
                    $point_value = $point*50;
                    $card_number = $item->card_number;
                    
                     DB::table('bet_logs')->where('id',$card_number)->update([
                        'amount'=>DB::raw("amount + $point_value")
                        ]);
                }
                }
                
               // [{"points": "5","card_number": "3"}, {"points": "3","card_number": "2"}]
    
                if($insert_bet){
                    return response()->json(['status'=>200,'message'=>'Bet placed successfully.','id'=>$insert_bet]);
                }else{
                     return response()->json(['status'=>400,'message'=>'Failed to place bet!']);
                }
            
        }
        
        
        
            public function cancel_bet(Request $request){
                
                $validator = Validator::make($request->all(), [
        			'id' => 'required|exists:bets,id',
        			'user_id'=>'required|exists:bets,user_id',
        		]);

	        	$validator->stopOnFirstFailure();
        
        		if ($validator->fails()) {
        			return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        		}
        		
        		
        		$id = $request->id;
        		$user_id = $request->user_id;
        		
             	$cancel_ticket_num = DB::table('admins')->where('id',$user_id)->value('today_cancel_ticket');
             	 if($cancel_ticket_num>=10){
             	     return response()->json([ 'status'=>200, 'message'=>'limit exhaust,Maximum 10 tickets can be cancel in a day']);
             	 }
             	     
        		$bet_details = DB::table('bets')->where('id',$id)->where('user_id',$user_id)->first();
        		$status = $bet_details->status;
        		
        		if($status == 0){
        		    $total_points = $bet_details->total_points;
        		    
        		    $wallet_update =  DB::table('admins')->where('id',$user_id)->update([
        		      'wallet'=>DB::raw("wallet + $total_points")
        		      ]);
        		    
        		    if($wallet_update){
        		        
        		    $change_status = DB::table('bets')->where('id',$id)->where('user_id',$user_id)->update([
        		       'status'=>1
        		       ]); 
        		        if($change_status){
        		            DB::table('admins')->where('id',$user_id)->update([ 'today_cancel_ticket'=>DB::raw("today_cancel_ticket + 1")]);
        		            return response()->json(['status'=>200,'message'=>'Bet canceled successfully.']);
        		        }else{
        		             return response()->json(['status'=>400,'message'=>'Falied to update bet status!']);
        		        }
        		        
        		    }else{
        		        return response()->json(['status'=>400,'message'=>'Falied to update wallet!']);
        		    }
        		    
        		    
        		    
        		 }else if($status == 1){
        		     return response()->json(['status'=>400,'message'=>'Bet Alredy Cancelled!']);
        	   	}else if($status == 4){
        	   	     return response()->json(['status'=>400,'message'=>'Bet Alredy Claimed!']);
        	   	 }else{
        		     return response()->json(['status'=>400,'message'=>'Cancelation Time Is Over!']);
        		}
            }
            
            
             public function claim_bet(Request $request){
                 
                $validator = Validator::make($request->all(), [
        			'id' => 'required|exists:bets,id',
        			'user_id'=> 'required|exists:bets,user_id',
        		]);
                
	        	$validator->stopOnFirstFailure();
        
        		if ($validator->fails()) {
        			return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        		}
        		
        		$id = $request->id;
        		$user_id = $request->user_id;
        		
        		$bet_details = DB::table('bets')->where('id',$id)->where('user_id',$user_id)->first();
        		
        		$status = $bet_details->status;
        		
        		
        		if($status == 3){
        		     $total_points = $bet_details->total_points;
        		     
        		     $wallet_update =  DB::table('admins')->where('id',$user_id)->update([
        		      'wallet'=>DB::raw("wallet + $total_points")
        		      ]);
        		    
        		    if($wallet_update){
        		        
        		    $change_status = DB::table('bets')->where('id',$id)->where('user_id',$user_id)->where('status',3)->update([
        		       'status'=>4
        		       ]); 
        		        if($change_status){
        		            return response()->json(['status'=>200,'message'=>'Bet claimed successfully.']);
        		        }else{
        		             return response()->json(['status'=>400,'message'=>'Falied to update bet status!']);
        		        }
        		        
        		    }else{
        		        return response()->json(['status'=>400,'message'=>'Falied to update wallet!']);
        		    }
        		    
        		    
        		}else if($status == 1 || $status == 2){
        		    return response()->json(['status'=>400,'message'=>'Not a winning ticket!']);
        		}else if($status == 4){
        		    return response()->json(['status'=>400,'message'=>'This ticket has been claimed!']);
        		}else if($status == 0){
        		    return response()->json(['status'=>400,'message'=>'Result is pending!']);
        		}else{
        		   return response()->json(['status'=>400,'message'=>'Not a valid request!']);
        		}
        		
             }
             
              public function all_claim_bet(? string $user_id){
                     
                     $validator = Validator::make(['user_id'=>$user_id],[
                         'user_id'=>'required|exists:bets,user_id'
                         ]);
                     $validator->stopOnFirstFailure();
                     if($validator->fails()){
                    	return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
                     }

                   date_default_timezone_set('Asia/Kolkata');
                   $datetime = date('Y-m-d H:i:s');
                   $newdate = date('Y-m-d');

                  $bet_details = DB::table('bets')->where('user_id',$user_id)->whereDate('created_at',$newdate)->where('status',3)->get();
                   
                   if($bet_details->isEmpty()){
                       return response()->json(['status'=>400,'message'=>'No Bet to claim!']);
                   }  
                
                foreach($bet_details as $item){
                    $id = $item->id;
                    $total_points = $item->total_points;
        		     
        		     $wallet_update =  DB::table('admins')->where('id',$user_id)->update([
        		      'wallet'=>DB::raw("wallet + $total_points")
        		      ]);

        		    $change_status = DB::table('bets')->where('id',$id)->where('status',3)->update([
        		       'status'=>4
        		       ]); 

                }
                
                return response()->json(['status'=>200,'message'=>'All bet claimed successfully.']);
                
              }
             
             
              public function fetch_data(Request $request){
                  /*if custom date time is selected then send that time in $resultAnnouncementTime (after calculating result time) so that time will set in input box and after clicking on submit
                  button that time will be submitted for prediction, this vale will not lost because page is not refereshing, 
                  if page is refreshed manually then current time will automatically taken  */
                  /*
                    now, if custom time is selected,means $custom_date_time is not empty then calculate it by bet table using result_time column and return  all amount on betlog parametr. 
                    and if $custom_date_time is empty then it will caluclate automatically from bet log table.
                    $custom_date_time  is sending from predictionnpage using js, it is sending input box value, if value is set then value other wise empty you will get
                  */
                  
                date_default_timezone_set('Asia/Kolkata');
                $datetime = date('Y-m-d H:i:s');
                $currentTime = time();
                $periodStart = $currentTime - ($currentTime % 300); // 300 seconds = 5 minutes
                $resultAnnouncementTime = date('Y-m-d H:i:00', $periodStart + 300); // this time is for next prediction, appending in input box and after processing by form submission
                    
                $bet_log = DB::table('bet_logs')->get();
                 
                $custom_date_time = $request->custom_date_time; 
            
                if($custom_date_time){
                    $modify_date_time = date('Y-m-d H:i:s', strtotime($custom_date_time)); // because it is coming like this - 2024-10-10T12:12 due to input box datetime-local formate
                    $unix_modify_time = strtotime($modify_date_time);
                    $adjustment = $unix_modify_time % 300;
                    if($adjustment==0){
                        $min_modify_time = date('Y-m-d H:i:s', $unix_modify_time - $adjustment);
                        $max_modify_time = $min_modify_time;
                        $custom_bet = DB::table('bets')->where('result_time','=',$min_modify_time)->get();
                        
                    }else{
                        $min_modify_time = date('Y-m-d H:i:s', $unix_modify_time - $adjustment);
                        $max_modify_time = date('Y-m-d H:i:s', $unix_modify_time + (300 - $adjustment));
                        $custom_bet = DB::table('bets')->where('result_time','>',$min_modify_time)->where('result_time','<=',$max_modify_time)->get();
                        
                    }
                    $resultAnnouncementTime = $max_modify_time;
            
                    $a = ['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'9'=>0,'10'=>0,'11'=>0,'12'=>0];

                      foreach($custom_bet as $item =>$value){
                          $bet_details = $value->bet_details;
                          $details = json_decode($bet_details);
                          foreach($details as $value){
                             $a[$value->card_number] = $a[$value->card_number] + (int)$value->points*50;
                          }
                      }
                      $bet_log = [];
                     foreach($a as $value){
                       $bet_log[] = (object)["amount"=>$value];
                     }   
                    
                   return response()->json(['status'=>200,'bet_log'=>$bet_log,'result_time'=>$resultAnnouncementTime]);
                }
                
               return response()->json(['status'=>200,'bet_log'=>$bet_log,'result_time'=>$resultAnnouncementTime]);
            }
             
             
             public function point_details(Request $request){
                    date_default_timezone_set('Asia/Kolkata');
                    $datetime = date('Y-m-d H:i:s');
                 
                     $result_time =$request->result_time;
                     $card_number = (string)$request->card_number;
                     $uinx_result_time = strtotime($result_time);
                     $adjustment = $uinx_result_time % 300;
                 
                    if($adjustment==0){
                         $min_result_time = date('Y-m-d H:i:s', $uinx_result_time - $adjustment);
                         $max_result_time = $min_result_time;
                          $bet_history = DB::table('bets')
                            ->join('admins', 'bets.user_id', '=', 'admins.id') // Join the main admin
                            ->leftJoin('admins as stockist_admin', 'admins.inside_stockist', '=', 'stockist_admin.id') // Join for stockist
                            ->leftJoin('admins as substockist_admin', 'admins.inside_substockist', '=', 'substockist_admin.id') // Join for substockist
                            ->select(
                                'stockist_admin.terminal_id as stockist_ter_id',
                                'substockist_admin.terminal_id as substockist_ter_id',
                                'admins.terminal_id as user_ter_id',
                                'bets.win_points as win_points',
                                'bets.status as bet_status',
                                'bets.created_at as bet_time',
                                'bets.result_time as result_time',
                                'bets.bet_details as bet_details'
                                )
                            ->where('bets.result_time', '=', $max_result_time)
                            ->whereJsonContains('bets.bet_details', ['card_number' => $card_number])
                           ->get();
                    }else{
                        $min_result_time = date('Y-m-d H:i:s', $uinx_result_time - $adjustment);
                        $max_result_time = date('Y-m-d H:i:s', $uinx_result_time + (300 - $adjustment));
                       $custom_bet = DB::table('bets')->where('result_time','>',$min_result_time)->where('result_time','<=',$max_result_time)->get();
                        $bet_history = DB::table('bets')
                            ->join('admins', 'bets.user_id', '=', 'admins.id') // Join the main admin
                            ->leftJoin('admins as stockist_admin', 'admins.inside_stockist', '=', 'stockist_admin.id') // Join for stockist
                            ->leftJoin('admins as substockist_admin', 'admins.inside_substockist', '=', 'substockist_admin.id') // Join for substockist
                            ->select(
                                'stockist_admin.terminal_id as stockist_ter_id',
                                'substockist_admin.terminal_id as substockist_ter_id',
                                'admins.terminal_id as user_ter_id',
                                'bets.win_points as win_points',
                                'bets.status as bet_status',
                                'bets.created_at as bet_time',
                                'bets.result_time as result_time',
                                'bets.bet_details as bet_details'
                                )
                            ->where('result_time','>',$min_result_time)
                             ->where('result_time','<=',$max_result_time)
                            ->whereJsonContains('bets.bet_details', ['card_number' => $card_number])
                           ->get();
                    }
                    
                $card = [ '1' => "JC", '2' => "JD", '3' => "JS", '4' => "JH", '5' => "QC", '6' => "QD",'7' => "QS", '8' => "QH", '9' => "KC", '10' => "KD", '11' => "KS", '12' => "KH"];
               $modified_result = [];
               $card_name = $card[$card_number];
              foreach($bet_history as $item=>$value){
                    $result_card = json_decode($value->bet_details);
                          foreach($result_card as $a =>$b){
                              if($b->card_number==$card_number){
                                  $selected_card_number = $b->card_number;
                                  $selected_points = $b->points;
                                  break;
                              }
                          }
         $bet_status = $value->bet_status==0?'Pending':($value->bet_status==1?'Cancel':($value->bet_status==2?'Loss':($value->bet_status==3?'PRZ':($value->bet_status==4?'Claimed':''))));
                          
              $modified_result[] = (object)[
                                             'user_ter_id'=>$value->user_ter_id,
                                             'stockist_ter_id'=>$value->stockist_ter_id,
                                             'substockist_ter_id'=>$value->substockist_ter_id,
                                              'total_card'=>$selected_points,
                                              'purchase_points'=>$selected_points*5,
                                              'card_name'=>$card_name,
                                               'win_points'=>$value->win_points,
                                              'bet_status'=>$bet_status,
                                              'bet_time'=>$value->bet_time,
                                               'result_time'=>$value->result_time,
                                            // 'card_number'=>$selected_card_number,
                                         ];
                               }
                    return response()->json(['status'=>200,'point_details'=>$modified_result]);
             }
             
           

    
}