@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <style>
    </style>
@endsection

@section('content')
<form action="{{url('app/accounts/save')}}" method="post" autocomplete="off" id="form_account" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Create Sub Accounts</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.AccoutName')}} <span class="error">*</span></label>
                                    <input  type="text" name="account_name" class="form-control" value="{{old('account_name')}}" maxlength="30" required="">
                                    @error('account_name') <span class="error">{{$message}}</span> @enderror
                                </div>

                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.EMAIL')}} <span class="error">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{old('email')}}" maxlength="200" required="">
                                    @error('email') <span class="error">{{$message}}</span> @enderror
                                </div>
                                
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.PASSWORD')}} <span class="error">*</span></label>
                                    <div class="custom-pwd">
                                    <input type="password" name="password" id="password" class="form-control" value="" minlength="8" maxlength="30" required="">
                                        <i class="far fa-eye togglePassword"  onclick="showPassword()"></i>
                                    </div>
                                    @error('password') <span class="error">{{$message}}</span> @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
                        <a href="{{ url('app/accounts') }}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</form>

@endsection

@section('scripts')

    <script src="{{ asset('js/account/sub-accounts.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
    <script type="text/javascript">
        
    </script>
   
@endsection