<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('media/logos/logo.png') }}" />

    {{-- Fonts --}}
    {{ Metronic::getGoogleFontsInclude() }}

    {{-- Global Theme Styles (used by all pages) --}}
    @foreach(config('layout.resources.css') as $style)
        <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($style)) : asset($style) }}?v={{ config('constants.WEB_VERSION') }}" rel="stylesheet" type="text/css"/>
    @endforeach

    {{-- Layout Themes (used by all pages) --}}
    @foreach (Metronic::initThemes() as $theme)
        <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($theme)) : asset($theme) }}?v={{ config('constants.WEB_VERSION') }}" rel="stylesheet" type="text/css"/>
    @endforeach

    {{-- Includable CSS --}}
    @yield('styles')
    @toastr_css
</head>
<body style="background: #6a4ee1">
    <div id="app">
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <div class="col-md-12 text-center">
                            <img src="{{url('media/logos/logo.png')}}" width="180px" />
                        </div>
                        <div class="card  my-4">
                            
                            <div class="card-header text-center">
                                <h3>Sign In</h3>
                                <div class="text-muted font-weight-bold">Enter your details to login to your account</div>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('login') }}" autocomplete="off" id="loginForm">
                                    @csrf
                                    <div class="input-group mb-5 fv-plugins-icon-container">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text px-3">
                                                <i class="la la-envelope"></i>
                                            </span>
                                        </div>

                                        <input class="form-control py-4 px-4 @error('email') is-invalid @enderror" type="email" placeholder="Email" name="email" autocomplete="off" value="{{ old('email') }}" required="" >
                                        <div class="fv-plugins-message-container"></div>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                            
                                    <div class="input-group mb-5 fv-plugins-icon-container">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text px-3">
                                                <i class="la la-lock"></i>
                                            </span>
                                        </div>

                                        <input id="password" type="password" class="form-control py-4 px-4 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password" >
                                        <div class="fv-plugins-message-container"></div>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                        <div class="checkbox-inline">
                                            <label class="checkbox m-0 text-muted">
                                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span></span>Remember me</label>
                                        </div>
                                        <!-- <a href="{{ route('password.request') }}" id="kt_login_forgot" class="text-muted text-hover-primary">Forgot Password?</a> -->
                                    </div>

                                    <div class="row ">
                                        <div class="col-md-8 offset-md-2 text-center">
                                           <button type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4" id="kt_login_signin_submit">
                                                {{ __('Login') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-12 text-center color-white">
                            <a href="#" class="color-white copyright" target="_blank">POPCOM</a> &copy; 2021. All Rights Reserved.
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Global Theme JS Bundle (used by all pages)  --}}
    @foreach(config('layout.resources.js') as $script)
        <script src="{{ asset($script) }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
    @endforeach

    {{-- Includable JS --}}
    @yield('scripts')
    
    @toastr_js
    @toastr_render
    
    <script src="{{url('plugins/jquery-validation/jquery.validate.js')}}" type="text/javascript"></script>
    <script src="{{url('plugins/jquery-validation/additional-methods.js')}}" type="text/javascript"></script>
    
    <script>        
        $(document).ready(function () {
            jQuery.validator.addMethod("validate_email", function(value, element) {
                if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
                    return true;
                } else {
                    return false;
                }
            }, "Please enter valid email");

            $('#loginForm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                        validate_email: true,
                    },
                    password: {
                        required: true,
                    },
                },
                messages: {
                    email: {
                        required: "Please enter email address",
                        email: "Please enter valid email",
                        validate_email: "Please enter valid email",
                    },
                    password:{  
                        required:  "Please enter password",
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                  error.addClass('invalid-feedback');
                  element.closest('.input-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                }
            });
        });
    </script>
</body>
</html>
