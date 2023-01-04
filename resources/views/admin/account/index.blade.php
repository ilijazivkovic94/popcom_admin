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
                <h3 class="card-title">{{$page_title}}</h3>
                <div class="pull-right vcenter">
                	<a href="{{url('admin/account/create')}}" class="btn btn-primary btn-sm">Create Account</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Account Type</label>
                        <select id="account" class="form-control" >
                          <option value="">Select</option>
                          <option value="Standard">Standard</option>
                          <option value="Parent">Parent</option>
                          <option value="Sub-Account">Sub-Account</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Plan</label>
                        <select id="plan" class="form-control" >
                          <option value="">Select</option>
                          <option value="bypassed">bypassed</option>
                          @if(!empty($plans))
                            @foreach($plans as $plan)
                                <option value="{{$plan['product_name']}}">{{$plan['product_name']}}</option>
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
                    <table id="tbl_account" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr class="bg-primary">
                            	  <th>Account ID</th>
                              	<th>Account Name</th>
                              	<th style="min-width: 170px">Login Email</th>
                              	<th>Account Type</th>
                              	<th>Parent</th>
                              	<th style="min-width: 150px;">Stripe Plan</th>
                              	<th style="min-width: 70px;">Created On</th>
                              	<th style="min-width: 55px">Status</th>
                              	<th style="min-width: 115px">Action</th>
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
    <script src="{{ asset('js/admin/account.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection