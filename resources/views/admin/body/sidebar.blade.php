<div class="full_container">
    <div class="inner_container">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar_blog_1">
                <div class="sidebar-header">
                    <div class="logo_section">
                        <a href="index.html"><img class="logo_icon img-responsive" src="{{ asset('images/logo/logo_icon.png') }}" alt="#" /></a>
                    </div>
                </div>
                <div class="sidebar_user_info">
                    <div class="icon_setting"></div>
                    <div class="user_profle_side">
                        <div class="user_img"><img class="img-responsive" src="{{asset('images/layout_img/user_img.jpg')}}" alt="#" /></div>
                        <div class="user_info">
                            <h6> {{ Session::get('terminal_id') }}</h6>
                            <p><span class="online_animation"></span> Online</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar_blog_2">
                <h4>General</h4>
                <ul class="list-unstyled components">
                    <li class="active">
                        <a href="{{route('admin.calculation')}}" aria-expanded="false">
                            <i class="fa fa-dashboard yellow_color"></i> <span>Dashboard</span>
                        </a>
                    </li>
   
                    <li class="active">
                        <a href="{{route('stokistlist')}}" aria-expanded="false">
                            <i class="fa fa-user purple_color"></i> <span>Users</span>
                        </a>
                    </li>
                  @php
                       $role = Session::get('role_id');
                  @endphp
                  @if($role == 1)
                      <li class="active">
                          <a href="{{ route('prediction.12card5') }}" aria-expanded="false">
                              <i class="fa fa-diamond purple_color"></i> <span>Manage 12card5</span>
                          </a>
                      </li>
                 
                    <li class="active">
                        <a href="{{route('prediction_history')}}" aria-expanded="false">
                            <i class="fa fa-history purple_color"></i> <span>Prediction History</span>
                        </a>
                    </li>
                     
                     @endif
                    <li class="active">
                        <a href="{{route('result_history')}}" aria-expanded="false">
                            <i class="fa fa-history purple_color"></i> <span>Result History</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{route('admin.bethistory')}}" aria-expanded="false">
                            <i class="fa fa-paper-plane red_color"></i> <span>Bet History</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('admin.password')}}">
                            <i class="fa fa-lock orange_color"></i> <span>Update Password</span>
                        </a>
                    </li>
                     @if($role == 1)
                      <li class="active">
                          <a href="{{ route('user.pending',2) }}" aria-expanded="false">
                              <i class="fa fa-user yellow_color"></i> <span>Pending users</span>
                          </a>
                      </li>
                     @endif
                      {{--
                    <li>
                        <a href="{{route('admin.addmoney')}}">
                            <i class="fa fa-money orange_color"></i> <span>Money</span>
                        </a>
                    </li>
                   
                
                     <!--role_id 4 ke liye nhi dikhega create role-->
                     --}}
                      @if($role!=4)
                    <li>
                        <a href="{{route('create_role')}}">
                            <i class="fa fa-user orange_color"></i> <span>Create Role</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>

<!-- Bootstrap JS -->
@section('scripts')
<script>
function handleStockistClick(event) {
    event.preventDefault(); // Default action ko rokne ke liye
    const url = '{{ route('stokistlist') }}'; // Aapka route yahan hai
    window.location.href = url; // Redirect karne ke liye
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@endsection
