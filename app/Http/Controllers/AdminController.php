<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    
    
    
    public function add_role(Request $request){
        $login_id = session('id');
        $login_role_id = session('role_id');
        $terminal_id = $request->terminal_id;
        $role_id = $request->role_id;
        $password = $request->password;
        $under_role_terminal_id = $request->under_role_terminal_id;
        
        $chek = DB::table('admins')->where('terminal_id',$terminal_id)->first();
        if($chek){
          return redirect()->back()->with('error','Key already exist..');
        }
       
       $inside_stockist = null;
       $inside_substockist = null;
       if($role_id==3){
           //creating substockisit, so inside_substockist = null;
           $inside_stockist = DB::table('admins')->where('terminal_id',$under_role_terminal_id)->value('id');
       }else{
           $row = DB::table('admins')->where('terminal_id',$under_role_terminal_id)->first();
           if($row->role_id==2){
               $inside_stockist = $row->terminal_id;
           }else{
               $inside_stockist = $row->inside_stockist;
               $inside_substockist = $row->id;
           }
           
       }
       
       $insert = DB::table('admins')->insert([
                         'role_id'=>$role_id,
                         'inside_stockist'=>$inside_stockist,
                         'inside_substockist'=>$inside_substockist,
                         'terminal_id'=>$terminal_id,
                         'password'=>$password,
                         'created_by'=>$login_id,
                         'status'=>2
                ]);
                
                if($insert){
                    return redirect()->back()->with('success','Created successfully..');
                }else{
                    return redirect()->back()->with('error','Failed to create..');
                }
       
    }
    
    public function create_role(){
         $login_id = session('id');
        $login_role_id = session('role_id');
        return view('create_role')->with('login_id',$login_id)->with('login_role_id',$login_role_id);
    }
    
    public function create_role_code(Request $request){
         $login_id = session('id');
         $login_role = session('role_id');
         $selected_role = $request->role_id;

         $login_details = DB::table('admins')->where('id',$login_id)->first();
         
         if($login_role==1){
             if($selected_role==2){
                 $list = [(object)[
                       'id'=>$login_id,
                       'role_id'=>$login_role,
                       'terminal_id'=>$login_details->terminal_id
                     ]];
                     //this is because stockist will create inside admin only
             }elseif($selected_role==3){
                $list =  DB::table('admins')->select('id','role_id','terminal_id')->where('role_id',2)->get(); 
             }else{
                 $list =  DB::table('admins')->select('id','role_id','terminal_id')->whereIn('role_id',[2,3])->get(); 
             }
         }elseif($login_role==2){
             // this is a sotockist and it can create only substockist and users
              $a = (object)[
                                   'id'=>$login_id,
                                   'role_id'=>$login_role,
                                   'terminal_id'=>$login_details->terminal_id
                                ];
             if($selected_role==3){
                 $list = [$a];
             }else{
                  $list =  DB::table('admins')->select('id','role_id','terminal_id')->where('role_id',3)->where('inside_stockist',$login_id)->get(); 
                  $list->prepend($a);
             }
             
         }elseif($login_role==3){
              $list = [(object)[
                                   'id'=>$login_id,
                                   'role_id'=>$login_role,
                                   'terminal_id'=>$login_details->terminal_id
                                ]];
         }
         
       return response()->json([
               'status'=>200,
               'data'=>$list
           ]);
    }
    
    
    public function login_page(){
        return view('admin.login');
    }
    public function login(Request $request){
            $validator = Validator::make($request->all(), [
            'terminal_id' => 'required',
            'password' => 'required'
        ]);
		   
        $validator->stopOnFirstFailure();
        if($validator->fails()) {
            return redirect()->route('login_page')->with('error',$validator->errors()->first());
        }
       
             $login = DB::table('admins')
           ->where('terminal_id', $request->terminal_id)
           ->where('password', $request->password)
           ->first();
       
       if($login){ 
           $request->session()->put('id', $login->id);
           $request->session()->put('role_id', $login->role_id); 
           $request->session()->put('status', $login->status); 
           $status = Session::get('status');
           if($status == 0)
           {
             return redirect()->route('login_page')->with('error','Inactive account. Please reach out to admin');
           }
           Session::put('Auth_id', $login->id);
           $role = Session::get('role_id');
            $request->session()->put('terminal_id', $login->terminal_id); 
           return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('login_page')->with('error','Invalid Credentials');
        }
    }
        public function dashboard(){
        return view('admin.index');
    }
     public function logout(Request $request){
         $request->session()->forget('id');
        return redirect()->route('login_page');
    }
         public function cardfive(Request $request){
           return view('prediction.12card5');
          }
    
     public function password(){
           return view('admin.password');
         }
         
    public function update_password(Request $request){
             $validator = Validator::make($request->all(), [
            'terminal_id' => 'required',
            'password' => 'required',
            'new_password'=>'required'
        ]);
        $validator->stopOnFirstFailure();
        if ($validator->fails()) {
            return redirect()->route('admin.password')->with('error',$validator->errors()->first());
        }
        
        $id = session('id');
        
         $update_pass = DB::table('admins')->where('id',$id)->where('terminal_id',$request->terminal_id)->where('password',$request->password)->first();
        if($update_pass){
         $update_pass = DB::table('admins')->where('id',$id)->where('terminal_id',$request->terminal_id)->update([
             'password'=>$request->new_password
             ]);
             if($update_pass){
                 return redirect()->back()->with('success','Password updated successfully.');
             }else{
                  return redirect()->route('admin.password')->with('error','Failed to update password!');
             }
        }else{
            return redirect()->route('admin.password')->with('error','Invalid Credentials');
        }
         }
         
        public function wallet(){
            $wallet = DB::table('admins')->where('id',1)->value('wallet');
            $wallet_history = DB::table('add_money')->orderBy('id','desc')->get();
           return view('admin.addmoney')->with('wallet',$wallet)->with('wallet_history',$wallet_history);
         }
         
           public function add_money(Request $request){
             
            $insert =  DB::table('add_money')->insert(['amount'=>$request->amount]);
            $wallet_update = DB::table('admins')->where('id',1)->update([
              'wallet'=>DB::raw("wallet + $request->amount"),
              'today_add_money'=>DB::raw("today_add_money + $request->amount"),
              ]);
                if($wallet_update && $insert){
                    return redirect()->back()->with('success','Money added successfully.');
                }else{
                    return redirect()->back()->with('error','Something went wrong!');
                }          
            }
            
public function createRole()
    {
        // Fetch all roles from the admins table
       
        $roles = DB::table('admins')->select('role_id')->distinct()->get();
        $creator_id = DB::table('admins')->select('terminal_id')->where('id', $authid)->first(); // Use first() to get a single record
        $role = DB::table('admins')->select('role_id')->where('id', $authid)->first(); // Use first() to get a single record
        return view('admin.createrole', compact('authid','roles','role','creator_id'));
    }
       public function editpass(Request $request, $id)
      {
        $request->validate([
            'password' => 'required|'
        ]);
        $admin = Admin::findOrFail($id);
        $admin->password = $request->password;
        $admin->save();
        return back()->with('success', 'Password updated successfully.');
       }
       
  public function getTerminalsByRole(Request $request)
  {
    $request->validate([
        'role_id' => 'required|integer', 
        'logged_in_role_id' => 'required|integer', 
        'auth' => 'required|string',
    ]);
     $created_by = $request->input('auth');
    $terminals = []; 
    if ($request->logged_in_role_id == 2 && $request->role_id == 3) {
        $terminals = DB::table('admins')
            ->where('id', auth()->user()->id) 
            ->pluck('terminal_id'); 
    } 
    elseif ($request->logged_in_role_id == 2 && $request->role_id == 4) {
               $terminalsfirst = DB::table('admins')
              ->where('created_by', $created_by) 
              ->where('role_id', 3) 
              ->select('terminal_id');
          $terminalssecond = DB::table('admins')
              ->where('id', $created_by) // Jo id hai usko match karna
              ->select('terminal_id');
          $terminals = $terminalsfirst->union($terminalssecond)->pluck('terminal_id');
       }
        elseif ($request->logged_in_role_id == 3 && $request->role_id == 4){
               $terminals = DB::table('admins')
              ->where('id', $created_by) 
              ->pluck('terminal_id');
       }
    elseif ($request->logged_in_role_id == 1) {
        if ($request->role_id == 2) {
            $terminals = DB::table('admins')
                ->where('role_id', 1)
                ->pluck('terminal_id');
        } elseif ($request->role_id == 3) {
            $terminals = DB::table('admins')
                ->where('role_id', 2)
                ->pluck('terminal_id');
        } elseif ($request->role_id == 4) {
            $terminals = DB::table('admins')
                ->where('role_id',3)
                ->pluck('terminal_id');
        }
    }
    return response()->json($terminals); // Terminals ko JSON response ke through bhejte hain
}

public function store(Request $request)
{
  
    // Validate the request
    $request->validate([
       'terminal_id' => 'required|',
        'password' => 'required|',
        'role_id' => 'required|integer',
        'under_role_terminal_id' => 'required|string', // Ye line ab update hui hai, selected terminal id
        'createdby' => 'required|', // Ye line ab update hui hai,login prson id
    ]);
    
        $terminalid = $request->under_role_terminal_id;
        $data = DB::table('admins')->where('terminal_id', $terminalid)->first();
        $ids = $data->id;
        $role_id = $data->role_id;
        $insidestokist =  $data->inside_stockist;
        $insidesubstokist =$data->inside_substockist;
        
        if($role_id == 2){            // yaha uski role_id chech ho rhai hai jiske andar create kiya ja raha hai 
            $insidestokist = $ids;
            $insidesubstokist =null;
        }
        elseif($role_id == 3)
        {
            $insidestokist = $insidestokist;
            $insidesubstokist =$ids;
        }else{
            $insidestokist= null;
            $insidesubstokist = null;
        }
        
       // dd($key);
    // Create a new admin entry
    $admin = new Admin();
    $admin->terminal_id = $request->terminal_id;
    $admin->password =$request->password; 
    $admin->role_id = $request->role_id;
    $admin->created_by = $request->createdby;
    $admin->inside_stockist = $insidestokist;
    $admin->inside_substockist = $insidesubstokist;
  
    // Save the admin entry
    $admin->save();

    // Return a success message
    return redirect()->back()->with('success', 'Role added successfully!');
}

public function editRole($id)
{
    // Role ko fetch karne ke liye $id se related data laate hain
    $authid = Session::get('Auth_id');
    $roles = DB::table('admins')->select('role_id')->distinct()->get();
    $creator_ids = DB::table('admins')->select('terminal_id')->where('id', $authid)->first();
    
    $role = DB::table('admins')->select('role_id')->where('id', $authid)->first();

    $roleToEdit = DB::table('admins')->where('id', $id)->first();
    $created_inside = $roleToEdit->created_inside;
    $creator_id = DB::table('admins')->select('terminal_id')->where('id', $created_inside)->first();
    
    if($roleToEdit->role_id ==3)
    {
         $inside_stockistid = DB::table('admins')->select('inside_stockist')->where('id', $id)->first();
         $inside_stockist_value = $inside_stockistid->inside_stockist;
         $insideid = DB::table('admins')->select('terminal_id')->where('id', $inside_stockist_value)->first();
         
        
    }elseif($roleToEdit->role_id == 4)
    {
        $inside_stockistid = DB::table('admins')->select('inside_substockist')->where('id', $id)->first();
        $inside_substockist_value = $inside_stockistid->inside_substockist;
        $insideid = DB::table('admins')->select('terminal_id')->where('id', $inside_substockist_value)->first();
    }else{
         $insideid = DB::table('admins')->select('terminal_id')->where('id', 1)->first();
    }
    
    return view('admin.editusers')
    ->with('roleToEdit', $roleToEdit)
    ->with('authid', $authid)
    ->with('roles', $roles)
    ->with('role', $role)
    ->with('insideid',$insideid);
}

   public function update(Request $request, $id)
   {
    // Validate the request
   $request->validate([
    'terminal_id' => 'required', 
    'password' => 'required', 
    'role_id' => 'required|integer',
    'under_role_terminal_id' => 'required|string', 
    'createdby' => 'required', 
   ]);
   
    $creator_id = DB::table('admins')->where('terminal_id', $request->under_role_terminal_id)->value('id'); 
    $admin = Admin::findOrFail($id);
    $admin->terminal_id = $request->terminal_id;
    $admin->password = $request->password;
    $admin->role_id = $request->role_id;
    $admin->created_inside = $creator_id; 
    $admin->created_by = $request->createdby;
    $admin->save();
    return redirect()->back()->with('success', 'Role updated successfully!');
   }  
  
  
public function stokistlist(Request $request) { 
  
    $authid = Session::get('Auth_id');  
    $role_id = Session::get('role_id');  
    $admins = DB::table('admins')
        ->leftjoin('admins as admin_stockist','admins.inside_stockist','=','admin_stockist.id')
        ->leftjoin('admins as admin_substockist','admins.inside_substockist','=','admin_substockist.id')
        ->select(
        'admins.id as id',
        'admins.role_id as role_id',
        'admins.created_by as created_by',
        'admins.created_inside as created_inside',
        'admins.inside_stockist as inside_stockist',
        'admins.inside_substockist as inside_substockist',
        'admins.terminal_id as terminal_id',
        'admins.password as password',
        'admins.wallet as wallet',
        'admins.day_wallet as day_wallet',
        'admins.today_add_money as today_add_money',
        'admins.receiveamount as receiveamount',
        'admins.status as status',
        'admins.reason as reason',
        'admins.created_at as created_at',
        'admins.updated_at as updated_at',
        'admin_stockist.terminal_id as stockist_tr_id',
        'admin_substockist.terminal_id as substockist_tr_id',
        )->whereIn('admins.status', [0, 1]);

    // Filter based on role_id (Stockist or Substockist)
    if ($role_id == 2) {
        $admins = $admins->where('admins.inside_stockist', $authid);
    }
    if ($role_id == 3) {
        $admins = $admins->where('admins.inside_substockist', $authid);
    }
  
    // Terminal ID filtering from the dropdown
    if ($request->has('terminal_id') && !empty($request->terminal_id)) {
        $admins = $admins->where('admins.terminal_id', $request->terminal_id);
    }

    // Fetching the data
    $admins = $admins->orderBy('admins.id', 'desc')->get();
    return view('admin.stokist')
        ->with('admins', $admins)
        ->with('authid', $authid)
        ->with('roles', $role_id);
}


 
public function getSubstockists($stockistId)
{
    $substockists = DB::table('admins')
                      ->where('inside_stockist', $stockistId)
                      ->where('role_id', 3) // Role ID for Substockist
                      ->get(['id', 'terminal_id']); // Select ID and terminal_id
    return response()->json($substockists);
}

public function getUsers($substockistId)
{
    $users = DB::table('admins')
               ->where('inside_substockist', $substockistId)
               ->where('role_id', 4) // Role ID for Users
               ->get(['id', 'terminal_id', 'role_id', 'password', 'wallet', 'day_wallet', 'today_add_money', 'created_at', 'status']); // Select required fields

    return response()->json($users);
}

// AdminController.php
public function getUserByTerminal($terminalId)
{
    //$user = Admin::where('terminal_id', $terminalId)->first(); // Find user by terminal_id

     $user = DB::table('admins')
        ->leftjoin('admins as admin_stockist','admins.inside_stockist','=','admin_stockist.id')
        ->leftjoin('admins as admin_substockist','admins.inside_substockist','=','admin_substockist.id')
        ->select(
        'admins.id as id',
        'admins.role_id as role_id',
        'admins.created_by as created_by',
        'admins.created_inside as created_inside',
        'admins.inside_stockist as inside_stockist',
        'admins.inside_substockist as inside_substockist',
        'admins.terminal_id as terminal_id',
        'admins.password as password',
        'admins.wallet as wallet',
        'admins.day_wallet as day_wallet',
        'admins.today_add_money as today_add_money',
         'admins.receiveamount as receiveamount',
        'admins.status as status',
         'admins.reason as reason',
        'admins.created_at as created_at',
        'admins.updated_at as updated_at',
        'admin_stockist.terminal_id as stockist_tr_id',
        'admin_substockist.terminal_id as substockist_tr_id',
        )
        ->where('admins.terminal_id', $terminalId)
        ->first();
     
    if ($user) {
        return response()->json($user); // Return user data as JSON
    } else {
        return response()->json(null); // Return null if user not found
    }
}

public function getTableData(Request $request)
{
    $query = DB::table('admins')
        ->leftjoin('admins as admin_stockist','admins.inside_stockist','=','admin_stockist.id')
        ->leftjoin('admins as admin_substockist','admins.inside_substockist','=','admin_substockist.id')
        ->select(
        'admins.id as id',
        'admins.role_id as role_id',
        'admins.created_by as created_by',
        'admins.created_inside as created_inside',
        'admins.inside_stockist as inside_stockist',
        'admins.inside_substockist as inside_substockist',
        'admins.terminal_id as terminal_id',
        'admins.password as password',
        'admins.wallet as wallet',
        'admins.day_wallet as day_wallet',
        'admins.today_add_money as today_add_money',
         'admins.receiveamount as receiveamount',
        'admins.status as status',
         'admins.reason as reason',
        'admins.created_at as created_at',
        'admins.updated_at as updated_at',
        'admin_stockist.terminal_id as stockist_tr_id',
        'admin_substockist.terminal_id as substockist_tr_id',
        );

    if ($request->stockist_id) {
        $query->where('admins.inside_stockist', $request->stockist_id);
    }

    if ($request->substockist_id) {
        $query->where('admins.inside_substockist', $request->substockist_id);
    }
    $data = $query->get();

    return response()->json($data);
}

public function updateStatus(Request $request, $id)
{
   
    // Validate the incoming request
    $request->validate([
        'status' => 'required|boolean',
        'reason' => 'nullable|string', // Reason is only required if status is inactive
    ]);

    // Find the admin by ID and update the status
    $admin = Admin::findOrFail($id);
    $admin->status = $request->status;

    // If the status is inactive (0), save the reason
    if ($request->status == 0 && $request->has('reason')) {
        $admin->reason = $request->reason;
    } else {
        $admin->reason = null; // Clear the reason if status is active
    }

    $admin->save();

    return redirect()->back()->with('message', 'Status updated successfully!');
}
public function destroy($id)
{
    // Find the admin by ID and delete it
    $admin = Admin::findOrFail($id);
    $admin->delete();

    return redirect()->back()->with('message', 'Admin deleted successfully!');
} 

public function addwallet(Request $request, $id)
{
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
        'authid' => 'required',
        'operation' => 'required|string|in:add,deduct',
    ]);

    $auth = $request->authid;
   
    $admin = Admin::findOrFail($id);
    $authAdmin = Admin::findOrFail(Session::get('Auth_id'));
    $authRole = $authAdmin->role_id;
    $unlimited = ($authRole == 1);
    if ($request->operation == 'add') {
        if ($unlimited || $authAdmin->wallet >= $request->amount) {
            $admin->wallet += $request->amount;
            if (!$unlimited) {
                $authAdmin->wallet -= $request->amount;
            }
            $admin->today_add_money += $request->amount;
            $message = 'Amount added to wallet successfully.';
            $operation = 1;
        } else {
            return redirect()->back()->with('error', 'Your Insufficient wallet balance for recharge.');
        }
    } elseif ($request->operation == 'deduct') {
        if ($admin->wallet >= $request->amount) {
            if (!$unlimited) {
                if ($authAdmin->wallet < $request->amount) {
                    return redirect()->back()->with('error', 'Insufficient wallet balance for deduction.');
                }
                $authAdmin->wallet += $request->amount;
            }
            $admin->wallet -= $request->amount;
            $admin->today_add_money -= $request->amount;
            $message = 'Amount deducted from wallet successfully.';
            $operation = 2;
        } else {
            return redirect()->back()->with('error', 'Wallet balance insufficient. Cannot deduct the requested amount.');
        }
    }
    $transaction = new TransactionHistory();
    $transaction->user_id = $id;
    $transaction->transaction_perform_by = $auth;
    $transaction->amount = $request->amount;
    $transaction->result1add2deduct = $operation;
    $transaction->created_at = now();
    $transaction->updated_at = now();
    $transaction->save();
    $admin->save();
    if (!$unlimited) {
        $authAdmin->save();
    }
    return redirect()->back()->with('success', $message);
}
   public function history($id)
{
    // Fetching transaction history
    $transactions = DB::table('admins')
        ->join('TransactionHistory', 'admins.id', '=', 'TransactionHistory.user_id')
        ->select('admins.id as admin_id', 'admins.role_id as role', 'admins.terminal_id as terminal_id', 'TransactionHistory.id as transaction_id', 
        'TransactionHistory.amount as transamount', 'TransactionHistory.result1add2deduct as description', 'TransactionHistory.created_at as transtime')
        ->where('admins.id', $id)
        ->get();

    // Fetching payment received data
    $paymentricive = DB::table('admins')
        ->join('PaymentReceive', 'admins.id', '=', 'PaymentReceive.user_id')
        ->select('admins.*', 'PaymentReceive.receiveamount','PaymentReceive.user_id', 'PaymentReceive.id', 'PaymentReceive.created_at as payment_created_at', 'PaymentReceive.addedby')
        ->where('admins.id', $id)
        ->get();

    // Passing data using 'with' instead of compact
    return view('admin.transactions')
        ->with('transactions', $transactions)
        ->with('paymentricive', $paymentricive);
}


public function receiveamount(Request $request, $id)
{
    $request->validate([
        'received_amount' => 'required|',
    ]);
    $admin = Admin::findOrFail($id);
    $admin->receiveamount += $request->received_amount;
    $admin->save();
    DB::table('PaymentRicive')->insert([
        'user_id' => $id,
        'receiveamount' => $request->received_amount,
        'addedby' => Session::get('Auth_id') ,
        'created_at' => Carbon::now('Asia/Kolkata'),
    ]);
    return redirect()->back()->with('success', 'amount Received with wistory add!');
}

  public function userpending(? string $status)
    {
    $user = DB::table('admins')->where('status', $status)->get();
    return view('admin.pending')->with('user', $user);
    }
    
   public function updateStatuss(Request $request, $id)
    {
             $request->validate([
             'status' => 'required|integer',
           
          ]);
           $admin = Admin::findOrFail($id);
           $admin->status = $request->status;
           $admin->reason = null;
          if ($request->reason) {
           if (strlen($request->reason) > 55) {
            return redirect()->back()->with(['message' => 'Reason must be 55 characters or less.']);
           } else {
            $admin->reason = $request->reason;
           }
           }
            $admin->save();
        return redirect()->back()->with('message', 'Status updated successfully!');
    }
   


}




















