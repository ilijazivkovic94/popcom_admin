@extends('layout.default')

@section('styles')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

    <style>
        .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20rem; }
        .toggle.ios .toggle-handle { border-radius: 20rem; }

        .imageLogo{
            background-color: #000;
            text-align: center;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
<form action="{{ url('app/setting/update') }}" method="POST" autocomplete="off" id="form_account" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Settings</h3>
                </div>
                <div class="card-body">
                    <div class="row">   
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.LOGO')}} <span class="error">*</span></label>
                            
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="account_logo" class="custom-file-input" id="inputGroupFile01" accept="image/*" @if(empty($setting->accountSetting->account_logo)) required @endif>
                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>                            
                            </div>

                            <div class="imageLogo col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                @if($setting->accountSetting->account_logo != '')
                                    <img src="{{ $setting->accountSetting->account_logo }}" id="preview" style="width: 15%;">
                                @else
                                    <img src="" id="preview" style="width: 15%;">
                                @endif                        
                                <input type="hidden" id="fileFlag" value="" />
                            </div>

                        </div>

                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.OrganizationName')}}<span class="error">*</span></label>
                            <input type="text" name="account_org_name" class="form-control" value="{{ $setting->accountSetting->account_org_name }}" required="">
                        </div>

                        <div class="form-group col-md-3 col-lg-3 col-sm-6 col-xs-6">
                            <label class="col-form-label">{{__('labels.SurveyURL')}}</label>
                            <input type="text" name="account_survey_url" id="account_survey_url" class="form-control" value="{{ $setting->accountSetting->account_survey_url }}">
                        </div>

                        <div class="form-group col-md-3 col-lg-3 col-sm-6 col-xs-6">
                            <label class="col-form-label">{{__('labels.SurveysWyzerr')}}</label>
                            <a href="{{ Config::get('constants.SurveyLink') }}" target="_blank" class="btn btn-primary">Click here</a>
                        </div>

                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" style="display: block;">{{__('labels.FactorAuthentication')}}</label>
                            
                            <input id="userFact" type="checkbox" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-style="ios" data-width="120" @if ($setting->user_2fa_yn == 'Y') checked="checked" @endif>

                            <input type="hidden" name="user_2fa_yn" id="user_2fa_yn" value="{{ $setting->user_2fa_yn }}" />
                        </div>

                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.PrimaryName')}}<span class="error">*</span></label>
                            <input type="text" name="account_poc" class="form-control" value="{{ $setting->accountSetting->account_poc }}" required="">
                        </div>

                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.TIMEZONE')}}<span class="error">*</span></label>
                            <select name="account_timezone" id="account_timezone" class="form-control" required="">
                                <!-- <option value="">Select Time Zone</option> -->
                                <option value="America/New_York" @if ($setting->accountSetting->account_timezone == 'America/New_York') selected @endif>ET</option>
                                <option value="America/Chicago" @if ($setting->accountSetting->account_timezone == 'America/Chicago') selected @endif>CT</option>
                                <option value="America/Denver" @if ($setting->accountSetting->account_timezone == 'America/Denver') selected @endif>MT</option>
                                <option value="America/Los_Angeles" @if ($setting->accountSetting->account_timezone == 'America/Los_Angeles') selected @endif>PT</option>
                            </select>
                        </div>

                        @if ($setting->accountDetails->account_type == 'ent')
                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                <h5>{{__('labels.Advertisement')}}</h5>
                                <div class="row">                               

                                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <label class="col-form-label">{{__('labels.AdvertAllowAdd')}}</label>
                                        <select name="ads_create" id="ads_create" class="form-control">
                                            <option value="">Select Options</option>
                                            <option value="Y" {{ $setting->accountSubSetting->ads_create == 'Y' ? 'selected="selected"' :'' }} >Yes</option>
                                            <option value="N" {{ $setting->accountSubSetting->ads_create == 'N' ? 'selected="selected"' :'' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <label class="col-form-label">{{__('labels.AdvertAllowStatus')}}</label>
                                        <select name="ads_status" id="ads_status" class="form-control">
                                            <option value="">Select Options</option>
                                            <option value="Y" {{ $setting->accountSubSetting->ads_status == 'Y' ? 'selected="selected"' :'' }} >Yes</option>
                                            <option value="N" {{ $setting->accountSubSetting->ads_status == 'N' ? 'selected="selected"' :'' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <label class="col-form-label">{{__('labels.AdvertAllowGender')}}</label>
                                        <select name="ads_gender" id="ads_gender" class="form-control">
                                            <option value="">Select Options</option>
                                            <option value="Y" {{ $setting->accountSubSetting->ads_gender == 'Y' ? 'selected="selected"' :'' }} >Yes</option>
                                            <option value="N" {{ $setting->accountSubSetting->ads_gender == 'N' ? 'selected="selected"' :'' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <label class="col-form-label">{{__('labels.AdvertAllowAge')}}</label>
                                        <select name="ads_age" id="ads_age" class="form-control">
                                            <option value="">Select Options</option>
                                            <option value="Y" {{ $setting->accountSubSetting->ads_age == 'Y' ? 'selected="selected"' :'' }} >Yes</option>
                                            <option value="N" {{ $setting->accountSubSetting->ads_age == 'N' ? 'selected="selected"' :'' }}>No</option>
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                <h5>{{__('labels.ProductSetting')}}</h5>
                                <div class="row">                               

                                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <label class="col-form-label">{{__('labels.ProductAllowAdd')}}</label>
                                        <select name="products_create" id="products_create" class="form-control">
                                            <option value="">Select Options</option>
                                            <option value="Y" {{ $setting->accountSubSetting->products_create == 'Y' ? 'selected="selected"' :'' }} >Yes</option>
                                            <option value="N" {{ $setting->accountSubSetting->products_create == 'N' ? 'selected="selected"' :'' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <label class="col-form-label">{{__('labels.ProductAllowUpdatName')}}</label>
                                        <select name="products_name" id="products_name" class="form-control">
                                            <option value="">Select Options</option>
                                            <option value="Y" {{ $setting->accountSubSetting->products_name == 'Y' ? 'selected="selected"' :'' }} >Yes</option>
                                            <option value="N" {{ $setting->accountSubSetting->products_name == 'N' ? 'selected="selected"' :'' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <label class="col-form-label">{{__('labels.ProductAllowUpdatPric')}}</label>
                                        <select name="products_price" id="products_price" class="form-control">
                                            <option value="">Select Options</option>
                                            <option value="Y" {{ $setting->accountSubSetting->products_price == 'Y' ? 'selected="selected"' :'' }} >Yes</option>
                                            <option value="N" {{ $setting->accountSubSetting->products_price == 'N' ? 'selected="selected"' :'' }}>No</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        @else
                            <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <p class="mb-0">The following contact information will be displayed on your machines for your customers should they need to contact you. At least one field, email or phone, is required.</p>
                            </div>

                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                <label class="col-form-label">{{__('labels.AccountContactEmail')}}<span class="error">*</span></label>
                                <span class="float-right mt-2 edit_sender_email" style="cursor: pointer;">
                                    <i class="la la-pencil-alt"></i>
                                </span>
                                <input type="email" name="account_contact_email" id="account_contact_email" class="form-control mygroup" value="{{ $setting->accountSetting->account_contact_email }}" disabled>
                            </div>

                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                <label class="col-form-label">{{__('labels.AccountContactPhone')}}</label>
                                <input type="text" name="account_contact_phone" id="account_contact_phone" class="form-control mygroup" value="{{ $setting->accountSetting->account_contact_phone }}">
                            </div>
                        @endif        
                        
                        @if ($setting->accountDetails->account_type == 'sub')
                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                <h5>{{__('labels.Advertisement')}}</h5>
                                <label class="col-form-label" style="display: block;">Can add my own Advertisements: {{$SubSetting->ads_create == 'Y' ? 'Yes' : 'No' }}</label>
                                <label class="col-form-label" style="display: block;">Can change a Parent Advertisement Status: {{$SubSetting->ads_status == 'Y' ? 'Yes' : 'No' }}</label>
                                <label class="col-form-label" style="display: block;">Can change a Parent Advertisement Gender: {{$SubSetting->ads_gender == 'Y' ? 'Yes' : 'No' }}</label>
                                <label class="col-form-label" style="display: block;">Can change a Parent Advertisement Age: {{$SubSetting->ads_age == 'Y' ? 'Yes' : 'No' }}</label>
                            </div>

                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                <h5>{{__('labels.ProductSetting')}}</h5>
                                <label class="col-form-label" style="display: block;">Can add my own Products: {{$SubSetting->products_create == 'Y' ? 'Yes' : 'No' }}</label>
                                <label class="col-form-label" style="display: block;">Can change a Parent Product Name: {{$SubSetting->products_name == 'Y' ? 'Yes' : 'No' }}</label>
                                <label class="col-form-label" style="display: block;">Can change a Parent Product Price: {{$SubSetting->products_price == 'Y' ? 'Yes' : 'No' }}</label>
                            </div>
                        @endif                        
                        
                    </div>
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('labels.BUPDATE')}}</button>
                        <a href="{{ url('home') }}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</form>

<!-- Modal-->
<div class="modal fade" id="FactModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Set as Two Factor Authentication Number</h5>
            </div>
            <div class="modal-body">
                <p>Please a enter the mobile number you want to assign to Two-Factor Authentication.</p>
                <div id="step1" class="steps" >
                    <div class='form-group'>
                        <label class="col-form-label"><strong>{{__('labels.PhoneNumber')}}</strong><span class="error">*</span></label>
                        <div class="row">
                            <div class="col-md-4 no-ls-padding">
                                <select id='country_code' name="country_code"  class='form-control' >
                                <?php foreach($countryCode as $code => $country){
                                    $countryName = ucwords(strtolower($country["name"])); // Making it look good
                                    $select_text =  "";
                                    if($country["code"] == $setting->accountSetting->country_code) {
                                        $select_text = "selected";
                                    }
                                    echo "<option value='".$country["code"]."' ".$select_text."  >(+".$country["code"].") ".$countryName."</option>";
                                } ?>
                                </select>                        
                            </div> 

                            <div class="col-md-8 no-rs-padding">
                                <input type="text" maxlength="10" id="user_phone_no" name="user_phone_no" placeholder="Phone Number" class="form-control" value="{{ $setting->user_phone_no }}" >
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class="col-form-label"><strong>{{__('labels.CurrentPassword')}}</strong><span class="error">*</span></label>
                        <input type='password' id='current_password' name="current_password" class='form-control' placeholder="Current Password" autocomplete="off"/>
                    </div>
                </div>

                <div id="step2">
                    <div class='form-group'>
                        <label class="col-form-label">Enter OTP <span class="error">*</span></label>
                        <input type='number' maxlength="6" id='user_otp' class='form-control' placeholder="Enter OTP" />
                    </div>
                </div>           

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary closeFact" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="update_number">Update</button>
                <button type="button" class="btn btn-primary" id="check_otp">Enable Authentication</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    {{-- <script src="{{ asset('public/assets/js/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script> --}}

    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    <script src="{{ asset('js/account/setting.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>

    <script type="text/javascript">
        @if(empty($setting->accountSetting->account_logo))
            $('#preview').hide();
        @else 
            $('#preview').show();
        @endif
    </script>
@endsection