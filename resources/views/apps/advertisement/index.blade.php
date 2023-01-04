@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .adv_image{
            background-color: transparent !important;
            background-repeat: no-repeat !important;
            background-position: center center !important;
            background-size: contain !important;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title">Advertisements</h3>        
                <div class="pull-right vcenter">
                    @php
                        $accountType = Auth::user()->accountDetails()->pluck('account_type')->first();
                    @endphp
                    
                    @if (CommonHelper::SubAccountSetting('ads_create') == 'Y')
                    <a href="{{ url('app/advertisement/add') }}" class="btn btn-primary btn-sm mr-2">Add Advertisement</a>    
                    @endif                          
                    
                </div>        
            </div>
            <div class="card-body">
                <div class="row">
                    @if ($accountType == 'ent')
                        <div class="form-group col-md-12 col-lg-12 col-sm-6 col-xs-6">                        
                            <select id="sub_account_id" class="form-control advert-filter">
                                <option value="">All Sub-Accounts</option>
                                @foreach (CommonHelper::SubAccountDetails() as $item)
                                <option value="{{ $item->account_id}}">{{ $item->account_name }}</option>
                                @endforeach                          
                            </select>
                        </div>   
                    @else
                        <input type="hidden" id="sub_account_id" value="" />
                    @endif
                    
                    <div class="form-group col-md-3 col-lg-3 col-sm-6 col-xs-6">                        
                        <select id="ad_type" class="form-control advert-filter">
                            <option value="">All Type</option>
                            <option value="image">Image</option>
                            <option value="video">Video</option>                          
                        </select>
                    </div>

                    <div class="form-group col-md-3 col-lg-3 col-sm-6 col-xs-6">                        
                        <select id="ad_status" class="form-control advert-filter">
                            <option value="">All Status</option>
                            <option value="Y">Active</option>
                            <option value="N">Inactive</option>                            
                        </select>
                    </div>

                    <div class="form-group col-md-3 col-lg-3 col-sm-6 col-xs-6">                        
                        <select id="ad_gender" class="form-control advert-filter">
                            <option value="">All Gender</option>
                            <option value="F">Female</option>
                            <option value="M">Male</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3 col-lg-3 col-sm-6 col-xs-6">                        
                        <select id="ad_age_group" class="form-control advert-filter">
                            <option value="">All Age</option>
                            <option value="senior">Senior</option>
                            <option value="adult">Adult</option>
                            <option value="young">Young Adult</option>
                            <option value="child">Youth</option>
                        </select>
                    </div>
                </div>

                <div class="mb-0 table-responsive">
                    <table id="tbl_account" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr class="bg-primary">
                                <th>{{__('labels.AdvertisementImage')}}</th>
                              	<th>{{__('labels.AdvertisementName')}}</th>
                              	<th>{{__('labels.AdvertisementType')}}</th>
                                <th>{{__('labels.AdvertisementGender')}}</th>
                                <th>{{__('labels.AdvertisementAge')}}</th>
                                <th>{{__('labels.AdvertisementStatue')}}</th>
                              	<th>{{__('labels.ColAction')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>

<input type="hidden" value="{{ Config::get('constants.AdvertDeleteWar') }}" id="advertDeleteWarMsg" />
@endsection

@section('scripts')
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/plugins/custom/bootbox/bootbox.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/plugins/custom/bootbox/bootbox.locales.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/account/advertisement.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection

