@extends('layout.default')
{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
<input type="hidden" name="id" value="{{$id}}" id="uid">
<div class="row">
    <div class="col-lg-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title">Account List > @if($user->accountSetting->account_poc!='') {{$user->accountSetting->account_poc}} @else {{$user->email}} @endif > Manage Machines</h3>
                <div class="pull-right vcenter">
                	<a href="{{url('admin/machine/create')}}/{{$decrypt_id}}" class="btn btn-primary btn-sm">Create Machine</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Machine Model</label>
                        <select id="machine_model" class="form-control" >
                            <option value="">Select</option>
                            @if($models->isNotEmpty())
                                @foreach($models as $model)
                                    <option value="{{$model->model_name}}">{{$model->model_name}}</option>
                                @endforeach
                            @endif
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
                    <table id="tbl_machine" class="table table-bordered table-striped tbl_account" width="100%">
                        <thead>
                            <tr class="bg-primary">
                            	<th>Machine Serial</th>
                              	<th>Machine Name</th>
                                <th style="min-width: 100px">Street</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Zip</th>
                                <th>Minimum Age</th>
                                <th>Machine Model</th>
                                <th>Template</th>
                              	<th style="min-width: 55px">Status</th>
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
    <script src="{{ asset('js/admin/machine.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection