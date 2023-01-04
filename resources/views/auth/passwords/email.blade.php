<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('media/logos/logo-blue.png') }}" />

    {{-- Fonts --}}
    {{ Metronic::getGoogleFontsInclude() }}

    <!-- Styles -->
    <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet">
</head>
<body style="background: #17a2b8">
    <div id="app">
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5 div_center">
                        <div class="col-md-12 text-center">
                            <img src="{{url('media/logos/logo.png')}}" width="120px" />
                        </div>
                        <div class="card my-9">
                            <div class="card-header text-center"><h3>{{ __('Reset Password') }}</h3></div>

                            <div class="card-body">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf

                                    <div class="form-group mb-5 fv-plugins-icon-container">
                                        <!-- <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label> -->
                                            <input id="email" type="email" class="form-control h-auto form-control-solid py-4 px-8 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Send Password Reset Link') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12 text-center color-white">
                            <a href="#" class="color-white copyright" target="_blank">Dental Training</a> &copy; 2021. All Rights Reserved.
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
