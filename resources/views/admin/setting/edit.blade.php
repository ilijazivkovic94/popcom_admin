@extends('layout.default')
@section('content')
<form action="{{url('admin/global-setting')}}" method="post" autocomplete="off" id="form_account" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Global Settings</h3>
                </div>
                <div class="card-body">
                    <div class="row">   
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.SVI_LOGO')}} <span class="error">*</span></label>
                            <div class="input-group">
                               <!--  <div class="input-group-prepend">
                                    <span class="input-group-text">Upload <span style="color: red;">*</span></span>
                                </div> -->
                                <div class="custom-file">
                                    <input type="file" name="svi_logo" class="custom-file-input" id="inputGroupFile01" accept="image/*">
                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>                            
                            </div>
                            @if ($setting['svi_logo']!='')
                                <img src="{{ $setting['svi_logo'] }}" id="preview" style="width: 20%; border: 1px solid #ccc; margin-top: 10px">
                            @else
                                <img src="" id="preview" style="width: 20%; border: 1px solid #ccc; margin-top: 10px">
                            @endif                        
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.IAM_USER')}} <span class="error">*</span></label>
                            <input  type="text" name="iam_user" class="form-control" value="{{$setting['iam_user']}}" maxlength="50" autocomplete="" required="">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.IAM_ACCESS_KEY')}} <span class="error">*</span></label>
                            <input type="text" name="iam_access_key" class="form-control" maxlength="50" value="{{$setting['iam_access_key']}}" required="">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.IAM_SECRET_KEY')}} <span class="error">*</span></label>
                            <input type="text" name="iam_secret_key" class="form-control" maxlength="50" required="" value="{{$setting['iam_secret_key']}}">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.S3_BUCKET')}} <span class="error">*</span></label>
                            <input type="text" name="s3_bucket" class="form-control" maxlength="50" required="" value="{{$setting['s3_bucket']}}">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.SES_EMAIL')}} <span class="error">*</span></label>
                            <input type="email" name="ses_email" class="form-control" placeholder="" maxlength="50" autocomplete="" required="" value="{{$setting['ses_email']}}">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.SES_PASSWORD')}} <span class="error">*</span></label>
                            <input type="text" name="ses_password" class="form-control" maxlength="50" required="" value="{{$setting['ses_password']}}">
                        </div>
                       <!--  <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.STRIPE_FEE')}} <span class="error">*</span></label>
                            <input type="text" name="stripe_fee" class="form-control" maxlength="50" required="" value="{{$setting['stripe_fee']}}">
                        </div> -->
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.AWS_REGION')}} <span class="error">*</span></label>
                            <input type="text" name="aws_region" class="form-control" maxlength="50" required="" value="{{$setting['aws_region']}}">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.VENDING_BAY_COUNT_S')}} <span class="error">*</span></label>
                            <input type="text" name="vending_bay_count" class="form-control" maxlength="50" required="" value="{{$setting['vending_bay_count']}}">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.STRIPE_SECRET_KEY')}} <span class="error">*</span></label>
                            <input type="text" name="stripe_secret_key" class="form-control" maxlength="50" required="" value="{{$setting['stripe_secret_key']}}">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.STRIPE_PUBLISH_KEY')}} <span class="error">*</span></label>
                            <input type="text" name="stripe_publish_key" class="form-control" maxlength="50" required="" value="{{$setting['stripe_publish_key']}}">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"> {{__('labels.ADMIN_MACHINE_PIN_S')}} <span class="error">*</span></label>
                            <input type="text" name="admin_machine_pin" class="form-control" maxlength="50" required="" value="{{$setting['admin_machine_pin']}}">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"> {{__('labels.ADMIN_EMAIL')}} <span class="error">*</span></label>
                            <input type="email" name="admin_email" class="form-control" maxlength="50" required="" value="{{$setting['admin_email']}}">
                        </div>
                    </div>
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('labels.BUPDATE')}}</button>
                        <a href="{{url('home')}}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</form>
@endsection
@section('scripts')
    <script src="{{ asset('js/admin/account.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection