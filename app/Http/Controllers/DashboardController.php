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

class DashboardController extends Controller
{
    public function dashboard_a(Request $request){
            date_default_timezone_set('Asia/Kolkata');
            $datetime = date('Y-m-d H:i:s');
            $newdate = date('Y-m-d');
     
             $login_role = $request->login_role??1;
             $role_id = $request->role_id??2;
             
             if($login_role==1||$role_id==1){
                 $bets = DB::table('bets')
                    ->join('admins', 'bets.user_id', '=', 'admins.id')
                    ->whereDate('bets.result_time', $newdate)
                    ->select('bets.*')
                    ->get();
                    $sums = DB::table('bets')
                                ->join('admins as users', 'bets.user_id', '=', 'users.id')
                                ->select(
                                    DB::raw("SUM(CASE WHEN bets.status = 0 THEN bets.total_points ELSE 0 END) as total_pending_points"),
                                    DB::raw("SUM(CASE WHEN bets.status = 1 THEN bets.total_points ELSE 0 END) as total_cancel_points"),
                                    DB::raw("SUM(CASE WHEN bets.status = 2 THEN bets.total_points ELSE 0 END) as total_loss_points"),
                                    DB::raw("SUM(CASE WHEN bets.status = 3 THEN bets.total_points ELSE 0 END) as total_unclaimed_points"),
                                    DB::raw("SUM(CASE WHEN bets.status = 4 THEN bets.total_points ELSE 0 END) as total_claimed_points"),
                                )
                                ->whereDate('bets.created_at', $newdate)
                                ->get();
             }
             
             if($role_id==3){
                 $stokistId = $role_id;
                    // $sums = DB::table('bets')
                    //             ->join('admins as users', 'bets.user_id', '=', 'users.id')
                    //             ->leftJoin('admins as substokists', 'users.inside_substokist', '=', 'substokists.id')
                    //             ->leftJoin('admins as stokists', 'substokists.inside_stokist', '=', 'stokists.id')
                    //             ->select(
                    //                 DB::raw("SUM(CASE WHEN bets.status = 0 THEN bets.total_points ELSE 0 END) as total_pending_points"),
                    //                 DB::raw("SUM(CASE WHEN bets.status = 4 THEN bets.win_points ELSE 0 END) as total_win_points"),
                    //                 DB::raw("SUM(CASE WHEN bets.status = 1 THEN bets.total_points ELSE 0 END) as total_cancel_points")
                    //             )
                    //             ->where(function($query) use ($stokistId) {
                    //                 $query->where('users.inside_stokist', $stokistId)
                    //                       ->orWhere('substokists.inside_stokist', $stokistId);
                    //             })
                    //             ->whereDate('bets.created_at', $currentDate)
                    //             ->get();
                    
                    
                     $sums = DB::table('bets')
                             ->join('admins as users', 'bets.user_id', '=', 'users.id')
                             ->leftJoin('admins as substokists', 'users.inside_substockist', '=', 'substokists.id')
                             ->leftJoin('admins as stokists', 'substokists.inside_stockist', '=', 'stokists.id')
                             ->select(
                                    DB::raw("SUM(CASE WHEN bets.status = 0 THEN bets.total_points ELSE 0 END) as total_pending_points"),
                                    DB::raw("SUM(CASE WHEN bets.status = 1 THEN bets.total_points ELSE 0 END) as total_cancel_points"),
                                    DB::raw("SUM(CASE WHEN bets.status = 2 THEN bets.total_points ELSE 0 END) as total_loss_points"),
                                    DB::raw("SUM(CASE WHEN bets.status = 3 THEN bets.total_points ELSE 0 END) as total_unclaimed_points"),
                                    DB::raw("SUM(CASE WHEN bets.status = 4 THEN bets.total_points ELSE 0 END) as total_claimed_points"),
                                )
                                ->where(function($query) use ($stokistId) {
                                    $query->where('users.inside_stockist', $stokistId)
                                          ->orWhere('substokists.inside_stockist', $stokistId);
                                })
                                ->whereDate('bets.created_at', $newdate)
                                ->get();
                                
                    
                     dd($sums);
                    
             }
             
        
    }
    
    
    
}