@extends('admin.body.adminmaster')
@section('content')
<div class="col-md-12 margin_top_30">
    <div class="white_shd full margin_bottom_30">
        <div class="full graph_head">
            <div class="heading1 margin_0">
                <div class="row">
                    @if($authrole != 3)
                    <div class="col-sm-3">
                        <form action="{{ route('admin.bethistory') }}" method="GET"
                            class="d-flex align-items-center ml-auto">
                            <select name="use_terminal_id" id="uniqueTerminalSelect"
                                class="form-control custom-select-terminal" style="width: 160px; margin-top: 5px;">
                                <option value="" disabled selected>Select terminal</option> <!-- Default option -->
                                @foreach($users as $admin)
                                @if($admin->adminrole_id == 4)
                                <option value="{{ $admin->terminal_id }}">
                                    {{ $admin->terminal_id }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                            <button id="uniqueSubmitButton" class="btn btn-primary btn-sm ml-2 custom-button"
                                style="margin-top: -5px;">search</button> <!-- Keep button unchanged -->
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <form action="{{ route('admin.bethistory') }}" method="GET"
                            class="d-flex align-items-center ml-5">
                            <div class="input-group-sm mb-3 d-flex">
                                <input type="date" name="date" class="form-control" required>
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <form action="{{ route('admin.bethistory') }}" method="GET" class="d-flex align-items-center">
                            <div class="input-group-sm mb-3 d-flex">
                                <input type="date" name="start_date" class="form-control" required>
                                <input type="date" name="end_date" class="form-control ml-2" required>
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif

                </div>
                @if($authrole == 1)
                <form action="{{ route('admin.bethistory') }}" method="GET">
                    <div class="row d-flex">
                        <div class="col-sm-2 text-center ml-1">
                            <span class="me-2">All Status</span>
                            <select name="bet_status" id="status" class="form-control form-control-sm  ml-2">
                                <option value="">All History</option>
                                <option value="0">Pending</option>
                                <option value="1">Cancel</option>
                                <option value="2">Loss</option>
                                <option value="3">Unclaimed</option>
                                <option value="4">Claimed</option>
                            </select>
                            </select>
                        </div>
                        <!-- Stokist Section -->
                        <div class="col-sm-3 text-center ml-1">
                            <span class="me-2">&nbsp;&nbsp;Stokist&nbsp;&nbsp;</span>
                            <select id="stockist-select" name="st_terminal_id" class="form-control select2 me-2"
                                style="width: auto;">
                                <option value="">All Stokist Terminal</option>
                                @foreach($users as $admin)
                                @if($admin->adminrole_id == 2)
                                <option value="{{ $admin->terminal_id }}">
                                    {{ $admin->terminal_id }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <!-- Substockist Section -->
                        <div class="col-sm-3 text-center ml-1">
                            <span class="me-2">Sustokist</span>
                            <select id="substockist-select" name="sub_terminal_id" class="form-control select2 me-2"
                                style="width: auto;">
                                <option value="">All Sustokist Terminal</option>
                                <!-- Populate substockist options as needed -->
                            </select>
                        </div>
                        <!-- User Section -->
                        <div class="col-sm-2 text-center ml-1">
                            <span class="me-2">User</span>
                            <select id="user-select" name="use_terminal_id" class="form-control select2 me-2"
                                style="width: auto;">
                                <option value=""> User Terminal</option>
                                <!-- Populate user options as needed -->
                            </select>
                        </div>

                        <div class="col-sm-1 ml-2">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary btn-sm mt-4">Search</button>
                                <a href="{{route('admin.bethistory')}}"><button
                                        class="btn btn-secondary  btn-sm mt-4  ml-1">Reset</button></a>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
            @elseif($authrole == 2)
            <form action="{{ route('admin.bethistory') }}" method="GET">
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
                        <span class="me-2">&nbsp;&nbsp;User&nbsp;&nbsp;&nbsp;</span>
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
            <div class="row d-flex align-items-center">
                <!-- User Dropdown Form -->
                <div class="col-sm-3">
                    <form action="{{ route('admin.bethistory') }}" method="GET">
                        <div class="d-flex align-items-center">
                            <select id="user-select" name="use_terminal_id" class="form-control select2"
                                style="width: 150px;">
                                <option value="" disabled selected>Select a terminal</option>
                                @foreach($users as $admin)
                                @if($admin->adminrole_id == 4)
                                <option value="{{ $admin->terminal_id }}">{{ $admin->terminal_id }}</option>
                                @endif
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm ml-2">Search</button>
                            <a href="{{ route('admin.bethistory') }}" class="btn btn-secondary btn-sm ml-1">Reset</a>
                        </div>
                    </form>
                </div>
                <div class="col-sm-1"></div>
                <!-- Date Filter Form -->
                <div class="col-sm-4">
                    <form action="{{ route('admin.bethistory') }}" method="GET" class="d-flex align-items-center ml-3">
                        <div class="input-group-sm mb-3 d-flex">
                            <input type="date" name="date" class="form-control" required>
                            <div class="d-flex">
                                <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Date Range Filter Form -->
                <div class="col-sm-3">
                    <form action="{{ route('admin.bethistory') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-group-sm mb-3 d-flex">
                            <input type="date" name="start_date" class="form-control" required>
                            <input type="date" name="end_date" class="form-control ml-2" required>
                            <div class="d-flex">
                                <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @endif
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

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $results->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $results->url(1) }}" aria-label="First">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item {{ $results->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $results->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    @php
                    $half_total_links = floor(9 / 2);
                    $from = $results->currentPage() - $half_total_links;
                    $to = $results->currentPage() + $half_total_links;

                    if ($results->currentPage() < $half_total_links) { $to +=$half_total_links - $results->
                        currentPage();
                        }

                        if ($results->lastPage() - $results->currentPage() < $half_total_links) { $from
                            -=$half_total_links - ($results->lastPage() - $results->currentPage()) - 1;
                            }
                            @endphp

                            @for ($i = $from; $i <= $to; $i++) @if ($i> 0 && $i <= $results->lastPage())
                                    <li class="page-item {{ $results->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $results->url($i) }}">{{ $i }}</a>
                                    </li>
                                    @endif
                                    @endfor

                                    <li class="page-item {{ $results->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $results->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                    <li
                                        class="page-item {{ $results->currentPage() == $results->lastPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $results->url($results->lastPage()) }}"
                                            aria-label="Last">
                                            <span aria-hidden="true">&raquo;&raquo;</span>
                                        </a>
                                    </li>
                </ul>
            </nav>
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
});
</script>
<script>
$(document).ready(function() {
    $('#uniqueTerminalSelect').select2({
        placeholder: "Select All",
        allowClear: true
    });
});
</script>

@endsection