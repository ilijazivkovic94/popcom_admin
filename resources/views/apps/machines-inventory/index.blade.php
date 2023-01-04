@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title">Manage Machines Inventory</h3>               
            </div>
            <div class="card-body">
                <div class="mb-0 table-responsive">
                    <table id="tbl_account" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr class="bg-primary">
                                <th>{{__('labels.MachineName')}}</th>
                              	<th>{{__('labels.AddressCity')}}</th>
                              	<th>{{__('labels.AddressState')}}</th>
                              	<th>{{__('labels.TotalProducts')}}</th>
                              	<th>{{__('labels.LowAlert')}}</th>
                              	<th>{{__('labels.AssProducts')}}</th>
                              	<th>{{__('labels.ColAction')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection

@section('scripts')
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/account/machines.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection

