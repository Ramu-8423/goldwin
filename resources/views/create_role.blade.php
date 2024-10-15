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
                <form action="{{ route('add_role') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Pin</label>
                        <input type="text" class="form-control" id="terminal_id" name="terminal_id" required>
                    </div>
                    <div class="error text-danger">{{ $errors->first('terminal_id') }}</div>
                    
                    <label for="role">Role Select</label>
                    <select class="form-control" name="role_id" id="role_id" required>
                        <option selected value="">Choose...</option>
                        @if($login_role_id==1)
                        <option value="2">Stockist</option>
                        <option value="3">SubStockist</option>
                        <option value="4">User</option>
                        @elseif($login_role_id==2)
                           <option value="3">SubStockist</option>
                           <option value="4">User</option>
                        @elseif($login_role_id==3) 
                            <option value="4">User</option>
                        @endif    
                    </select>
                    <div class="error text-danger">{{ $errors->first('role_id') }}</div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="error text-danger">{{ $errors->first('password') }}</div>
                    
                    <div class="form-group">
                        <label for="under_role">Select Under Role</label>
                        <select class="form-control" name="under_role_terminal_id" id="under_role_terminal_id" required>
                            <option selected value="">Choose...</option>
                        </select>
                        <div class="error text-danger">{{ $errors->first('under_role_terminal_id') }}</div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" name="submit" class="btn btn-primary">Add Role</button>
            </div>
        </form>
    </div>
</div>

<!-- Include Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 on the under_role_terminal_id dropdown
        $('#under_role_terminal_id').select2({
            placeholder: "Choose...",
            allowClear: true
        });

        // Function to handle selected role
        function selectedRole() {
            var select = document.getElementById('role_id');
            var selectedRole = select.options[select.selectedIndex].value;
            var data = {
                role_id: selectedRole || '0'
            };

            fetch('/create_role_code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                var underRoleSelect = $('#under_role_terminal_id');
                underRoleSelect.empty().append('<option selected value="">Choose...</option>');
                
                result.data.forEach(function(item) {
                    var option = new Option(item.terminal_id, item.terminal_id, false, false);
                    underRoleSelect.append(option).trigger('change');
                });
            })
            .catch(error => console.error('Error:', error));
        }

        $('#role_id').change(selectedRole);
    });
</script>

@endsection
