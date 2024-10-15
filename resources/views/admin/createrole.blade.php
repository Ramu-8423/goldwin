@extends('admin.body.adminmaster')

@section('content')
@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif
<div class="full_container mt-4">
    <div class="card p-4">
        <div class="text-center">
            <h3 class="text-primary mt-4">Add Roles</h3>
        </div>
        <div class="row mt-4">
            <div class="col-sm-6">
                <form action="{{ route('store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Pin</label>
                        <input type="text" class="form-control" id="terminal_id" name="terminal_id"
                            value="{{ $creator_id->terminal_id ?? '' }}" required>
                    </div>
                    @if($errors->has('terminal_id'))
                    <div class="error text-danger">{{ $errors->first('terminal_id') }}</div>
                    @endif
                    <label for="role">Role Select</label>
                    <select class="form-control" name="role_id" id="role_id">
                        <option selected value="">Choose...</option>
                        @if($role->role_id == 1)
                        <!-- Admin -->
                        <option value="2">Stockist</option>
                        <option value="3">SubStockist</option>
                        <option value="4">User</option>
                        @elseif($role->role_id == 2)
                        <!-- Stockist -->
                        <option value="3">SubStockist</option>
                        <option value="4">User</option>
                        @elseif($role->role_id == 3 || $role->role_id == 4)
                        <!-- SubStockist or User -->
                        <option value="4">User</option>
                        @endif
                    </select>
                    @if($errors->has('role_id'))
                    <div class="error  text-danger">{{ $errors->first('role_id') }}</div>
                    @endif
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="input-group-append">
                            <span class="input-group-text" onclick="togglePasswordVisibility()">
                                <i id="toggleIcon" class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="hidden" name="createdby" value="{{$authid ?? 0}}"
                        required>
                </div>

                @if($errors->has('password'))
                <div class="error  text-danger">{{ $errors->first('password') }}</div>
                @endif
                <!-- Inside your form, the relevant part will look like this -->
                <div class="form-group">
                    <label for="under_role">Select Under Role</label>
                    <select class="form-control" name="under_role_terminal_id" id="under_role_terminal_id" value="xyz">
                        <option selected value="">Choose KeyNames</option>
                        @if($role->role_id == 2)
                        <!-- Stockist -->
                        <!-- Stockist can see a list of all SubStockists when creating a User -->
                        @endif
                        @if($role->role_id == 1)
                        <!-- Stockist -->
                        <!-- Stockist can see a list of all SubStockists when creating a User -->
                        @endif
                    </select>
                    @if($errors->has('password'))
                    <div class="error  text-danger">{{ $errors->first('under_role_terminal_id') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary">Add Role</button>
        </div>
        </form>
    </div>
</div>
</div>

<!-- Add the AJAX script to dynamically populate the terminal list -->
<script>
$(document).ready(function() {
    $('#under_role_terminal_id').select2({
        placeholder: "Choose KeyNames",
        allowClear: true
    });

    $('#role_id').change(function() {
        var roleId = $(this).val(); //selected role value
        var loggedInRoleId = "{{ $role->role_id }}";  //login person role
        var auth = "{{$authid}}"; // login person id

        $('#under_role_terminal_id').empty().prop('disabled', false); // Allow selecting

      console.log(`checking roleid is - ${roleId} and loggedInRoleId is ${loggedInRoleId} and auth is is ${auth}`);
       
        if (roleId) {
            if (roleId == 3 && loggedInRoleId == 2) {
                // Stockist creating SubStockist, show their terminal_id without pre-selection
                var roleCreatorId = "{{ $creator_id->terminal_id }}";
                $('#under_role_terminal_id').append('<option value="' + roleCreatorId + '">' +
                    roleCreatorId + '</option>');
            } else if ((roleId == 4 && loggedInRoleId == 2) || (roleId == 4 && loggedInRoleId == 1) || (
                    roleId == 3 && loggedInRoleId == 1)) {
                // Stockist creating User, show SubStockist terminals for selection
                $.ajax({
                    url: '{{ route("getTerminals") }}',
                    type: 'POST',
                    data: {
                        role_id: roleId,
                        logged_in_role_id: loggedInRoleId,
                        auth: auth, // Auth ID yahan bhej rahe hain
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $('#under_role_terminal_id').append(
                                '<option value="">Choose KeyNames</option>');
                            $.each(data, function(index, value) {
                                $('#under_role_terminal_id').append(
                                    '<option value="' + value + '">' + value +
                                    '</option>');
                            });
                        } else {
                            $('#under_role_terminal_id').append(
                                '<option>No Terminals Available</option>');
                        }
                    }
                });
            } else {
                $.ajax({
                    url: '{{ route("getTerminals") }}',
                    type: 'POST',
                    data: {
                        role_id: roleId,
                        logged_in_role_id: loggedInRoleId,
                        auth: auth, // Auth ID yahan bhej rahe hain
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(index, value) {
                                $('#under_role_terminal_id').append(
                                    '<option value="' + value + '">' + value +
                                    '</option>');
                            });
                        } else {
                            $('#under_role_terminal_id').append(
                                '<option>No Terminals Available</option>');
                        }
                    }
                });
            }
        }
    });
});
</script>
<script>
function togglePasswordVisibility() {
    var passwordField = document.getElementById('password');
    var toggleIcon = document.getElementById('toggleIcon');

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = "password";
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection

@section('scripts')

@endsection