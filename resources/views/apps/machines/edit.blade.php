@extends('layout.default')
@section('content')
<form action="{{url('app/machines/update')}}" method="post" autocomplete="off" id="form">
    @csrf
    <input type="hidden" name="account_id" value="{{$kiosk->account_id}}">
    <input type="hidden" name="kiosk_id" value="{{$kiosk->kiosk_id}}">

    <div class="row">
        <div class="col-lg-12">
            
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Update Machine > {{$user->email}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_NAME')}} <span class="error">*</span></label>
                            <input  type="text" name="kiosk_identifier" class="form-control" placeholder=""  value="{{$kiosk->kiosk_identifier}}" maxlength="30" autocomplete="new-kiosk_identifier" required="" readonly>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.PASSWORD')}} <span class="error">*</span></label>
                            <div class="custom-pwd">
                                <input type="password" name="kiosk_password" id="password" class="form-control" placeholder=""  value="{{$kiosk->kiosk_password}}" minlength="6" maxlength="30" autocomplete="new-password" readonly>
                                <i class="far fa-eye togglePassword"  onclick="showPassword()"></i>
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_MODEL')}} <span class="error">*</span></label>
                            <select name="model_id" class="form-control" required="" disabled>
                                <option value="">Select Machine Model</option>
                                @if($models->isNotEmpty())
                                    @foreach($models as $model)
                                        <option value="{{$model->kiosk_model_id}}" @if($kiosk->model_id == $model->kiosk_model_id) selected="" @endif>{{$model->model_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_TEMPLATE')}} <span class="error">*</span></label>
                            <input type="text" name="" class="form-control" value="{{$kiosk->template_name}}"  maxlength="100" readonly="" readonly>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.FACIAL_DETECTION')}} <span class="error">*</span></label>
                            <input type="text" name="kiosk_facial_license" class="form-control" value="{{$kiosk->kiosk_facial_license}}"  maxlength="100" readonly>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_SERIAL')}} </label>
                            <input  type="text" name="kiosk_serial_no" class="form-control" value="{{$kiosk->kiosk_serial_no}}"  maxlength="30" placeholder="{{__('labels.MACHINE_SERIAL')}}" readonly>
                        </div>   
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.ADMIN_MACHINE_PIN')}} <span class="error">*</span> 
                            <span class="info_span">
                                <i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;padding-right: 18px;" data-toggle="tooltip" data-placement="right" title="" data-original-title="Machine PIN can only contain numbers, small letters or combination of numbers and small letters."></i>
                            </span></label>
                            <input type="text" name="pos_pin" class="form-control" value="{{$kiosk->pos_pin}}" maxlength="100"  id="pos_pin" readonly>
                        </div>  
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.TIMEZONE')}} </label>
                            <select  name="kiosk_timezone" class="trans-select form-control">
                                <option value="">Select Time Zone</option>
                                <option value="America/New_York" @if($kiosk->kiosk_timezone == 'America/New_York') selected @endif>ET</option>
                                <option value="America/Chicago" @if($kiosk->kiosk_timezone == 'America/Chicago') selected @endif>CT</option>
                                <option value="America/Denver" @if($kiosk->kiosk_timezone == 'America/Denver') selected @endif>MT</option>
                                <option value="America/Los_Angeles" @if($kiosk->kiosk_timezone == 'America/Los_Angeles') selected @endif>PT</option>
                            </select>
                        </div>
        
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.Language')}}<span class="error">*</span></label>
                            <select name="language" id="language" class="trans-select form-control" required="true" disabled>
                                <option value="English" @if($kiosk->language == 'English') selected @endif>English</option>
                                <option value="Arabic" @if($kiosk->language == 'Arabic') selected @endif>Arabic</option>
                            </select>
                        </div>
        
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.Currency')}}<span class="error">*</span></label>
                            <select name="currency" id="currency" class="trans-select form-control" required="true" disabled>
                                <option value="USD" @if($kiosk->currency == 'USD') selected @endif>USD</option>
                                <option value="Qatari Riyal" @if($kiosk->currency == 'Qatari Riyal') selected @endif>Qatari Riyal</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.Email_Alert')}}<span class="error">*</span></label>
                            <select name="alert_email_yn" id="alert_email_yn" class="trans-select form-control" required="true" disabled>
                                <option value="Y" @if($kiosk->alert_email_yn == 'Y') selected @endif>Yes</option>
                                <option value="N" @if($kiosk->alert_email_yn == 'N') selected @endif>No</option>
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
                            <label class="col-form-label">{{__('labels.STREET')}} <span class="error">*</span></label>
                            <input  type="text" name="kiosk_street" class="form-control" placeholder=""  value="{{$kiosk->kiosk_street}}" maxlength="200" autocomplete="new-kiosk_street" required="">
                        </div>
                        
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.CITY')}} <span class="error">*</span></label>
                            <input  type="text" name="kiosk_city" class="form-control" placeholder=""  value="{{$kiosk->kiosk_city}}" maxlength="50" autocomplete="new-kiosk_city" required="">
                        </div>       
                        
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label for="kiosks_state" class="col-form-label">{{__('labels.STATE')}} <span class="error">*</span></label>
                            <input type="text" name="kiosks_state" id="kiosks_state" class="form-control" value="{{$kiosk->kiosks_state}}" maxlength="50" required="">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.COUNTRY')}} <span class="error">*</span></label>
                            <input type="text" name="kiosk_country" class="form-control" value="{{$kiosk->kiosk_country}}" maxlength="50" required="">
                        </div>
                         
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.ZIP')}} <span class="error">*</span></label>
                            <input type="text" name="kiosk_zip" class="form-control" value="{{$kiosk->kiosk_zip}}"  maxlength="6" required="" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
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
                            <input type="number" name="kiosk_low_inv_threshold" class="form-control" value="{{$kiosk->kiosk_low_inv_threshold}}" maxlength="20" min="0">
                        </div>  
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.CHECKOUT_MSG')}} <span class="info_span"><i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;" data-toggle="tooltip" data-placement="right" title="" data-original-title="This message will be displayed on the POS screen after payment along with order confirmation details."></i></span></label>
                            <input  type="text" name="pos_checkout_msg" class="form-control" value="{{$kiosk->pos_checkout_msg}}" maxlength="100" >
                        </div>  
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.TAX_RATE')}} </label>
                            <input  type="text" name="kiosk_tax_rate" class="form-control" value="{{$kiosk->kiosk_tax_rate}}"  maxlength="30" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                        </div>   

                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.PROMOTION')}}</label>
                            <select class="form-control js-example-basic-single" name="promotions[]" id="promo" multiple="multiple">
                                @if (isset($promo) && count($promo) > 0)
                                    @foreach ($promo as $item)
                                        <option value="{{$item->promo_id}}">{{$item->promo_code}}</option>    
                                    @endforeach
                                @endif
                            </select>
                        </div>  
        
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_REGULATION')}} </label><br/>
                            <input type="radio" id="yes" name="pos_age_regulation" value="Y" @if($kiosk->pos_age_regulation == 'Y') checked @endif disabled>
                            <label for="yes">Yes</label> &nbsp;&nbsp;
                            <input type="radio" id="no" name="pos_age_regulation" value="N" @if($kiosk->pos_age_regulation!= 'Y') checked @endif disabled>
                            <label for="no">No</label><br>
                        </div>  
                        <input type="hidden" id="current_promo_id" value="{{!empty($c_selpromo->promo_id)?$c_selpromo->promo_id:''}}" />
               
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12 hidden" id="promo_id">
                            <label>Promotion to set as Opt-in</label> 
                              <select class="form-control" name="optin_promo_id" id="optin_promo_id">
                                <option>Select Opt-in Promotion</option>
                              </select>
                              <!-- <div class="has-drop"> 
                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z"></path></svg>
                              </div> -->
                        </div>
                        @if($kiosk->pos_age_regulation == 'Y')
                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                <label class="col-form-label">{{__('labels.MIN_AGE')}} <span class="info_span"><i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;" data-toggle="tooltip" data-placement="right" title="" data-original-title="This field is used for age-regulated features. Please talk to your PopCom account rep for more information."></i></span></label>
                                <input type="number" name="pos_min_age" id="pos_min_age" class="form-control" value="{{$kiosk->pos_min_age}}"  maxlength="5" >
                            </div>    
                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12" id="consumption">
                                <label class="col-form-label">Limit Consumption to</label>
                                <input type="number" name="pos_consumption_units" class="form-control w10" value="{{$kiosk->pos_consumption_units}}" maxlength="10" min="0">
                                <label class="col-form-label">units within a</label>
                                <input type="number" name="pos_consumption_period_hr" class="form-control w10" value="{{$kiosk->pos_consumption_period_hr}}" maxlength="10" min=0> hour period
                            </div>    
                        @else
                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                <label class="col-form-label">{{__('labels.MIN_AGE')}} <span class="info_span"><i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;" data-toggle="tooltip" data-placement="right" title="" data-original-title="This field is used for age-regulated features. Please talk to your PopCom account rep for more information."></i></span></label>
                                <input type="number" name="pos_min_age" id="pos_min_age" class="form-control" value="0"  maxlength="5" disabled="">
                            </div>    
                            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12" id="consumption" style="display: none;">
                                <label class="col-form-label">Limit Consumption to</label>
                                <input type="number" name="pos_consumption_units" class="form-control w10" value="0" maxlength="10" min="0">
                                <label class="col-form-label">units within a</label>
                                <input type="number" name="pos_consumption_period_hr" class="form-control w10" value="0" maxlength="10" min=0> hour period
                            </div>    
                        @endif                                         
                    </div>
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
                        @if ($AuthFlag == 1)
                        <a href="{{ url('app/machines/list') }}/{{encrypt($kiosk->account_id)}}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                        @else
                        <a href="{{ url('app/machines') }}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/machine_model.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    
        @if($promo->isNotEmpty() && !empty($selpromo))
            var c = '<?php echo $selpromo ;?>';
            console.log(c);
            var promo = c.split(',');
            $('#promo').val(promo).trigger('change');
        @endif
    </script>
    
@endsection