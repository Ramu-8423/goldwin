@extends('admin.body.adminmaster')
@section('content')
@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif

<div class="container-fluid mt-4">
    <!--abc-->

    <!--abc-->
    <div class="row">
        <div class="col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0">
                        <div class="d-flex flex-wrap align-items-center">
                            @if($roles == 1)
                            <div class="d-flex flex-wrap ml-3">
                                <select id="stockist-select" class="form-control select2 me-2" style="width: auto;">
                                    <option value="">Select Stockist</option>
                                    @foreach($admins as $admin)
                                    @if($admin->role_id == 2)
                                    <option value="{{ $admin->id }}">{{ $admin->terminal_id }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <select id="substockist-select" class="form-control select2 me-2" style="width: auto;">
                                    <option value="">Select Substockist</option>
                                </select>

                                <select id="user-select" class="form-control select2 me-2" style="width: auto;">
                                    <option value="">Select User</option>
                                </select>
                            </div>
                            @elseif($roles == 2)
                            <div class="d-flex flex-wrap ml-3">
                                <select id="substockist-select" class="form-control select2 me-2" style="width: auto;">
                                    <option value="">Select Stockist</option>
                                    @foreach($admins as $admin)
                                    @if($admin->role_id == 3)
                                    <option value="{{ $admin->id }}">{{ $admin->terminal_id }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <select id="user-select" class="form-control select2 me-2" style="width: auto;">
                                    <option value="">Select User</option>
                                </select>
                            </div>
                            @endif
                            <div class="d-flex flex-wrap justify-content-end align-items-center "
                                style="margin-top:-5px;">
                                <form action="{{ route('stokistlist') }}" method="GET" class="form-inline">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="terminal_id" class="form-control form-control-sm ml-4"
                                            style="width: 173px;" placeholder="Enter Manually Terminal ID"
                                            value="{{ request()->get('terminal_id') }}">
                                        <button class="btn btn-primary btn-sm" type="submit">Search</button>
                                    </div>
                                </form>
                                <a href="{{ route('stokistlist') }}" class="btn btn-secondary btn-sm ml-3">Reset</a>
                            </div>
                        </div>
                        <div class="mt-3 text-right">
                            <form action="{{ route('stokistlist') }}" method="GET">

                                <span class="mb-0">Users</span> <select id="terminal_id_dropdown" name="terminal_id"
                                    class="form-control select2" style="width: auto;">
                                    <option value="">All User Terminal</option>
                                    @foreach($admins as $admin)
                                    @if($admin->role_id == 4)
                                    <option value="{{ $admin->terminal_id }}">{{ $admin->terminal_id}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm"
                                    style="margin-top:-5px;">Search</button>
                            </form>
                        </div>
                    </div>
                    <div class="table_section padding_infor_info">
                        <div class="table-responsive">
                            <!-- Changed class to table-responsive for better responsiveness -->
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>

                                        <th>Id</th>
                                        <th>Role</th>
                                        <th>PIN</th>
                                        <th>PASSWORD</th>
                                        <th>Under Stockist</th>
                                        <th>Under Substockist</th>
                                        <th>Payment Entry</th>
                                        <th>DAY WALLET</th>
                                        <th>TODAY ADD& WALLET</th>
                                        <th>Payment Recive</th>
                                        <th>CREATED DATE</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th> <!-- Add Action column -->
                                        <th>TransactionHistory</th> <!-- Add Action column -->
                                    </tr>
                                </thead>
                                <tbody class="tdata">
                                    @forelse($admins as $admin)
                                    <tr>
                                        <td>{{ $admin->id }}</td>
                                        <td>
                                            @if($admin->role_id == 1)
                                            <b class="text-primary">Admin</b>
                                            @elseif($admin->role_id == 2)
                                            <b class="text-primary">Stockist</b>
                                            @elseif($admin->role_id == 3)
                                            <b class="text-primary">Substockist</b>
                                            @elseif($admin->role_id == 4)
                                            <b class="text-primary">User</b>
                                            @endif
                                        </td>
                                        <td>{{ $admin->terminal_id }}</td>
                                        <td>{{ $admin->password }}
                                            <a class="text-danger" data-toggle="modal"
                                                data-target="#passwordModal{{ $admin->id }}"><i
                                                    class="fa-solid fa-pencil"></i></a>
                                        </td>
                                        <td>{{ $admin->stockist_tr_id??'N/A' }}</td>
                                        <td>{{ $admin->substockist_tr_id??'N/A' }}</td>
                                        <td class="text-center">
                                            {{ $admin->wallet }}
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2"></span>
                                                <div class="d-flex">
                                                    <button class="btn btn-sm btn-success me-1" data-toggle="modal"
                                                        data-target="#addModal{{ $admin->id }}">
                                                        <i class="bi bi-plus-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger ml-1" data-toggle="modal"
                                                        data-target="#deductModal{{ $admin->id }}">
                                                        <i class="bi bi-dash-square"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $admin->day_wallet }}</td>
                                        <td>{{ $admin->today_add_money }}</td>
                                        <td class="text-center">
                                            {{ $admin->receiveamount}}
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a class="text-danger btn" data-toggle="modal"
                                                    data-target="#receiveamountModal{{ $admin->id }}"><i
                                                        class="bi bi-coin">Received</i></a>
                                            </div>
                                        </td>
                                        <td>{{ $admin->created_at }}</td>
                                        <td class="text-center">
                                            @if($admin->status == 1)
                                            <button class="btn btn-sm btn-success" data-toggle="modal"
                                                data-target="#statusModal{{ $admin->id }}">Active</button>
                                            @elseif($admin->status == 0)
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#statusModal{{ $admin->id }}">Inactive</button>
                                            <div class="col-sm-1">
                                                <p style="font-size:10px;">{{ $admin->reason }}</p>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">

                                                <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#deleteModal{{ $admin->id }}"><i
                                                        class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('transaction.history', ['id' => $admin->id]) }}"
                                                class="btn btn-sm btn-success">Transaction History</a>
                                        </td>
                                    </tr>
                                    <!-- Status Modal -->
                                    <div class="modal fade" id="statusModal{{ $admin->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="statusModalLabel{{ $admin->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel{{ $admin->id }}">
                                                        {{ $admin->status == 1 ? 'Inactive' : 'Activate' }} User</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admins.updateStatus', $admin->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')

                                                        Are you sure you want to
                                                        {{ $admin->status == 1 ? 'Inactivate' : 'activate' }} this user?

                                                        <input type="hidden" name="status"
                                                            value="{{ $admin->status == 1 ? 0 : 1 }}">

                                                        @if($admin->status == 1)
                                                        <!-- Reason for inactivation input -->
                                                        <div class="form-group mt-3">
                                                            <label for="reason">Reason for Inactivation:</label>
                                                            <textarea name="reason" id="reason" class="form-control"
                                                                placeholder="Enter reason" required></textarea>
                                                        </div>
                                                        @endif
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">{{ $admin->status == 1 ? 'Inactive' : 'Activate' }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $admin->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="deleteModalLabel{{ $admin->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $admin->id }}">Delete
                                                        User</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this user?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admins.destroy', $admin->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--recevid amount-->
                                    <div class="modal fade" id="receiveamountModal{{ $admin->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="receiveamountModalLabel{{ $admin->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="receiveamountModalLabel{{ $admin->id }}">Received Amount
                                                        Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="operationForm"
                                                    action="{{ route('admins.receiveamount', ['id' => $admin->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <label for="receivedAmountInput">Amount Received</label>
                                                        <input type="text" name="received_amount"
                                                            id="receivedAmountInput" class="form-control"
                                                            value="{{ $admin->receiveamount }}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!--change password-->
                                    <div class="modal fade" id="passwordModal{{ $admin->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="passwordModalLabel{{ $admin->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="passwordModalLabel{{ $admin->id }}">
                                                        Update
                                                        Password</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-footer">
                                                    <form id="operationForm"
                                                        action="{{ route('admins.editpass', ['id' => $admin->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <label id="modalLabel" for="modalAmount">Password</label>
                                                        <input type="text" name="password" class="form-control"
                                                            value="{{$admin->password}}">
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-success"
                                                                id="modalSubmitBtn">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--change password-->

                                    <!-- Add Modal -->
                                    <div class="modal fade" id="addModal{{ $admin->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="addModalLabel{{ $admin->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addModalLabel{{ $admin->id }}">Add Money
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admins.addwallet', $admin->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="add-amount">Current Amount </label><label
                                                                for="add-amount"
                                                                class="ml-2 text-primary">{{$admin->wallet}}</label>
                                                            <input type="number" name="amount" id="modalAmount"
                                                                class="form-control" required step="0.01">
                                                            <input type="hidden" name="operation" value="add">
                                                            <input type="hidden" name="authid" value="{{$authid}}">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">add
                                                                Money</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Deduct Modal -->
                                    <div class="modal fade" id="deductModal{{ $admin->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="deductModalLabel{{ $admin->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deductModalLabel{{ $admin->id }}">Deduct
                                                        Money </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admins.addwallet', $admin->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="add-amount">Current Amount </label><label
                                                                for="add-amount"
                                                                class="ml-2 text-primary">{{$admin->wallet}}</label>
                                                            <input type="number" name="amount" class="form-control "
                                                                id="deduct-amount" required>
                                                            <input type="hidden" name="operation" value="deduct">
                                                            <input type="hidden" name="authid" value="{{$authid}}">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Deduct
                                                                Money</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="10">No users found.</td>
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
    <script>
    $(document).ready(function() {
        // Initialize Select2 for all select elements
        $('.select2').select2();

        // Stockist selection
        $('#stockist-select').on('change', function() {
            var stockistId = $(this).val();
            if (stockistId) {
                // Get Substockists
                $.ajax({
                    url: '/getSubstockists/' + stockistId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#substockist-select')
                            .empty(); // Clear previous options
                        $('#substockist-select').append(
                            '<option value="">Select Substockist</option>'
                        );
                        $.each(data, function(key, value) {
                            $('#substockist-select').append(
                                '<option value="' + value
                                .id + '">' + value
                                .terminal_id + '</option>');
                        });

                        // Clear user dropdown since stockist has changed
                        $('#user-select').empty();
                        $('#user-select').append(
                            '<option value="">Select User</option>');

                        // Update table based on Stockist selection
                        updateTable(stockistId, null,
                            null
                        ); // Passing Stockist ID to updateTable function
                    }
                });
            } else {
                $('#substockist-select').empty();
                $('#user-select').empty();
                $('.tdata').empty(); // Clear table if no stockist is selected
            }
        });

        // Substockist selection
        $('#substockist-select').on('change', function() {
            var substockistId = $(this).val();
            if (substockistId) {
                // Get Users under selected Substockist
                $.ajax({
                    url: '/getUsers/' + substockistId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#user-select')
                            .empty(); // Clear previous options
                        $('#user-select').append(
                            '<option value="">Select User</option>');
                        $.each(data, function(key, value) {
                            $('#user-select').append(
                                '<option value="' + value
                                .terminal_id + '">' + value
                                .terminal_id + '</option>');
                        });

                        updateTable(null, substockistId,
                            null
                        ); // Passing Substockist ID to updateTable function
                    }
                });
            } else {
                $('#user-select').empty();
                $('.tdata').empty(); // Clear table if no substockist is selected
            }
        });

        // User selection
        $('#user-select').on('change', function() {
            var terminalId = $(this).val();
            if (terminalId) {
                // Fetch and display user details based on terminal_id
                $.ajax({
                    url: '/getUserByTerminal/' + terminalId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('.tdata')
                            .empty(); // Clear previous table rows
                        if (data) { // Check if valid data is returned

                            console.log(data);

                            $('.tdata').append('<tr>' +
                                '<td>' + data.id + '</td>' +
                                '<td>' + (data.role_id == 1 ?
                                    "<b class='text-primary'>Admin</b>" :
                                    data.role_id == 2 ?
                                    "<b class='text-primary'>Stockist</b>" :
                                    data.role_id == 3 ?
                                    "<b class='text-primary'>Substockist</b>" :
                                    "<b class='text-primary'>User</b>"
                                ) + '</td>' +
                                '<td>' + data.terminal_id +
                                '</td>' +
                                '<td>' + data.password +
                                '<a class="text-danger" data-toggle="modal" data-target="#passwordModal' +
                                data.id + '">' +
                                '<i class="fa-solid fa-pencil"></i></a></td>' +
                                '<td>' + data.stockist_tr_id + '</td>' +
                                '<td>' + data.substockist_tr_id + '</td>' +
                                '<td class="text-center">' + data
                                .wallet +
                                '<div class="d-flex align-items-center justify-content-center">' +
                                '<div class="d-flex">' +
                                '<button class="btn btn-sm btn-success me-1" data-toggle="modal" data-target="#addModal' +
                                data.id + '">' +
                                '<i class="bi bi-plus-square"></i>' +
                                '</button>' +
                                '<button class="btn btn-sm btn-danger ml-1" data-toggle="modal" data-target="#deductModal' +
                                data.id + '">' +
                                '<i class="bi bi-dash-square"></i>' +
                                '</button>' +
                                '</div>' +
                                '</div></td>' +
                                '<td>' + data.day_wallet + '</td>' +
                                '<td>' + data.today_add_money +
                                '<td class="text-center">' +
                                data.receiveamount +
                                '<div class="d-flex align-items-center justify-content-center">' +
                                '<a class="text-danger btn" data-toggle="modal" data-target="#receiveamountModal' +
                                data.id + '">' +
                                '<i class="bi bi-coin"></i> Received' +
                                '<span style="color: green;"></span>' +
                                '</a>' +
                                '</div>' +
                                '</td>' +
                                '<td>' + data.created_at + '</td>' +
                               '<td>' + 
    (data.status == 1 ? 
        '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#statusModal' + data.id + '">Active</button>' :
    data.status == 0 ? 
        '<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#statusModal' + data.id + '">Inactive</button>' +
        '<div class="col-sm-1">' +
            '<p style="font-size:10px;">' + data.reason + '</p>' +
        '</div>' :
        ''
    ) +
'</td>'+
                                '<td>' +
                                '<div class="d-flex">' +

                                '<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal' +
                                data.id +
                                '"><i class="fa fa-trash"></i></button>' +
                                '</div>' +
                                '</td>' +
                                '<td><a href="/transaction/history/' +
                                data.id +
                                '" class="btn btn-sm btn-success">Transaction History</a></td>' +
                                '</tr>');

                            // Add the modals dynamically for the Add, Deduct, Status, and Delete actions
                            $('#modalContainer').append(`
                        <!-- Add Wallet Modal -->
                        <div class="modal fade" id="addModal${data.id}" tabindex="-1" role="dialog" aria-labelledby="addModalLabel${data.id}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addModalLabel${data.id}">Add Wallet Amount</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add Wallet form or content here -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deduct Wallet Modal -->
                        <div class="modal fade" id="deductModal${data.id}" tabindex="-1" role="dialog" aria-labelledby="deductModalLabel${data.id}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deductModalLabel${data.id}">Deduct Wallet Amount</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Deduct Wallet form or content here -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Change Status Modal -->
                        <div class="modal fade" id="statusModal${data.id}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel${data.id}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="statusModalLabel${data.id}">Change Status</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to change the status of this user?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete User Modal -->
                        <div class="modal fade" id="deleteModal${data.id}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel${data.id}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel${data.id}">Delete User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this user?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--recevid amount modal -->
                         <div class="modal fade" id="receiveamountModal${data.id}" tabindex="-1" role="dialog" aria-labelledby="receiveamountModalLabel${value.id}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiveamountModalLabel${data.id}">Received Amount Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="receiveAmountForm" action="/admins/updatereceiveamount/${data.id}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="receivedAmountInput">Amount Received</label>
                        <input type="text" name="received_amount" id="receivedAmountInput" class="form-control" value="${data.receiveamount}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                    `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' +
                            error); // Log error if AJAX request fails
                    }
                });
            } else {
                $('.tdata').empty(); // Clear table if no user is selected
            }
        });

        // Function to update table dynamically
        function updateTable(stockistId, substockistId, terminalId) {
            $.ajax({
                url: '/getTableData', // Backend route for fetching data
                type: 'GET',
                data: {
                    stockist_id: stockistId,
                    substockist_id: substockistId,
                    terminal_id: terminalId // Add terminal_id for user filtering
                },
                dataType: 'json',
                success: function(data) {
                        $('.tdata').empty(); // Clear previous table rows

                        $.each(data, function(key, value) {
                           var statusButton = value.status == 1 ?
    '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#statusModal' + value.id + '">Active</button>' :
    '<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#statusModal' + value.id + '">Inactive</button>' +
    (value.status == 0 ? 
        '<div class="col-sm-1">' +
            '<p style="font-size:10px;">' + value.reason + '</p>' +
        '</div>' : ''
    );


                            var deleteButton =
                                '<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal' +
                                value.id + '"><i class="fa fa-trash"></i></button>';
                            var transactionHistory = '<a href="/transaction/history/' + value
                                .id +
                                '" class="btn btn-sm btn-success">Transaction History</a>';

                            var roleLabel = value.role_id;
                            if (value.role_id == 1) {
                                roleLabel = '<strong style="color: blue;">Admin</strong>';
                            } else if (value.role_id == 2) {
                                roleLabel = '<strong style="color: blue;">Stokist</strong>';
                            } else if (value.role_id == 3) {
                                roleLabel = '<strong style="color: blue;">Substokist</strong>';
                            } else if (value.role_id == 4) {
                                roleLabel = '<strong style="color: blue;">User</strong>';
                            }

                            var walletColumn = `
            <div class="text-center">
                ${value.wallet} 
                <div class="d-flex align-items-center justify-content-center mt-2">
                    <button class="btn btn-sm btn-success me-1" data-toggle="modal" data-target="#addModal${value.id}">
                        <i class="bi bi-plus-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger ml-1" data-toggle="modal" data-target="#deductModal${value.id}">
                        <i class="bi bi-dash-square"></i>
                    </button>
                </div>
            </div>
        `;

                            // Append row with all columns
                            $('.tdata').append('<tr>' +
                                '<td>' + value.id + '</td>' +
                                '<td>' + roleLabel + '</td>' +
                                '<td>' + value.terminal_id + '</td>' +
                                '<td>' + value.password +
                                '<a class="text-danger" data-toggle="modal" data-target="#passwordModal' +
                                value.id + '">' +
                                '<i class="fa-solid fa-pencil"></i></a></td>' +
                                '<td>' + (value.stockist_tr_id ?? 'N/A') + '</td>' +
                                '<td>' + (value.substockist_tr_id ?? 'N/A') + '</td>' +
                                '<td>' + walletColumn + '</td>' +
                                '<td>' + value.day_wallet + '</td>' +
                                '<td>' + value.today_add_money + '</td>' +
                                '<td class="text-center">' +
                                value.receiveamount +
                                '<div class="d-flex align-items-center justify-content-center">' +
                                '<a class="text-danger btn" data-toggle="modal" data-target="#receiveamountModal' +
                                value.id + '">' +
                                '<i class="bi bi-coin"></i> Receive' +
                                '<span style="color: green;"></span>' +
                                '</a>' +
                                '</div>' +
                                '</td>' +
                                '<td>' + value.created_at + '</td>' +
                                '<td>' + statusButton + '</td>' +
                                '<td><div class="d-flex">' + deleteButton + '</div></td>' +
                                '<td>' + transactionHistory + '</td>' +
                                '</tr>');

                            // Password update modal for each row
                            $('body').append(`
            <div class="modal fade" id="passwordModal${value.id}" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel${value.id}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="passwordModalLabel${value.id}">Update Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="operationForm" action="/admins/editpass/${value.id}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <label for="passwordInput">Password</label>
                                <input type="password" name="password" id="passwordInput" class="form-control" value="${value.password}">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `);
                            $('body').append(`
    <div class="modal fade" id="receiveamountModal${value.id}" tabindex="-1" role="dialog" aria-labelledby="receiveamountModalLabel${value.id}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiveamountModalLabel${value.id}">Received Amount Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="receiveAmountForm" action="/admins/updatereceiveamount/${value.id}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="receivedAmountInput">Amount Received</label>
                        <input type="text" name="received_amount" id="receivedAmountInput" class="form-control" value="${value.receiveamount}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
`);
                        });
                    }


                    ,
                error: function(xhr, status, error) {
                    console.log('Error: ' +
                        error); // Log error if AJAX request fails
                }
            });
        }

    });
    </script>

    @endsection