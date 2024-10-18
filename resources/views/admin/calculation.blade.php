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
                            <a href="" class="btn btn-secondary btn-sm">Reset</a>
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
                            <span class="me-2">User</span>
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
                <form action="{{ route('admin.calculation') }}" method="GET">
                    <div class="d-flex align-items-center">
                        <!-- Bet Status Dropdown -->
                        <div class="mr-3">
                            <select name="bet_status" id="status" class="form-control form-control-sm">
                                <option value="">All History</option>
                                <option value="0">Pending</option>
                                <option value="1">Cancel</option>
                                <option value="2">Loss</option>
                                <option value="3">Unclaimed</option>
                                <option value="4">Claimed</option>
                            </select>
                        </div>

                        <!-- User Dropdown -->
                        <div class="mr-3 d-flex align-items-center mt-2">

                            <select id="user-select" name="use_terminal_id" class="form-control select2"
                                style="width: 150px;">
                                @foreach($results as $admin)
                                @if($admin->admin_role_id == 4)
                                <option value="{{ $admin->admin_terminal_id }}">{{ $admin->admin_terminal_id }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary btn-sm mr-2">Search</button>
                            <a href="" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </div>
                </form>
                @endif
                <div class="row">
                    <div class="col-sm-3">
                        <form action="{{ route('admin.calculation') }}" method="GET">
                            <div class="d-flex align-items-center">
                                <!-- User Dropdown -->
                                <div class="mr-3 d-flex align-items-center mt-2">
                                    <select id="user-selectmenual" name="all_user_search_id"
                                        class="form-control select2" style="width: 150px;">
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
                                    <a href="" class="btn btn-secondary btn-sm">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="table_section padding_infor_info">
            <div class="table-responsive">
                <div class="text-center">
                    <h3> data here</h3>
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