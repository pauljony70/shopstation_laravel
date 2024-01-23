<!-- resources/views/Admin/Auth/login.blade.php -->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Log In - {{config('app.name')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('images/icons-big-size.ico') }}">

    		<!-- App css -->
    		<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    		<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

    		<link href="{{ asset('assets/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    		<link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet"  disabled />

    		<!-- icons -->
    		<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    </head>

    <body class="authentication-bg authentication-bg-pattern">

      <div class="account-pages mt-5 mb-5">
          <div class="container">
              <div class="row justify-content-center">
                  <div class="col-md-8 col-lg-6 col-xl-5">
                      <div class="card bg-pattern">

                          <div class="card-body p-4">
                              <div class="text-center w-75 m-auto">
                                    <div class="auth-logo">
                                        <a href="index.html" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                               <img src="{{ asset('images/logo.png') }}" alt="" height="40">
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Enter your email and password to access account.</p>
                                </div>

                              <!-- form -->
                              <form id="loginForm" class="form-horizontal m-t-30" action="{{ route('admin.login') }}" method="POST">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        Invalid User details!!!
                                    </div>
                                @endif
                                
                                @if (!empty(Session::get('erro_login')))
                                  <div class="alert alert-danger">
                                      {{Session::get('erro_login')}}
                                  </div>
                                @endif

                                  <div class="form-group mb-3">
                                      <label for="emailaddress">Email</label>
                                      <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" required data-parsley-required-message="Please enter your email" autocomplete="email" autofocus>
                                  </div>

                                  <div class="form-group mb-3">
                                      <label for="password">Password</label>
                                      <div class="input-group input-group-merge">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required data-parsley-required-message="Please enter your password" data-parsley-errors-container="#pass-error" autocomplete="current-password">
                                          <div class="input-group-append" data-password="false">
                                              <div class="input-group-text">
                                                  <span class="password-eye"></span>
                                              </div>
                                          </div>

                                      </div>
                                      <div id="pass-error" role="alert"></div>
                                  </div>

                                  <div class="form-group mb-0 text-center">
                                      <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                                  </div>
                                  
                                  @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                  @endif

                              </form>
                              <!-- end form-->

                          </div> <!-- end card-body -->
                      </div>
                      <!-- end card -->

                  </div> <!-- end col -->
              </div>
              <!-- end row -->
          </div>
          <!-- end container -->
      </div>
      <!-- end page -->

        <!-- Vendor js -->
        <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
        <script src="{{ asset('assets/libs/parsleyjs/parsley.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.min.js') }}"></script>
        <script type="text/javascript">
          $(document).ready(function() {
            $('#loginForm').parsley();
          });
        </script>

    </body>
</html>