@extends('layout.default')
@section('content')
<form action="{{url('admin/account/save')}}" method="post" autocomplete="off" id="form_account">
    @csrf
    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header bg-pro">
            <h3 class="card-title color-white">Create Account</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-form-label">{{__('labels.NAME')}}<span class="error">*</span></label>
                    <input  type="text" name="account_name" class="form-control" placeholder="{{__('labels.NAME')}}"  value="{{old('account_name')}}" maxlength="30" autocomplete="new-account_name" required="">
                    @error('account_name') <span class="error">{{$message}}</span> @enderror
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-form-label">{{__('labels.FIRST_NAME')}}</label>
                    <input type="text" name="user_fname" class="form-control" placeholder="Name" value="{{old('user_fname')}}" maxlength="30">
                    @error('user_fname') <span class="error">{{$message}}</span> @enderror
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-form-label">{{__('labels.EMAIL')}}<span class="error">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Login {{__('labels.EMAIL')}}"  value="{{old('email')}}" maxlength="200" required="">
                    @error('email') <span class="error">{{$message}}</span> @enderror
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-form-label">{{__('labels.PASSWORD')}}<span class="error">*</span></label>
                    <div class="custom-pwd">
                        <input type="text" name="password" id="password" class="form-control" placeholder="{{__('labels.PASSWORD')}}"  value="<?php echo CommonHelper::randomString() ?>" minlength="8" maxlength="30" autocomplete="new-password" required="">
                        <i class="far fa-eye togglePassword"  onclick="showPassword()"></i>
                    </div>
                    @error('password') <span class="error">{{$message}}</span> @enderror
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-form-label">{{__('labels.ACCOUNT_TYPE')}}<span class="error">*</span></label>
                    <select name="account_type" class="trans-select form-control" required="">
                        <option value="">Select Account Type</option>
                        <option value="ent"  @if(old('account_type' ) == 'ent') selected="" @endif>Parent</option>
                        <option value="std" @if(old('account_type' ) == 'std') selected="" @endif>Standard</option>
                    </select>
                    @error('account_type') <span class="error">{{$message}}</span> @enderror
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-form-label">{{__('labels.STATUS')}}<span class="error">*</span></label>
                    <select  name="account_status" class="form-control" required="">
                        <option value="">Select Status</option>
                        <option value="N" @if(old('account_status' ) == 'N') selected="" @endif>Inactive</option>
                        <option value="Y" @if(old('account_status' ) == 'Y') selected="" @endif>Active</option>
                    </select>
                    @error('status') <span class="error">{{$message}}</span> @enderror
                </div> 
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-form-label">{{__('labels.ACCOUNT_TIME_ZONE')}}</label>
                    <select  name="account_timezone" class="trans-select form-control">
                        <option value="">Select Time Zone</option>
                        <option value="America/New_York">ET</option>
                        <option value="America/Chicago">CT</option>
                        <option value="America/Denver">MT</option>
                        <option value="America/Los_Angeles">PT</option>
                    </select>
                </div> 
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header bg-pro">
            <h3 class="card-title color-white">Subscription Details</h3>
        </div>
        <div class="card-body">
            <div class="row">                   
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>{{__('labels.BY_PASS_SUBSCRIPTION')}}
                        <span class="info_span">
                            <i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;padding-right: 18px;text-align: left" data-html="true" data-toggle="tooltip" data-placement="right" title="" data-original-title="<p style='text-align:left'>If checked, this client will not be asked to input a credit card and pay for any of their machine subscriptions through this software.</p>"></i>
                        </span>
                    </label> 
                    <p class="m-0"><input type="checkbox" name="bypass_subscription" id="bypass_subscription" class=""> Check to bypass credit card input by this account</p>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-form-label">{{__('labels.STRIPE_CUSTOMER_ID')}}</label>
                    <input  type="text" name="" class="form-control" placeholder=""  value="" readonly="">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" id="plans">
                    <label class="col-form-label">{{__('labels.PLAN')}}</label> 
                    <!-- <span class="info_span cursor-pointer" id="refreshPlan">
                        <i class="la la-refresh" aria-hidden="true" style="font-size: 20px;padding-right: 18px;text-align: left"></i>
                    </span> -->
                    <select name="plan_id" id="allplans" class="form-control">
                        <option value="">Select Plan</option>
                        @if(!empty($plans))
                            @foreach($plans as $plan)
                                <option value="{{$plan['price_identifier']}}">{{$plan['product_name']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
                            
    <div class="col-lg-12 text-center">
        <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
        <a href="{{url('home')}}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
    </div>
</form>
@endsection
@section('scripts')
    <script src="{{ asset('js/admin/account.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection