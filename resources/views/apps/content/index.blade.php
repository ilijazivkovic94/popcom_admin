@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('public/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/assets/css/summernote/dist/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@if(Auth::user()->accountDetails->account_type == 'ent')
    <h4 class="ent_content">When you add text to a CONTENT section, that section will be shown in the POS footer menu of all of your sub-account machines. <br> Your text will be appended to the of any text your sub-account chose to include, if any.</h4>
@endif
<form action="{{url('app/content/save')}}" method="post" autocomplete="off" id="form_account">
    @csrf
    <input type="hidden" name="account_setting_id" value="{{$accountSetting->account_setting_id}}">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Contents - About</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.ABOUT')}}</label>
                                    <textarea class="summernote" id="cms_about" name="cms_about">{{$accountSetting->cms_about}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-form-label">{{__('labels.STATUS')}} <span class="error">*</span></label>
                            @if(!empty($subAccountSetting) && $subAccountSetting->cms_about == 'Y')
                                <select  name="cms_about_active_yn" class="form-control" required="" disabled="">
                                    <option value="Y">Active</option>
                                </select>
                            @else
                                <select  name="cms_about_active_yn" class="form-control" required="" >
                                    <option value="N" @if($accountSetting->cms_about_active_yn == 'N') selected @endif>Inactive</option>
                                    <option value="Y" @if($accountSetting->cms_about_active_yn == 'Y') selected @endif>Active</option>
                                </select>
                            @endif
                            @error('status') <span class="error">{{$message}}</span> @enderror
                            <br/>
                            @if(Auth::user()->accountDetails->account_type == 'sub')
                                @if($parentSetting->cms_about_active_yn == 'Y')
                                <b>Note: Content by parent.</b><br/>
                                {!!$parentSetting->cms_about!!}
                                @endif
                            @endif
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Contents - {{__('labels.TESTIMONIALS')}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.TESTIMONIALS')}}</label>
                                    <textarea class="summernote" id="cms_testimonials" name="cms_testimonials">{{$accountSetting->cms_testimonials}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-form-label">{{__('labels.STATUS')}} <span class="error">*</span></label>
                            @if(!empty($subAccountSetting) && $subAccountSetting->cms_testimonail == 'Y')
                                <select  name="cms_testimonials_active_yn" class="form-control" required=""  disabled="">
                                    <option value="Y">Active</option>
                                </select>
                            @else
                                <select  name="cms_testimonials_active_yn" class="form-control" required=""  >
                                    <option value="N" @if($accountSetting->cms_testimonials_active_yn == 'N') selected @endif>Inactive</option>
                                    <option value="Y" @if($accountSetting->cms_testimonials_active_yn == 'Y') selected @endif>Active</option>
                                </select>
                            @endif
                            @error('status') <span class="error">{{$message}}</span> @enderror
                            <br/>
                            @if(Auth::user()->accountDetails->account_type == 'sub')
                                @if($parentSetting->cms_testimonials_active_yn == 'Y')
                                <b>Note: Content by parent.</b><br/>
                                {!!$parentSetting->cms_testimonials!!}
                                @endif
                            @endif
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Contents - {{__('labels.PRIVACY')}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.PRIVACY')}}</label>
                                    <textarea class="summernote" id="cms_privacy_policy" name="cms_privacy_policy">{{$accountSetting->cms_privacy_policy}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-form-label">{{__('labels.STATUS')}} <span class="error">*</span></label>
                            <select  name="cms_privacy_policy_active_yn" class="form-control" required="" disabled="">
                                <option value="Y" @if($accountSetting->cms_privacy_policy_active_yn == 'Y') selected @endif>Active</option>
                            </select>
                            @error('status') <span class="error">{{$message}}</span> @enderror
                            <br/>
                            @if(Auth::user()->accountDetails->account_type == 'sub')
                                <b>Note: Content by parent.</b><br/>
                                {!!$parentSetting->cms_privacy_policy!!}
                            @endif

                            <br/>
                            <p><strong>Note: PopCom is required by law include this statement on our PopShop Kiosks. This statement will go below any content you choose to include for your business above.</strong></p>
                            <p>PopCom Privacy Policy: This machine is licensed under PopCom's Terms of Service. The Licensee of this machine determines the information that it collects and how it is implemented. For more information on on PopCom's privacy policy or terms of use visit <a href="https://www.popcom.shop/" target="_blank">popcom.shop.</a></p>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Contents - {{__('labels.TERMS')}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.TERMS')}}</label>
                                    <textarea class="summernote" id="cms_terms_of_use" name="cms_terms_of_use">{{$accountSetting->cms_terms_of_use}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-form-label">{{__('labels.STATUS')}} <span class="error">*</span></label>
                            @if(!empty($subAccountSetting) && $subAccountSetting->cms_terms == 'Y')
                                <select  name="cms_terms_of_use_active_yn" class="form-control" required="" disabled="">
                                    <option value="Y" @if($accountSetting->cms_terms_of_use_active_yn == 'Y') selected @endif>Active</option>
                                </select>
                            @else
                                <select  name="cms_terms_of_use_active_yn" class="form-control" required="">
                                    <option value="N" @if($accountSetting->cms_terms_of_use_active_yn == 'N') selected @endif>Inactive</option>
                                    <option value="Y" @if($accountSetting->cms_terms_of_use_active_yn == 'Y') selected @endif>Active</option>
                                </select>
                            @endif
                            @error('status') <span class="error">{{$message}}</span> @enderror
                            <br/>
                            @if(Auth::user()->accountDetails->account_type == 'sub')
                                @if($parentSetting->cms_terms_of_use_active_yn == 'Y')
                                <b>Note: Content by parent.</b><br/>
                                {!!$parentSetting->cms_terms_of_use!!}
                                @endif
                            @endif
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Contents - {{__('labels.CONTACT')}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.CONTACT')}}</label>
                                    <textarea class="summernote" id="cms_contact_us" name="cms_contact_us">{{$accountSetting->cms_contact_us}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-form-label">{{__('labels.STATUS')}} <span class="error">*</span></label>
                            @if(!empty($subAccountSetting) && $subAccountSetting->cms_contact == 'Y')
                                <select  name="cms_contact_us_active_yn" class="form-control" required="" disabled="">
                                    <option value="Y" @if($accountSetting->cms_contact_us_active_yn == 'Y') selected @endif>Active</option>
                                </select>
                            @else
                                <select  name="cms_contact_us_active_yn" class="form-control" required="">
                                    <option value="N" @if($accountSetting->cms_contact_us_active_yn == 'N') selected @endif>Inactive</option>
                                    <option value="Y" @if($accountSetting->cms_contact_us_active_yn == 'Y') selected @endif>Active</option>
                                </select>
                            @endif
                            @error('status') <span class="error">{{$message}}</span> @enderror
                            <br/>
                            @if(Auth::user()->accountDetails->account_type == 'sub')
                                @if($parentSetting->cms_contact_us_active_yn == 'Y')
                                <b>Note: Content by parent.</b><br/>
                                {!!$parentSetting->cms_contact_us!!}
                                @endif
                            @endif
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Contents - {{__('labels.FAQ')}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.FAQ')}}</label>
                                    <textarea class="summernote" id="cms__faq" name="cms__faq">{{$accountSetting->cms__faq}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-form-label">{{__('labels.STATUS')}} <span class="error">*</span></label>
                            @if(!empty($subAccountSetting) && $subAccountSetting->cms_faq == 'Y')
                                <select  name="cms__faq_active_yn" class="form-control" required="" disabled="">
                                    <option value="Y" @if($accountSetting->cms__faq_active_yn == 'Y') selected @endif>Active</option>
                                </select>
                            @else
                                <select  name="cms__faq_active_yn" class="form-control" required="">
                                    <option value="N" @if($accountSetting->cms__faq_active_yn == 'N') selected @endif>Inactive</option>
                                    <option value="Y" @if($accountSetting->cms__faq_active_yn == 'Y') selected @endif>Active</option>
                                </select>
                            @endif
                            @error('status') <span class="error">{{$message}}</span> @enderror
                            <br/>
                            @if(Auth::user()->accountDetails->account_type == 'sub')
                                 @if($parentSetting->cms__faq_active_yn == 'Y')
                                <b>Note: Content by parent.</b><br/>
                                {!!$parentSetting->cms__faq!!}
                                @endif
                            @endif
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 text-center">
        <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
        <a href="{{ url('home') }}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
    </div>
</form>

@endsection

@section('scripts')

    <script src="{{ asset('public/assets/js/pages/crud/forms/editors/summernote.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/account/content.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>

@endsection