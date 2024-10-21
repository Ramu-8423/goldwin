@extends('admin.body.adminmaster')
@section('content')

<div class="col-md-12 margin_top_30">
    <div class="white_shd full margin_bottom_30">
        <div class="full graph_head">
            <div class="heading1 margin_0">
                @if($authrole == 1)
                <form action="{{ route('admin.calculation') }}" method="GET">
                    <div class="row justify-content-center">
                        <!-- Stokist Section -->
                        <div class="col-sm-3 text-center mb-3">
                            <span class="me-2">Stokist</span>
                            <select id="stockist-select" name="st_terminal_id" class="form-control select2">
                                <option value="">All Stokist Terminal</option>
                                @foreach($users as $admin)
                                @if($admin->adminrole_id == 2)
                                <option value="{{ $admin->terminal_id }}">{{ $admin->terminal_id }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Substockist Section -->
                        <div class="col-sm-3 text-center mb-3">
                            <span class="me-2">Substokist</span>
                            <select id="substockist-select" name="sub_terminal_id" class="form-control select2">
                                <option value="">All Substokist Terminal</option>
                                <!-- Populate substockist options as needed -->
                            </select>
                        </div>

                        <!-- User Section -->
                        <div class="col-sm-3 text-center mb-3">
                            <span class="me-2">User</span>
                            <select id="user-select" name="use_terminal_id" class="form-control select2">
                                <option value="">All User Terminal</option>
                                <!-- Populate user options as needed -->
                            </select>
                        </div>

                        <!-- Buttons Section -->
                        <div class="col-sm-3 d-flex align-items-center justify-content-center mb-3">
                            <button type="submit" class="btn btn-primary btn-sm mr-2">Search</button>
                            <a href="{{route('admin.calculation')}}" class="btn btn-secondary btn-sm" onclick="reloadPage()">Reset</a>

                        </div>
                    </div>
                </form>
                @elseif($authrole == 2)
                <form action="{{ route('admin.calculation') }}" method="GET">
                    <div class="row d-flex">
                        <!-- Substockist Section -->
                        <div class="col-sm-3 text-center ml-1">
                            <span class="me-2">Substokist</span>
                            <select id="substockist-select" name="sub_terminal_id" class="form-control select2 me-2"
                                style="width: auto;">
                                <option value="">All Substokist Terminal</option>
                                @foreach($users as $admin)
                                @if($admin->adminrole_id == 3)
                                <option value="{{ $admin->adminid }}">{{ $admin->terminal_id }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- User Section -->
                        <div class="col-sm-3 text-center ml-5">
                            <span class="me-2">&nbsp;&nbsp;&nbsp;User&nbsp;&nbsp;</span>
                            <select id="user-select" name="use_terminal_id" class="form-control select2 me-2"
                                style="width: auto;">
                                <option value="">All User Terminal</option>
                                <!-- User options will be populated here -->
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary btn-sm mt-4 ">Search</button>
                                <a href="{{route('admin.bethistory')}}"><button
                                        class="btn btn-secondary  btn-sm mt-4  ml-1">Reset</button>
                            </div></a>
                        </div>
                    </div>
                </form>
                @elseif($authrole == 3)
                          <div class="row">
                         <div class="col-sm-3  mt-2">
                             <form action="{{ route('admin.calculation') }}" method="GET" class="d-flex align-items-center ml-5">
                                 <div class="input-group-sm mb-3 d-flex">
                                     <input type="date" name="date" class="form-control" required 
                                            value="{{ request('date') }}">
                                     <div class="d-flex">
                                         <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                                     </div>
                                 </div>
                             </form>
                         </div>
                     
                         <div class="col-sm-4 mt-2 ">
                             <form action="{{ route('admin.calculation') }}" method="GET" class="d-flex align-items-center">
                                 <div class="input-group-sm mb-3 d-flex">
                                     <input type="date" name="start_date" class="form-control" required 
                                            value="{{ request('start_date') }}">
                                     <input type="date" name="end_date" class="form-control ml-2" required 
                                            value="{{ request('end_date') }}">
                                     <div class="d-flex">
                                         <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                                     </div>
                                 </div>
                             </form>
                         </div>
                         <div class="col-sm-4  ml-4">
                             <form action="{{ route('admin.calculation') }}" method="GET">
                    <div class="d-flex align-items-center">
                        <div class="mr-3 d-flex align-items-center mt-2">
                            <select id="user-select" name="use_terminal_id" class="form-control select2"
                                style="width: 150px;">
                                 @foreach($users as $admin)
                                        @if($admin->adminrole_id == 4)
                                        <option value="" selected disabled>Select User Terminal</option>
                                        <option value="{{ $admin->terminal_id }}">{{ $admin->terminal_id }}</option>
                                        @endif
                                        @endforeach
                            </select>
                        </div>
                        <!-- Buttons -->
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary btn-sm mr-2">Search</button>
                            <a href="{{route('admin.calculation')}}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </div>
                </form>
                         </div>
                     </div>
                 @endif
                
                @if($authrole != 3)
                <div class="row">
                    <div class="col-sm-3">
                        <form action="{{ route('admin.calculation') }}" method="GET">
                            <div class="d-flex align-items-center">
                                <!-- User Dropdown -->
                                <div class=" d-flex align-items-center mt-2">
                                    <select id="user-selectmenual" name="all_user_search_id"
                                        class="form-control select2" style="width:200px;">
                                        @foreach($users as $admin)
                                        @if($admin->adminrole_id == 4)
                                        <option value="" selected disabled>Select User Terminal</option>
                                        <option value="{{ $admin->terminal_id }}">{{ $admin->terminal_id }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                 <input type="text" class="form-control">
                                <!-- Buttons -->
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                                    <!--<a href="" class="btn btn-secondary btn-sm">Reset</a>-->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 mt-2 ml-4">
                            <form action="{{ route('admin.calculation') }}" method="GET" class="d-flex align-items-center">
                                <div class="input-group-sm mb-3 d-flex">
                                    <input type="date" name="start_date" class="form-control" required 
                                           value="{{ request('start_date') }}">
                                    <input type="date" name="end_date" class="form-control ml-2" required 
                                           value="{{ request('end_date') }}">
                                    <div class="d-flex">
                                        <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                
                @endif
            </div>
            
        </div>
  <div class="row">
    <!-- Card for Pending Bets -->
   
    <div class="col-md-4">
        <div class="card mb-3" style="border: 2px solid #007bff;">
            <div class="card-header bg-info text-white">Pending Bets</div>
            <div class="card-body">
                <p><strong>Total Pending Points:</strong> {{ $sumresults->total_pending_points?? 0 }}</p>
               
            </div>
        </div>
    </div>
     <div class="col-md-4">
        <div class="card mb-3" style="border: 2px solid #007bff;">
            <div class="card-header bg-primary text-white">Total Bets Points</div>
            <div class="card-body">
                <p><strong>Total Bets Points:</strong> {{ $sumresults->total_bet_points ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Card for Cancelled Bets -->
    <div class="col-md-4">
        <div class="card mb-3" style="border: 2px solid #007bff;">
            <div class="card-header bg-danger text-white">Cancelled Bets</div>
            <div class="card-body">
                <p><strong>Total Cancel Points:</strong> {{ $sumresults->total_cancel_points ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Card for Loss Points -->
    <div class="col-md-4">
        <div class="card mb-3" style="border: 2px solid #007bff;">
            <div class="card-header bg-warning text-dark">Loss Points</div>
            <div class="card-body">
                <p><strong>Total Loss Points:</strong> {{ $sumresults->total_loss_points ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Card for Unclaimed Bets -->
    <div class="col-md-4">
        <div class="card mb-3" style="border: 2px solid #007bff;">
            <div class="card-header bg-secondary text-white">Unclaimed Bets</div>
            <div class="card-body">
                <p><strong>Total Unclaimed Points:</strong> {{ $sumresults->total_unclaimed_points ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Card for Claimed Bets -->
    <div class="col-md-4">
        <div class="card mb-3" style="border: 2px solid #007bff;">
            <div class="card-header bg-success text-white">Claimed Bets</div>
            <div class="card-body">
                <p><strong>Total Claimed Points:</strong> {{ $sumresults->total_claimed_points ?? 0 }}</p>
            </div>
        </div>
    </div>
    
</div>
 <div class="table_section padding_infor_info">
        <div class="table-responsive">
            <table id="example" class="table table-hover" style="white-space: nowrap;">
                <thead>
                    <tr>
                        <th>Sr.Num.</th>
                        <th>Pin</th>
                        <th>Result Time</th>
                        <th>Quantity</th>
                        <th>Total Points</th>
                        <th>Win Points</th>
                        <th>Bet Details</th>
                        <th>Game Name</th>
                        <th>Terminal ID</th>
                        <th>Status</th>
                        <th>Order ID</th>
                        <th>Barcode Number</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $card_info = [
                    '1' => 'JC',
                    '2' => 'JD',
                    '3' => 'JS',
                    '4' => 'JH',
                    '5' => 'QC',
                    '6' => 'QD',
                    '7' => 'QS',
                    '8' => 'QH',
                    '9' => 'KC',
                    '10' => 'KD',
                    '11' => 'KS',
                    '12' => 'KH'
                    ];
                    @endphp
                    @foreach($results as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->admin_terminal_id }}</td>


                        <td>{{ $item->result_time }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->total_points }}</td>
                        <td>{{ $item->win_points }}</td>
                        <td>
                            @php
                            $details = json_decode($item->bet_details);
                            $bet_details_string = '';
                            @endphp
                            @foreach($details as $value)
                            @php
                            $points = $value->points;
                            $card_number = $value->card_number;

                            if (array_key_exists($card_number, $card_info)) {
                            $card_name = $card_info[$card_number];
                            $bet_details_string .= $card_name . ':' . $points . ',';
                            }
                            @endphp
                            @endforeach
                            {{ rtrim($bet_details_string, ',') }}
                        </td>
                        <td>{{ $item->game_name }}</td>
                        <td>{{ $item->treminal_id }}</td>
                        <td>
                            @php
                            $bet_status =
                            $item->status==0?'Pending':($item->status==1?'Cancel':($item->status==2?'Loss':($item->status==3?'PRZ':($item->status==4?'Claimed':''))));
                            @endphp
                            {{$bet_status}}
                        </td>
                        <td>{{ $item->order_id }}</td>
                        <td>{{ $item->barcode_number }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

          
        </div>
    </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Stockist selection change
    $('#stockist-select').on('change', function() {
        var stockistId = $(this).val();
        if (stockistId) {
            // Get Substockists based on selected stockist using AJAX
            $.ajax({
                url: '/get-stockist-subordinates/' + stockistId, // Ensure this URL is correct
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Clear the Substockist dropdown and append new data
                    $('#substockist-select').empty();
                    $('#substockist-select').append(
                        '<option value="">All Sustokist Terminal</option>');
                    $.each(data, function(key, value) {
                        $('#substockist-select').append('<option value="' + value
                            .id + '">' + value.terminal_id + '</option>');
                    });
                    // Clear user dropdown as stockist has changed
                    $('#user-select').empty();
                    $('#user-select').append('<option value="">All User Terminal</option>');
                },
                error: function() {
                    alert('Error fetching substockists');
                }
            });
        } else {
            $('#substockist-select').empty();
            $('#user-select').empty();
        }
    });
    // Substockist selection change
    $('#substockist-select').on('change', function() {
        var substockistId = $(this).val();
        console.log(`when stokist login and selected a substokist - ${substockistId}`);
        if (substockistId) {
            // Get Users under selected substockist using AJAX
            $.ajax({
                url: '/get-substockist-users/' + substockistId, // Ensure this URL is correct
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Clear the User dropdown and append new data
                    $('#user-select').empty();
                    $('#user-select').append('<option value="">All User Terminal</option>');
                    $.each(data, function(key, value) {
                        $('#user-select').append('<option value="' + value
                            .terminal_id + '">' + value.terminal_id +
                            '</option>');
                    });
                },
                error: function() {
                    alert('Error fetching users');
                }
            });
        } else {
            $('#user-select').empty();
        }
    });
});
</script>
<script>
$(document).ready(function() {
    // Initialize select2 for all select boxes
    $('#stockist-select').select2({
        placeholder: "All Stokist Terminal",
        allowClear: true
    });

    $('#substockist-select').select2({
        placeholder: "All Substockist Terminal",
        allowClear: true
    });

    $('#user-select').select2({
        placeholder: "All User Terminal",
        allowClear: true
    });
    $('#user-selectmenual').select2({
        placeholder: "All User Terminals",
        allowClear: true
    });
});
</script>



@endsection