@extends('admin.body.adminmaster')
 @section('content')
   <body class="inner_page login">
      <div class="full_container">
         <div class="container">
            <div class="center verticle_center full_height">
               <div class="login_section">
                  <div class="logo_login">
                      <div class="center">
                         <img width="75" height="75" src="{{ asset('images/logo/logo_icon.png') }}" alt="#" />
                         <h1 style="font-size:56px;  font-family: Georgia, serif; color:#f99f1b; margin-top:5px;">&nbsp;Gold<span style="color:#f26833;">Win</span></h1>
                     </div>
                  </div>
                  <div class="login_form">
                     <form action="{{route('update_password')}}" method="post">
                         @csrf
                        <fieldset>
                           <div class="field">
                              <label class="label_field">Terminal ID </label>
                              <input type="text" name="terminal_id" placeholder="Enter Terminal ID" />
                           </div>
                            <div class="field" style="position: relative;">
                              <label class="label_field">Password</label>
                              <input type="password" name="password" placeholder="Enter Old Password" value="{{ old('password') }}" style="padding-right: 40px;" />
                              <span id="togglePassword" style="position: absolute; right: 10px; top: 35%; cursor: pointer;">
                                  <i class="fas fa-eye" id="eyeIcon"></i> <!-- Font Awesome Eye Icon -->
                              </span>
                          </div>
                           <!--<div class="field">-->
                           <!--   <label class="label_field">Old Password</label>-->
                           <!--   <input type="password" name="password" placeholder="Enter Old Password" />-->
                           <!--</div>-->
                            <div class="field">
                              <label class="label_field">New Password</label>
                              <input type="password" name="new_password" placeholder="Enter New Password" />
                           </div>
                           <div class="field margin_0">
                              <label class="label_field hidden">hidden label</label>
                              <button  type="submit" name="submit" class="main_bt">Change Password</button>
                           </div>
                        </fieldset>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.querySelector('input[name="password"]');
    const eyeIcon = document.getElementById('eyeIcon');
    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>
@endsection


      