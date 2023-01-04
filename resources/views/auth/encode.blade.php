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
                                <h3>Password Encode</h3>
                                <div class="text-muted font-weight-bold"></div>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ url('password-update') }}" autocomplete="off" id="loginForm">
                                    @csrf
                                    <div class="form-group">   
                                        <label class="col-form-label">Old Password <span class="error">*</span></label>        
                                        <textarea class="form-control" name="old_password" required="">{{ $userData }}</textarea>
                                    </div>
                            
                                    <div class="form-group"> 
                                        <label class="col-form-label">New Password <span class="error">*</span></label>                                       
                                        <textarea class="form-control" name="new_password" required=""></textarea>
                                    </div>
                                    
                                    <div class="row ">
                                        <div class="col-md-8 offset-md-2 text-center">
                                           <button type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4" id="kt_login_signin_submit">
                                                {{ __('labels.BUPDATE') }}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <div>
                                            <label style="font-size: 14px;">
                                                <span style='color:navy;font-weight:bold'>{{__('labels.AdvertisementVideo')}}:</span>
                                            </label>
                                            <ul>
                                                <li>
                                                    Please copy old password.
                                                </li>                                           
                                                <li>
                                                    Go to this link: <a href="https://www.md5online.org/md5-list-decrypter.html" >Online MD5 </a>
                                                </li>
                                                <li>
                                                    Check Mail.
                                                </li>
                                                <li>
                                                    New Password: Past Password here.
                                                </li>
                                            </ul>                                        
                                        </div>
                                        <span id="image-error" class=""></span>
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
