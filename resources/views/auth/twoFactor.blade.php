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
                            <img src="{{url('media/logos/logo.png')}}" width="120px" />
                        </div>

                        <div class="card  my-4">
                            <div class="card-header text-center">
                                <h3>Two Factor Verification</h3>

                                <div class="text-muted font-weight-bold">A one-time password has been sent to your phone number. Please enter it below to continue your login. If you haven't received it, click <a href="{{ route('verify.resend') }}">here</a>.</div>
                            </div>

                            <div class="card-body">
                                
                                @if(session()->has('message'))
                                    <p class="alert alert-success"> {{ session()->get('message') }} </p>
                                @endif
                                
                                <form method="POST" action="{{ route('verify.store') }}" autocomplete="off" id="loginForm">
                                    @csrf
                                    
                                    <div class="input-group mb-5 fv-plugins-icon-container">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text px-3">
                                                <i class="la la-lock"></i>
                                            </span>
                                        </div>
                                        
                                        <input class="form-control py-4 px-4 @error('two_factor_code') is-invalid @enderror" type="text" placeholder="One-time password" name="two_factor_code" autocomplete="off" value="{{ old('two_factor_code') }}" required="" maxlength="6" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        
                                        <div class="fv-plugins-message-container"></div>
                                        
                                        @error('two_factor_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row ">
                                        <div class="col-md-8 offset-md-2 text-center">
                                           <button type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4" id="kt_login_signin_submit">
                                                Verify
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
            $('#loginForm').validate({
                rules: {
                    two_factor_code: {
                        required: true,
                    },
                },
                messages: {
                    two_factor_code: {
                        required: "Please enter verification code",
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
