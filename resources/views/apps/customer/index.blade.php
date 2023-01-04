@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>

    <style>
        .symbol.symbol-35 > img {
            width: 100%;
            max-width: 25px;
            height: 25px;
            margin-right: 5px;
            display: inline-block;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title">Customers</h3>        
                <div class="pull-right vcenter">
                	<a href="{{ url('app/customer/export') }}" class="btn btn-primary btn-sm">Export</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(Auth::user()->accountDetails->account_type=='ent')
                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">                        
                        <select name="sub_account" id="sub_account" class="form-control" required="">
                            <option value="">Select Sub Account</option>
                            @if($subAccount->isNotEmpty())
                                @foreach ($subAccount as $act)
                                <option value="{{$act->account_id}}">{{$act->account_name}}</option>
                                @endforeach  
                            @endif                          
                        </select>
                    </div>
                    @endif
                    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">                        
                        <select name="kiosk_id" id="kiosk_id" class="form-control" required="">
                            <option value="">All Machines</option>
                            @foreach ($getMachine as $item)
                            <option value="{{$item->kiosk_id}}">{{$item->kiosk_identifier}}</option>
                            @endforeach                            
                        </select>
                    </div>
                    <div class="col col-sm-12">
                        <p class="font-bold">Showing {{$uniqueCount}} unique customers who have provided their Email. Not showing remaining {{$remainingCount}} customers without Email.</p>
                    </div>
                </div>

                <div class="mb-0 table-responsive">
                    <table id="tbl_account" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr class="bg-primary">
                                <th>{{__('labels.CustomerID')}}</th>
                              	<th>{{__('labels.CustomerEmail')}}</th>
                              	<th>{{__('labels.CustomerGender')}}</th>
                                <th>{{__('labels.CustomerEmotion')}}</th>
                                <th>{{__('labels.CustomerAgeGroup')}}</th>
                                <th>{{__('labels.CustomerSales')}}</th>
                              	<th>{{__('labels.CustomerAction')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>
<input type="hidden" value="{{ Config::get('constants.ProductDelete') }}" id="productDeleteMsg" />
@endsection

@section('scripts')
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/plugins/custom/bootbox/bootbox.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/plugins/custom/bootbox/bootbox.locales.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/account/customer.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection

