@extends('layout.default')
@section('content')
<form action="{{url('admin/machine/save')}}" method="post" autocomplete="off" id="form">
    @csrf
    <input type="hidden" name="account_id" value="{{$user->account_id}}">
    <input type="hidden" name="id" value="{{$user->id}}">
    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header bg-pro">
            <h3 class="card-title color-white">Account List > @if($user->accountSetting->account_poc!='') {{$user->accountSetting->account_poc}} @else {{$user->email}} @endif > Create Machine</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.MACHINE_NAME')}}<span class="error">*</span> <span class="info_span"><i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;" data-toggle="tooltip" data-placement="right" title="" data-original-title="Machine name must be unique across all PopCom machines."></i></span></label>
                    <input  type="text" name="kiosk_identifier" class="form-control" placeholder="{{__('labels.MACHINE_NAME')}}"  value="{{old('kiosk_identifier')}}" maxlength="30" autocomplete="new-kiosk_identifier" required="">
                </div>

                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.PASSWORD')}}<span class="error">*</span></label>
                     <span class="float-right mt-2" onclick="randomPassword();" style="cursor: pointer;">
                        <i class="la la-refresh refresh-icon"></i> 
                        <span>Regenerate Password</span>
                    </span>
                    <div class="custom-pwd">
                        <input type="text" name="kiosk_password" id="password" class="form-control" placeholder=""  value="<?php echo CommonHelper::randomString() ?>" minlength="6" maxlength="30" autocomplete="new-password" required="">
                        <i class="far fa-eye togglePassword" onclick="showPassword()"></i>
                    </div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.MACHINE_MODEL')}}<span class="error">*</span></label>
                    <select name="model_id" class="form-control" required="">
                        <option value="">Select Machine Model</option>
                        @if($models->isNotEmpty())
                            @foreach($models as $model)
                                <option value="{{$model->kiosk_model_id}}" @if(old('model_id' ) == $model->kiosk_model_id) selected="" @endif>{{$model->model_name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.MACHINE_SERIAL')}} </label>
                    <input  type="text" name="kiosk_serial_no" class="form-control" value=""  maxlength="30" placeholder="{{__('labels.MACHINE_SERIAL')}}">
                </div>   
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.ADMIN_MACHINE_PIN')}}<span class="error">*</span> 
                    <span class="info_span">
                        <i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;padding-right: 18px;" data-toggle="tooltip" data-placement="right" title="" data-original-title="Machine PIN can only contain numbers, small letters or combination of numbers and small letters."></i>
                    </span></label>
                    <span class="float-right mt-2" onclick="generatePin();" style="cursor: pointer;">
                        <i class="la la-refresh refresh-icon"></i> 
                        <span>Regenerate PIN</span>
                    </span>
                    <input type="text" name="pos_pin" id="pos_pin" class="form-control" value="<?php echo CommonHelper::randomString() ?>" maxlength="100" required="">
                </div>  
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.TIMEZONE')}} </label>
                    <select  name="kiosk_timezone" class="trans-select form-control">
                        <option value="">Select Time Zone</option>
                        <option value="America/New_York" selected="">ET</option>
                        <option value="America/Chicago">CT</option>
                        <option value="America/Denver">MT</option>
                        <option value="America/Los_Angeles">PT</option>
                    </select>
                </div>  
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.FACIAL_DETECTION')}}<span class="error">*</span></label>
                    <input type="text" name="kiosk_facial_license" class="form-control" value=""  maxlength="100" required="" placeholder="{{__('labels.FACIAL_DETECTION')}}">
                </div>

                <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-6">
                    <label class="col-form-label">{{__('labels.Language')}}<span class="error">*</span></label>
                    <select name="language" id="language" class="trans-select form-control" required="true">
                        <option value="English">English</option>
                        <option value="Arabic">Arabic</option>
                    </select>
                </div>

                <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-6">
                    <label class="col-form-label">{{__('labels.Currency')}}<span class="error">*</span></label>
                    <select name="currency" id="currency" class="trans-select form-control" required="true">
                        <option value="USD">USD</option>
                        <option value="Qatari Riyal">Qatari Riyal</option>
                    </select>
                </div>

                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.Email_Alert')}}<span class="error">*</span></label>
                    <select name="alert_email_yn" id="alert_email_yn" class="trans-select form-control" required="true">
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                    </select>
                </div>

            </div>
        </div>
    </div> 

    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header bg-pro">
            <h3 class="card-title color-white">Address Details</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.STREET')}}<span class="error">*</span></label>
                    <input  type="text" name="kiosk_street" class="form-control" placeholder="{{__('labels.STREET')}}"  value="{{old('kiosk_street')}}" maxlength="200" autocomplete="new-kiosk_street" required="">
                </div>
                
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.CITY')}}<span class="error">*</span></label>
                    <input  type="text" name="kiosk_city" class="form-control" placeholder="{{__('labels.CITY')}}"  value="{{old('kiosk_city')}}" maxlength="50" autocomplete="new-kiosk_city" required="">
                </div>       
               
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label for="kiosks_state" class="col-form-label">{{__('labels.STATE')}}<span class="error">*</span></label>
                    <input type="text" name="kiosks_state" id="kiosks_state" class="form-control" placeholder="{{__('labels.STATE')}}" maxlength="50" required="">
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.COUNTRY')}}<span class="error">*</span></label>
                    <input type="text" name="kiosk_country" class="form-control" value="" placeholder="{{__('labels.COUNTRY')}}" maxlength="50" required="">
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.ZIP')}}<span class="error">*</span></label>
                    <input type="number" name="kiosk_zip" class="form-control" value="" placeholder="{{__('labels.ZIP')}}" maxlength="50" required="">
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header bg-pro">
            <h3 class="card-title color-white">Machine Details</h3>
        </div>
        <div class="card-body">
            <div class="row">                
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.NOTIFY_INVENTORY')}} </label>
                    <input type="number" name="kiosk_low_inv_threshold" class="form-control" value="0" maxlength="20" min="0">
                </div>  
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.CHECKOUT_MSG')}}<span class="info_span"><i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;" data-toggle="tooltip" data-placement="right" title="" data-original-title="This message will be displayed on the POS screen after payment along with order confirmation details."></i></span></label>
                    <input  type="text" name="pos_checkout_msg" class="form-control" value="Thanks for shopping!" maxlength="100" >
                </div>  
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.TAX_RATE')}} </label>
                    <input  type="number" name="kiosk_tax_rate" class="form-control" value="0"  maxlength="30"  >
                </div>   
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.PROMOTION')}}</label>
                    <select class="form-control" name="promotions[]" multiple="multiple" id="promo">
                       @if($promotions->isNotEmpty())
                            @foreach($promotions as $promo)
                                <option value="{{$promo->promo_id}}">{{$promo->promo_code}}</option>
                            @endforeach
                       @endif
                    </select>
                </div>  
                <input type="hidden" id="current_promo_id" value="{{!empty($current_promo_id->promo_id)?$current_promo_id->promo_id:''}}" />
               
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12 hidden" id="promo_id">
                    <label>Promotion to set as Opt-in</label> 
                      <select class="form-control" name="optin_promo_id" id="optin_promo_id">
                        <option>Select Opt-in Promotion</option>
                      </select>
                      <!-- <div class="has-drop"> 
                         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z"></path></svg>
                      </div> -->
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.MACHINE_REGULATION')}}<span class="error">*</span></label><br/>
                    <input type="radio" id="yes" name="pos_age_regulation" value="Y">
                    <label for="yes">Yes</label> &nbsp;&nbsp;
                    <input type="radio" id="no" name="pos_age_regulation" value="N" checked="">
                    <label for="no">No</label><br>
                </div>  
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">{{__('labels.MIN_AGE')}} <span class="info_span"><i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;" data-toggle="tooltip" data-placement="right" title="" data-original-title="This field is used for age-regulated features. Please talk to your PopCom account rep for more information."></i></span></label>
                    <input type="number" name="pos_min_age" id="pos_min_age" class="form-control" value="0"  maxlength="5" disabled="">
                </div>    
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12" id="consumption" style="display: none;">
                    <label class="col-form-label">Limit Consumption to</label>
                    <input type="number" name="pos_consumption_units" class="form-control w20" value="0" maxlength="10" min="0">
                    <label class="col-form-label">units within a</label>
                    <input type="number" name="pos_consumption_period_hr" class="form-control w20" value="0" maxlength="10" min=0> hour period
                </div>                                             
            </div>
            <div class="col-lg-12 text-center">
                <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
                <a href="{{url('admin/machine')}}/{{$decrypt_id}}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
            </div>
        </div>
    </div>
</form>
@endsection
@section('scripts')
    <script src="{{ asset('js/admin/machine.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection