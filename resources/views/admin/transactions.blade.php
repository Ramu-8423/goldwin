@extends('admin.body.adminmaster') 

@section('content')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0">
                        <h2>Transaction History</h2>
                    </div>
                </div>
                <div class="table_section padding_infor_info">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>UserID</th>
                                    <th>Role</th>
                                    <th>KEY NAME</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>CREATED DATE</th>
                                </tr>
                            </thead>
                            <tbody class="tdata">
                                @forelse($transactions as $admin)
                                <tr>
                                    <td>{{ $admin->transaction_id }}</td>
                                    <td>{{ $admin->admin_id }}</td>
                                    <td>
                                         @if($admin->role == 1)
                                              <b class="text-primary">Admin</b>
                                          @elseif($admin->role == 2)
                                              <b class="text-primary">Stockist</b>
                                          @elseif($admin->role == 3)
                                              <b class="text-primary">Substockist</b>
                                          @elseif($admin->role == 4)
                                              <b class="text-primary">User</b>
                                          @endif
                                    </td>
                                    <td>{{ $admin->terminal_id }}</td>
                                    <td>{{ $admin->transamount }}</td>
                                    <td>
                                        @if($admin->description == 1)
                                         <p>Add</p>
                                       @else
                                            <p>Deduct</p>
                                       @endif
                                    </td>
                                   <td>{{$admin->transtime}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <marquee behavior="alternate" direction="">
                                            <span style="color:red;">!!</span> 
                                            <span style="color:black;">No Transaction History for this user</span> 
                                            <span style="color:red;">!!</span>
                                        </marquee>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0">
                        <h2>PaymentReceive History</h2>
                    </div>
                </div>
                <div class="table_section padding_infor_info">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Paymentricive ID</th>
                                    <th>User ID</th>
                                    <th>Role</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>DATE</th>
                                </tr>
                            </thead>
                            <tbody class="tdata">
                                @forelse($paymentricive as $admins)
                                <tr>
                                    <td>{{ $admins->id }}</td>
                                    <td>{{ $admins->user_id }}</td> 
<td>
                                         @if($admins->role_id == 1)
                                              <b class="text-primary">Admin</b>
                                          @elseif($admins->role_id == 2)
                                              <b class="text-primary">Stockist</b>
                                          @elseif($admins->role_id == 3)
                                              <b class="text-primary">Substockist</b>
                                          @elseif($admins->role_id == 4)
                                              <b class="text-primary">User</b>
                                          @endif
                                    </td>
                                    <td>{{ $admins->receiveamount }}</td> 
                                    <td>
                                        @if ($admins->addedby == 1)
                                            <span class="text-primary">Admin</span>
                                        @elseif ($admins->addedby == 2)
                                            <span class="text-primary">Stokist</span>
                                        @elseif ($admins->addedby == 3)
                                            <span class="text-primary">Sub Stokist</span>
                                        @else
                                            <span>N/A</span> 
                                        @endif
                                    </td>
                                    <td>{{ $admins->created_at }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <marquee behavior="alternate" direction="">
                                            <span style="color:red;">!!</span> 
                                            <span style="color:black;">No Paymentricive History for this user</span> 
                                            <span style="color:red;">!!</span>
                                        </marquee>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
