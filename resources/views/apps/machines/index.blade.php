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
                @if ($decrypt_id != '')
                    <h3 class="card-title">Account List > {{$email}} > Manage Machines</h3>
                    <div class="pull-right vcenter">
                        <a href="{{ url('app/machines/add') }}/{{ $decrypt_id }}" class="btn btn-primary mr-2 btn-sm">Create Machine</a>
                        <a href="{{ url('app/accounts') }}" class="btn btn-primary btn-sm">Back</a>
                    </div>   
                @else
                    <h3 class="card-title">Machines List</h3>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-0 table-responsive">
                    <table id="tbl_machines" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr class="bg-primary">
                                <th>{{__('labels.MachineSerial')}}</th>
                                <th>{{__('labels.MachineName')}}</th>
                                <th>{{__('labels.AddressSTREET')}}</th>
                                <th>{{__('labels.AddressCity')}}</th>
                              	<th>{{__('labels.AddressState')}}</th>
                                <th>{{__('labels.AddressCOUNTRY')}}</th>
                                <th>{{__('labels.AddressZip')}}</th>
                              	<th>{{__('labels.MIN_AGE')}}</th>
                              	<th>{{__('labels.MACHINE_MODEL')}}</th>
                              	<th>{{__('labels.MachineTemplate')}}</th>
                                <th>{{__('labels.STATUS')}}</th>
                              	<th>{{__('labels.ColAction')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>

<input type="hidden" id="accountID" value="{{ $decrypt_id }}" />
@endsection

@section('scripts')
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/account/machines.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>

    @if (\Session::has('successFlag'))
    <script>
        jQuery('#common_modal').modal('show');
        jQuery("#modal_content").html('<div class="modal-header"><h5 class="modal-title">SUCCESS</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><p>THANK YOU. YOU WILL BE NOTIFIED BY EMAIL WHEN THIS MACHINE HAS BEEN APPROVED AND ACTIVATED</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>');
        jQuery(".modal-backdrop").hide();
    </script>
    @endif
@endsection

