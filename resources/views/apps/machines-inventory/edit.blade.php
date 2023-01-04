@extends('layout.default')

{{-- Styles Section --}}
@section('styles')

@endsection

@section('content')
<form action="{{url('app/machines-inventory/update')}}" method="POST" autocomplete="off" id="form_account">
    @csrf
    <input type="hidden" name="kioskID" value="{{ encrypt($productData['kioskData']['machineID']) }}" />

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Edit Machine Inventory</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <!--begin::List Widget 14-->                            
                                
                            <!--begin::Header-->
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <h3 class="card-title font-weight-bolder text-dark mb-3" style="display: inline-block;">{{ $productData['kioskData']['machineName'] }} - {{ $productData['kioskData']['machineCity'] }} {{ $productData['kioskData']['machineState'] }}</h3>
                                
                                    <h3 class="card-title font-weight-bolder text-dark mb-3" style="display: inline-block;float: right;">Low Level Alert for this Machine: {{ $productData['kioskData']['machineAlert'] }}</h3>
                                </div>

                                <div class="col-lg-12 text-right mb-4">
                                    <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
                                    <a href="{{ url('app/machines-inventory') }}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                                </div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="mb-0 table-responsive">
                                <table class="table table-bordered" width="100%" id="machine_inventory">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th style="font-weight: 500; color: #ffff; width: 160px; min-width: 160px;">{{__('labels.BinIdentification')}}</th>
                                            <th style="font-weight: 500; color: #ffff; width: 130px; min-width: 225px;">{{__('labels.Products')}}</th>
                                            <th style="font-weight: 500; color: #ffff; width: 130px; min-width: 225px;">{{__('labels.ProductVariantType')}}</th>
                                            <th style="font-weight: 500; color: #ffff; width: 130px; min-width: 225px;">{{__('labels.ProductVariantName')}}</th>
                                            <th style="font-weight: 500; color: #ffff; width: 100px; min-width: 100px;">{{__('labels.ProductPrice')}}</th>
                                            <th style="font-weight: 500; color: #ffff; width: 100px; min-width: 100px;">{{__('labels.LowAlert')}}</th>
                                            <th style="font-weight: 500; color: #ffff; width: 100px; min-width: 100px;">{{__('labels.ProductVariantQty')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productData['machiceData'] as $item)
                        
                                        <tr id="{{ $item['bin_no'] }}">
                                            <td>
                                                <div class="form-group mb-0 text-center form-control h-auto border-transparent">
                                                    {{ $item['binIndex'] }}
                                                    <input type="hidden" name="kioskProductID[]" value="{{ $item['kioskProductID'] }}" />
                                                    <input type="hidden" name="binNo[]" value="{{ $item['bin_no'] }}" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-0">
                                                    <select name="productID[]" class="trans-select form-control" onchange="getVarient(this.value, {{ $item['bin_no'] }})" id="productID_{{ $item['bin_no'] }}">
                                                        <option value="">Select Product</option>
                                                        @foreach ($productData['productData'] as $itemProd)
                                                        <option value="{{$itemProd->product_id}}" {{ $item['productID'] == $itemProd->product_id ? 'selected' : '' }}>{{$itemProd->product_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-0">
                                                    <select class="trans-select form-control" id="varient_type_{{ $item['bin_no'] }}" onchange="getVarientName(this, {{ $item['bin_no'] }})">
                                                        <option value="">Select Variant Type</option>
                                                        @foreach ($item['VariantTypeList'] as $itemVar)
                                                        <option value="{{$itemVar->product_variant_id}}" {{ $item['productVarintID'] == $itemVar->product_variant_id ? 'selected' : '' }}>{{$itemVar->variant_sku}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-0">
                                                    <select name="productVarintName[]" class="trans-select form-control" id="varient_name_{{ $item['bin_no'] }}" onchange="getVariantPrice(this.value, {{ $item['bin_no'] }})">
                                                        <option value="">Select Variant Name</option>
                                                        @foreach ($item['VariantTypeList'] as $itemVar)
                                                        <option value="{{$itemVar->product_variant_id}}" {{ $item['productVarintID'] == $itemVar->product_variant_id ? 'selected' : '' }}>{{$itemVar->variant_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-0">
                                                    <input type="number" class="form-control numeric inMachine" min="0" value="{{ $item['productPrice'] }}" name="productPrice[]" step=".01" id="varient_price_{{ $item['bin_no'] }}" @if ($item['productPrice'] != '') required @endif>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-0 text-center form-control h-auto border-transparent">

                                                    @php

                                                        if(!empty($item['productQty'])){
                                                            if($productData['kioskData']['machineAlert'] < $item['productQty']){
                                                                echo 'N';
                                                            }

                                                            if($productData['kioskData']['machineAlert'] > $item['productQty']){
                                                                echo 'Y';
                                                            }
                                                        }
                                                    @endphp
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-0">
                                                    <input type="number" class="form-control numeric inMachine" min="0" value="{{ $item['productQty'] }}" name="productQty[]" step=".01" id="quantity_{{ $item['bin_no'] }}" @if ($item['productQty'] != '') required @endif>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach  

                                    </tbody>
                                </table>
                            </div>                                    
                            <!--end::Body-->
                            
                            <!--end::List Widget 14-->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div> 
    </div>

</form>
@endsection

@section('scripts')
    <script src="{{ asset('js/account/machines.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection

