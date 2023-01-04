@extends('layout.default')

{{-- Styles Section --}}
@section('styles')

@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title">Machine Mapping</h3>         
                <div class="pull-right vcenter">
                	<a href="{{url('app/machines-inventory')}}" class="btn btn-primary">Back</a>
                </div>      
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin::List Widget 14-->
                        <div class="card card-custom card-stretch gutter-b">
                            
                            <!--begin::Header-->
                            <div class="card-header border-0">
                                <h3 class="card-title font-weight-bolder text-dark">{{ $productData['kioskData']['machineName'] }} - {{ $productData['kioskData']['machineCity'] }} {{ $productData['kioskData']['machineState'] }}</h3>
                                <div class="card-toolbar">
                                    <h3 class="card-title font-weight-bolder text-dark">Low Level Alert for this Machine: {{ $productData['kioskData']['machineAlert'] }}</h3>
                                </div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-2">

                                @foreach ($productData['productData'] as $item)
                                    <!--begin::Item-->
                                    <div class="d-flex flex-wrap align-items-center mb-10">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-60 symbol-2by3 flex-shrink-0 mr-4">
                                            <div class="symbol-label" style="background-image: url('{{ $item['productImage'] }}')"></div>
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Title-->
                                        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                            @if ($item['productID'] != '')
                                                <a href="{{ url('app/product/edit') }}/{{encrypt($item['productID'])}}" class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg" target="_blank">{{ $item['productName'] }}</a>
                                            @else
                                                <span class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg">{{ $item['productName'] }}</span>
                                            @endif
                                            {{-- <span class="text-muted font-weight-bold font-size-sm my-1">Local, clean &amp; environmental</span> --}}
                                            <span class="text-muted font-weight-bold font-size-lg">{{ $item['productSize'] }}: 
                                            <span class="text-dark-75 font-weight-bold">{{ $item['productVariantName'] }}</span></span>
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Info-->
                                        <div class="d-flex align-items-center py-lg-0 py-2">
                                            <div class="d-flex flex-column text-right">
                                                <span class="text-dark-75 font-weight-bolder font-size-h4">QTY: {{ $item['productQty'] }}</span>
                                                <span class="text-muted font-size-lg font-weight-bolder">{{ $item['binIndex'] }}</span>
                                            </div>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Item-->
                                @endforeach                                
                                
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List Widget 14-->
                    </div>
                    
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection

@section('scripts')
    
@endsection

