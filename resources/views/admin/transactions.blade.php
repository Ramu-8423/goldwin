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
                                    <th>No.</th>
                                    <th>Pin</th>
                                    <th>Amount</th>
                                    <th>Transaction By</th>
                                    <th>Description</th>
                                    <th>CREATED DATE</th>
                                </tr>
                            </thead>
                            <tbody class="tdata">
                                @forelse($transactions as $admin)
                                <tr>
                                   <td>{{ $loop->iteration }}</td>
                                    <td>{{ $admin->terminal_id }}</td>
                                    <td>{{ $admin->transamount }}</td>
                                    <td>
    @if($admin->transaction_perform_by == 1)
        Admin
    @elseif($admin->transaction_perform_by == 2)
        Stockist
    @elseif($admin->transaction_perform_by == 3)
        Substockist
    @else
        Unknown Role
    @endif
</td>
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
                                    <th>No.</th>
                                     <th>Pin</th>
                                    <th>Amount</th>
                                    <th>Receiver</th>
                                    <th>DATE</th>
                                </tr>
                            </thead>
                            <tbody class="tdata">
                                @forelse($paymentricive as $admins)
                                <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $admins->terminal_id }}</td>
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
