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
                <h3 class="card-title">Machine Models</h3>
                <div class="pull-right vcenter">
                	<a href="{{url('admin/machine-model/create')}}" class="btn btn-primary btn-sm">Create Machine Model</a>
                </div>
            </div>
            <div class="card-body">
                 <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Machine Type</label>
                        <select id="machine_type" class="form-control" >
                          <option value="">Select</option>
                          <option value="Vending">Vending</option>
                          <option value="Kiosk">Kiosk</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select id="status" class="form-control" >
                          <option value="">Select</option>
                          <option value="Y">Active</option>
                          <option value="N">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="mb-0 table-responsive">
                    <table id="tbl_machine_model" class="table table-bordered table-striped tbl_account" width="100%">
                        <thead>
                            <tr class="bg-primary">
                            	<th>Machine Name</th>
                              	<th>Type</th>
                              	<th>Created On</th>
                                <th>Modified On</th>
                              	<th>Status</th>
                              	<th>Action</th>
                                <th style="display: none;"></th>
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
    <script src="{{ asset('js/admin/machine_model.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection