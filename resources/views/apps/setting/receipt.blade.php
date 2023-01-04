@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('public/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/assets/css/summernote/dist/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .custom-review-tbl>tbody>tr>td{
            border-top: none;
        }
        .custom-border{
            border: 1px solid black;
        }
        .custom-text-align{
            margin-right: 28px !important;
        }
        #custom_text_1_para{
            text-align: left;
        }
        table {
            width: 100%;
            /*border-collapse: collapse;*/
            border: 1px solid #cec3c3;
        }
        .company-logo_image {
            max-height: 120px;
            max-width: 200px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        ..wrap-table{
            padding-left: 
        }
        .button {
            border-radius: 4px;
            border: 0;
            padding: 10px 30px;
            text-decoration: none;
            font-size: 0.875em;
            font-weight: 600;
            background-color: #cccccc;
            transition: background-color 0.3s ease-in;
            cursor: pointer;
        }
        .head_para{
            margin-top: 10px;
            border:1px solid #d4d4d4;
        }     
        .product-header_image {
            max-height: 120px;
            max-width: 200px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        .note-editable i {
            color: black;
        }
    </style>
@endsection

@section('content')
<form action="{{ url('app/setting/updateReceipt') }}" method="POST" autocomplete="off" id="receipt_form" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Receipt Settings</h3>
                </div>
                <div class="card-body">
                    <div class="row">   
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">                        
                            
                            @if ($setting->accountDetails->account_type == 'std' || $setting->accountDetails->account_type == 'sub')
                            
                            <div class="form-group">
                                <label class="col-form-label"><strong>{{__('labels.OrganizationLogo')}}</strong> <span class="error">*</span></label>
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="symbol symbol-100 symbol-2by3 flex-shrink-0" style="padding: 10px; border: 1px solid #cbcbcb;">
                                        <div class="symbol-label organization-logo" style="background-image: url('{{ $setting->accountSetting->account_logo }}')"></div>
                                    </div>
                                </div> 
                            </div>

                            @else

                            <div class="form-group">
                                <p>Complete these fields to add your own message and/or survey link to all of your sub-account customer email receipts. <br/>Your text and survey link button will appear below any receipt language your sub-accounts may include.</p>
                            </div>

                            @endif
                            @if ($setting->accountDetails->account_type != 'std' && $setting->accountDetails->account_type != 'sub')
                                <div class="form-group">
                                    <label class="col-form-label"><strong>{{__('labels.CustomText1')}}</strong><span class="error">*</span></label>
                                    <textarea class="receipt_custom_text_1" id="receipt_custom_text_1" name="receipt_custom_text_1" required>{{ $setting->accountSetting->receipt_custom_text_1}}</textarea>
                                </div>
                            @endif
                            
                            @if ($setting->accountDetails->account_type == 'std' || $setting->accountDetails->account_type == 'sub')
                            <div class="form-group">
                                <label class="col-form-label"><strong>{{__('labels.CustomText1')}}</strong><span class="error">*</span></label>
                                <textarea class="receipt_custom_text_1" id="receipt_custom_text_1" name="receipt_custom_text_1" required>{{ !empty($setting->accountSetting->receipt_custom_text_1)?$setting->accountSetting->receipt_custom_text_1:config('constants.custome_text1')}}</textarea>
                            </div>

                           
                            <div class="form-group">
                                <label class="col-form-label"><strong>{{__('labels.CustomText2')}}</strong><span class="error">*</span></label>
                                <textarea class="receipt_custom_text_2" id="receipt_custom_text_2" name="receipt_custom_text_2">{{ !empty($setting->accountSetting->receipt_custom_text_2)?$setting->accountSetting->receipt_custom_text_2:config('constants.custome_text2') }}</textarea>
                            </div>

                            <p><strong>You may find the below information from your email service provider under "email configuration". This allows you to send emails to your clients under your branding.</strong></p>

                            <div class="form-group">
                                <label class="col-form-label"><strong>{{__('labels.SenderEmail')}}</strong><span class="error">*</span></label>
                                <span class="float-right mt-2 edit_sender_email" style="cursor: pointer;">
                                    <i class="la la-pencil-alt" style="float: right;color: #5bc1cd;cursor: pointer;"></i>
                                </span>
                                <input type="email" name="receipt_sender_email" id="receipt_sender_email" class="form-control is-valid" value="{{ $setting->accountSetting->receipt_sender_email }}" required="" disabled>
                            </div>

                            <div class="form-group">
                                <label class="col-form-label"><strong>{{__('labels.PASSWORD')}}</strong><span class="error">*</span></label>
                                <div class="custom-pwd">
                                    <input  type="password" name="receipt_sender_password" id="password" class="form-control" value="{{ $setting->accountSetting->receipt_sender_password }}" required="">
                                    <i class="far fa-eye togglePassword"  onclick="showPassword()"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-form-label"><strong>{{__('labels.Host')}}</strong><span class="error">*</span></label>
                                <input type="text" name="receipt_sender_host" id="receipt_sender_host" class="form-control" value="{{ $setting->accountSetting->receipt_sender_host }}"  required="">
                            </div>

                            <div class="form-group">
                                <label class="col-form-label"><strong>{{__('labels.Port')}}</strong><span class="error">*</span></label>
                                <input type="text" name="receipt_sender_port" id="receipt_sender_port" class="form-control" value="{{ $setting->accountSetting->receipt_sender_port }}" required="" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                            </div>
                            @endif

                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-primary">
                                    <input type="checkbox" name="includeSurveyUrl" id="includeSurveyUrl" {{ $setting->accountSetting->include_survey_url == 'Y' ? 'checked="checked"' : '' }}>
                                    <span></span><strong>{{__('labels.IncludeSurveyEmail')}}</strong></label>
                                </div>

                                <input type="hidden" name="include_survey_url" id="include_survey_url" value="{{$setting->accountSetting->include_survey_url}}">
                            </div>

                            <div class="form-group receipt_survey_url" style="{{ $setting->accountSetting->include_survey_url == 'Y' ? 'display:block;' : 'display:none;' }}">
                                <label class="col-form-label"><strong>{{__('labels.SurveyURL')}}</strong></label>
                                <input type="text" name="receipt_survey_url" id="receipt_survey_url" class="form-control" value="{{ $setting->accountSetting->receipt_survey_url }}">
                            </div>
    
                            <div class="form-group receipt_survey_url" style="{{ $setting->accountSetting->include_survey_url == 'Y' ? 'display:block;' : 'display:none;' }}">
                                <label class="col-form-label">{{__('labels.SurveysWyzerr')}}</label>
                                <a href="{{ Config::get('constants.SurveyLink') }}" target="_blank" class="btn btn-primary">Click here</a>
                            </div>

                            @if ($setting->accountDetails->account_type == 'ent')
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{__('labels.BUPDATE')}}</button>
                                <a href="{{ url('/home') }}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>

                                <input type="hidden" name="sendMail" value="0" id="sendMail"/>
                            </div>
                            @endif
                        </div>

                        @if ($setting->accountDetails->account_type == 'std' || $setting->accountDetails->account_type == 'sub')
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="col-form-label"><strong>{{__('labels.CustomerEmailPreview')}}</strong></label>
                                <input type="text" name="" class="form-control" value="{{ Config::get('constants.ShoppingSubject') }}" readonly>
                            </div>

                            <div class="form-group">
                                <table class="table custom-review-tbl">
                                    <tr>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>                                      
                                        <td colspan="2" style="text-align: center;background-color: rgb(242,242,242) !important"> 
                                            <img id="preview" src="{{ $setting->accountSetting->account_logo }}" alt="" style="padding-left: 15px;" class="product-header_image">
                                        </td>
                                      
                                        <td style="text-align: right;background-color: rgb(242,242,242) !important">
                                            <p  style="padding-right: 15px">Order No: #</p>
                                        </td>
                                    </tr>
              
                                    <tr>
                                        <td colspan="3" style="padding-bottom: 0px"><!-- Product -->
                                            <div style="padding-right: 25px;padding-left: 25px">
                                                <table class="table" style="border:0px;margin-bottom: 0px">
                                                
                                                    <tr>
                                                        <td style="border-top: 0px">
                                                            <div id="custom_text_1_para" >
                                                            @php
                                                                echo $setting->accountSetting->receipt_custom_text_1; 
                                                            @endphp
                                                            </div>
                                                        </td>
                                                        <td style="border-top: 0px" colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr class="receipt_survey_url" style="{{ $setting->accountSetting->include_survey_url == 'Y' ? 'display:block;' : 'display:none;' }}">
                                                        <td style="border-top: 0px">
                                                            <a href="{{ $setting->accountSetting->include_survey_url == 'Y' ? $setting->accountSetting->receipt_survey_url : 'javascript:void(0);' }}" target="_blank" id="servey_link">
                                                                <button type="button" class="button button--large" style="background-color: #{{ $setting->accountSetting->primany_color }};color: white">Take a Survey</button> 
                                                            </a>
                                                        </td>
                                                        <td style="border-top: 0px" colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-top: 0px">Your purchase details are as follows</td>
                                                        <td style="border-top: 0px" colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-top: 0px">Location: Street, City, State, Zip</td>
                                                        <td style="border-top: 0px" colspan="2">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>                                        
                                        <td colspan="3">
                                            <div style="padding-right: 25px;padding-left: 25px">
                                                <table border="1" class="table">
                                                    <tr>
                                                        <td>Product Name</td>
                                                        <td>Product Variant</td>
                                                        <td>Product Price</td>
                                                        <td>Qty Ordered</td>
                                                        <td>Line Total</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>                                       
                                    </tr>
                              
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td style="text-align: right;" colspan="2">
                                            <div style="margin-right: 115px;padding-bottom: 15px">Sub Total: </div>
                                            <div style="margin-right: 115px;padding-bottom: 15px">Discount Amount: </div>
                                            <div style="margin-right: 115px;padding-bottom: 15px">Tax: </div>
                                            <div style="margin-right: 115px;padding-bottom: 15px">Total: </div>
                                        </td>                                      
                                    </tr>

                                    <tr>
                                        <td colspan="3">
                                            <p id="custom_text_2_para" style="padding-left: 25px;">
                                            @php
                                                echo $setting->accountSetting->receipt_custom_text_2;
                                            @endphp
                                            </p>
                                        </td>
                                    </tr>
                                   
                                </table>     
                                
                                @if (isset($parentData->accountSetting->receipt_custom_text_1) && $parentData->accountSetting->receipt_custom_text_1 != '' && $setting->accountDetails->account_type == 'sub')
                                <div class="form-group">
                                    <label class="col-form-label"><b>The following receipt text has been added by test and will show below your receipt text as follows:</b></label>
                                    <p>
                                        @php
                                            echo $parentData->accountSetting->receipt_custom_text_1;
                                        @endphp
                                    </p>
                                    <hr>

                                    @if ($parentData->accountSetting->receipt_survey_url != '')
                                    <a href="{{$parentData->accountSetting->receipt_survey_url}}" class="" target="_blank" style="text-decoration: underline !important;">Take the {{$parentData->accountDetails->account_name}} Survey</a>
                                    @endif
                                    
                                </div>    
                                @endif
                                
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{__('labels.BUPDATE')}}</button>
                                <a href="{{ url('/home') }}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>

                                <input type="hidden" name="sendMail" value="0" id="sendMail"/>
                                <button type="submit" class="btn btn-primary sendMail">Send Test Mail</button>
                            </div>
                        </div>
                        @endif
                      
                    </div>
                   
                </div>
            </div>
        </div>
        
    </div>
</form>

@endsection

@section('scripts')
    <script src="{{ asset('public/assets/js/pages/crud/forms/editors/summernote.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/account/setting.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
    <script src="{{ asset('js/custom.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection