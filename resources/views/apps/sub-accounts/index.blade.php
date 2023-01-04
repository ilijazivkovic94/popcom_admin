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
                <h3 class="card-title">Sub-Account List</h3>        
                <div class="pull-right vcenter">
                    <a href="{{ url('app/accounts/add') }}" class="btn btn-primary btn-sm mr-2">Create Sub-Account</a>
                </div>        
            </div>
            <div class="card-body">
                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <p class="mb-0">This is where you see all your sub-accounts. Although we can do this for you, you can also add new sub-accounts and add machines to them. That will send a notification to us to make those accounts and machines active.</p>
                </div>

                <div class="mb-0 table-responsive">
                    <table id="tbl_account" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr class="bg-primary">
                                <th>{{__('labels.SubAccoutID')}}</th>
                              	<th>{{__('labels.SubAccoutName')}}</th>
                              	<th>{{__('labels.SubAccoutEmail')}}</th>
                                <th>{{__('labels.SubAccoutFullName')}}</th>
                                <th>{{__('labels.SubAccoutCreate')}}</th>
                                <th>{{__('labels.SubAccoutStatus')}}</th>
                              	<th>{{__('labels.ColAction')}}</th>
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
    <script src="{{ asset('js/account/sub-accounts.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>

    @if (Session::get('popFlag'))
    <script type="text/javascript">
        jQuery(document).ready(function(){
            $('#common_modal').modal('show');
            $("#modal_content").html('<div class="modal-header"><h5 class="modal-title">SUCCESS</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><p>THANK YOU. YOU WILL BE NOTIFIED BY EMAIL WHEN THIS SUB-ACCOUNT HAS BEEN APPROVED AND ACTIVATED</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>');
            $(".modal-backdrop").hide();
        });
    </script>
    @endif
@endsection

