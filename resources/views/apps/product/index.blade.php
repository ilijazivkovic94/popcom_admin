@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style type="text/css">
    #tbl_account .productImage:before{content: "";}
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title">Products</h3>        
                <div class="pull-right vcenter">
                    @if (CommonHelper::SubAccountSetting('products_create') == 'Y')
                    <a href="{{ url('app/product/add') }}" class="btn btn-primary btn-sm mr-2">Add Product</a>    
                    @endif                	
                    <a href="{{ url('app/product/export') }}" class="btn btn-primary btn-sm">Export</a>
                </div>        
            </div>
            <div class="card-body">
                <div class="mb-0 table-responsive">
                    <table id="tbl_account" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr class="bg-primary">
                                <th class="productImage">{{__('labels.ProductsImage')}}</th>
                              	<th>{{__('labels.ProductsName')}}</th>
                              	<th>{{__('labels.MachineName')}}</th>
                                <?php if($accType == 'sub'): ?>
                                    <th>{{__('labels.ParentName')}}</th>
                                <?php endif; ?>
                                <th>{{__('labels.ProductVariant')}}</th>
                              	<th style="width: 150px">{{__('labels.ColAction')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>

{{-- Retire Product --}}
<div class="modal fade" id="retireModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Do you want to retire this product?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="">
                    <label class="col-form-label">{{__('labels.ProductRetireDate')}} <span class="error">*</span></label>
                    <input type="date" class="form-control" name="retire_date" id="retire_date" value=""  data-date-format="MM DD YYYY"/>
                    <input type="hidden" class="form-control" name="retire_product_id" id="retire_product_id" value="" />
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary saveRetireModal">{{__('labels.SAVE')}}</button>
                <button class="btn btn-danger" data-dismiss="modal">{{__('labels.CANCEL')}}</button>
            </div>
        </div>        
    </div>
</div>

<input type="hidden" value="{{ Config::get('constants.ProductDelete') }}" id="productDeleteMsg" />
<input type="hidden" id="accType" value="{{ $accType }}" />
@endsection

@section('scripts')
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/plugins/custom/bootbox/bootbox.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/plugins/custom/bootbox/bootbox.locales.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/account/products.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection

