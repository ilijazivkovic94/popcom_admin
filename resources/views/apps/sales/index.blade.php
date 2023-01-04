@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('plugins/custom/datepicker/bootstrap-datepicker.min.css') }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title">All Sales</h3>        
                <div class="pull-right vcenter">
                    <form id="exportFrm" action="{{ url('app/sales/export/') }}" method="post">
                        @csrf <!-- {{ csrf_field() }} -->
                        <input type="text" hidden name="email" value="{{$email}}">
                    <button type="submit" class="btn btn-primary btn-sm">Export</button>
                    </form>
                   
                </div>
            </div>
            <div class="card-body">
                <?php if($accType == 'ent'){ ?>
                <div class="row mb-4">
                    <div class="col-6">
                        <span class="d-block mb-3">Select Sub Account </span>
                        <select form="exportFrm" class="form-control select-box" name="accountId" id="subaccount">
                            <option value="">Select Account</option>
                            <?php if($subAccounts->count() > 0): ?>
                                <?php 
                                    foreach ($subAccounts as $key => $acct) {
                                        echo "<option value='".$acct->account_id."'>".$acct->account_name."</option>";
                                    } 
                                ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-6">
                        <span class="d-block mb-3">Select Machine </span>
                        <select name="kiosk_id" form="exportFrm" id="kiosk_id" class="form-control select-box">
                           <option value="">Select Machines</option>
                           <option value="all">All Machines</option>
                        </select>
                    </div>
                </div>
                <?php } ?>
                <?php if($accType == 'ent'){
                        $cardClass = 'col-lg-4 col-sm-4 col-12';
                  }else{
                    $cardClass = 'col-lg-3 col-sm-3 col-12';
                  } ?>
                <div class="row no-margin order-filter " id="tbl_sales_filter">
                    <div class="{{ $cardClass }}">
                        <div class="form-group">
                            <span class="d-block mb-3">Select a date range </span>
                            <div class="col-12 p-0">
                             <input type="text" class="form-control text-center" autocomplete="off" form="exportFrm" name="daterange" value="" placeholder="Select Dates" />
                            </div>
                        </div>
                    </div>
                    <?php if($accType != 'ent'){ ?>
                        <div class="{{ $cardClass }}">
                            <div class="form-group">
                                <span class="labels d-block mb-3">Select machine </span>
                                <select name="kiosk_id" form="exportFrm" id="kiosk_id" class="form-control select-box" >
                                    <option value="">All Machines</option>
                                    @foreach ($getMachine as $item)
                                    <option value="{{$item->kiosk_id}}">{{$item->kiosk_identifier}}</option>
                                    @endforeach                            
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="{{ $cardClass }}">
                        <span class="labels d-block mb-3">Select a period </span>

                        <div class="has-select form-group">
                            <select class="select-box form-control" form="exportFrm" name="timeperiod" id="data-time">
                                @if($email != '')
                                    <option selected value=""></option>
                                    <option value="today">Today</option>
                                @else
                                <option selected value="today">Today</option>
                                @endif
                                <option value="week">This week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                                <option value="lastyear">Last Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="{{ $cardClass }}">
                        <span class="labels d-block mb-3">Cart events</span>
                        <div class="has-select form-group">
                            <select class="select-box form-control" form="exportFrm" name="cart_events" id="cart_events">
                                <option value="all">All</option>
                                <option selected value="checkout">Checkout</option>
                                <option value="abandon">Abandon</option>             
                            </select>
                            
                        </div>
                    </div>
                </div>
                <input type="text" hidden id="email_id" value="{{$email}}">
                <div class="mb-0 table-responsive">
                    <table id="tbl_sales" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr class="bg-primary">
                                <th>{{__('labels.OrderID')}}</th>
                                <th>{{__('labels.OrderDate')}}</th>
                                <?php if($accType == 'ent'): ?>
                                    <th>{{__('labels.VisitorActName')}}</th>
                                <?php endif; ?>
                                <th>{{__('labels.MachineName')}}</th>
                                <th>{{__('labels.CustomerName')}}</th>
                                <th>{{__('labels.SalesQty')}}</th>
                                <th>{{__('labels.SalesSubTotal')}}</th>
                                <th>{{__('labels.SalesTax')}}</th>
                                <th>{{__('labels.SalesTotal')}}</th>
                                <th>{{__('labels.SalesDiscTotal')}}</th>
                                <th>{{__('labels.SalesCode')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>
<input type="hidden" id="_t" value="{{ csrf_token() }}">
@endsection

@section('scripts')
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/plugins/custom/bootbox/bootbox.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/plugins/custom/bootbox/bootbox.locales.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/custom/datepicker/bootstrap-datepicker.min.js') }}" ></script>

    <script src="{{ asset('js/account/sales.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection


