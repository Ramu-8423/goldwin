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
                        <div class="container">
                           <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <div class="container-fluid">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex" style="gap: 20px;">
                                <li class="nav-item">
                                    <a class=" " href="{{ route('user.pending',2) }}">
                                        <i class="mdi mdi-view-dashboard"></i>
                                        <span>Pendings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="" href="{{ route('user.pending',3) }}">
                                        <i class="mdi mdi-view-dashboard"></i>
                                        <span>Reject</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                     </nav>


               </div>
                    </div>
                </div>
                <div class="table_section padding_infor_info">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                         <th>Id</th>
                                        <th>Role</th>
                                        <th>PIN</th>
                                        <th>PASSWORD</th>
                                        <th>Under Stockist</th>
                                        <th>Under Substockist</th>
                                         <th>STATUS</th>
                                        <th>CREATED DATE</th>
                            </thead>
                            <tbody class="tdata">
                                @forelse($user as $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td> @if($admin->role_id == 1)
                                              <b class="text-primary">Admin</b>
                                          @elseif($admin->role_id == 2)
                                              <b class="text-primary">Stockist</b>
                                          @elseif($admin->role_id == 3)
                                              <b class="text-primary">Substockist</b>
                                          @elseif($admin->role_id == 4)
                                              <b class="text-primary">User</b>
                                          @endif</td>
                                    
                                    <td>{{ $admin->terminal_id }}</td>
                                    <td>{{ $admin->password }}</td>
                                    <td>{{ $admin->inside_stockist }}</td>
                                    <td>{{ $admin->inside_substockist }}</td>
                                   <td class="text-center">
    <div class="dropdown">
        @if($admin->status==3)
        <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton{{ $admin->id }}" data-bs-toggle="dropdown" aria-expanded="false">
            Rejected
        </button>
        @elseif($admin->status==2)
        
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton{{ $admin->id }}" data-bs-toggle="dropdown" aria-expanded="false">
            Pending
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $admin->id }}">
            <!-- Active button -->
            <li>
                <form action="{{ route('admins.updateStatus', $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="1">
                    <button class="dropdown-item" type="submit">Active</button>
                </form>
            </li>

            <!-- Reject button -->
            <li>
                <form action="{{ route('admins.updateStatus', $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="3">
                    <button class="dropdown-item" type="submit">Reject</button>
                </form>
            </li>
        </ul>
        @endif
    </div>
</td>
                                   <td>{{ $admin->created_at }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <marquee behavior="alternate" direction="">
                                            <span style="color:red;">!!</span> 
                                            <span style="color:black;">No pending Here</span> 
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
