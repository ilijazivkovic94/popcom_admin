@extends('layout.default')
@section('content')
<form action="{{url('admin/machine-model/update')}}" method="post" autocomplete="off" id="form">
    @csrf
    <input type="hidden" name="kiosk_model_id" value="{{$model->kiosk_model_id}}">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Update Machine Model</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_NAME')}} <span class="error">*</span></label>
                            <input  type="text" name="model_name" class="form-control" placeholder=""  value="{{$model->model_name}}" maxlength="30" autocomplete="new-model_name" required="">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_TYPE')}} <span class="error">*</span></label>
                            <select name="model_type" class="trans-select form-control" required="">
                                <option value="">Select Machine Type</option>
                                <option value="Kiosk"  @if($model->model_type) == 'Kiosk') selected="" @endif>Kiosk</option>
                                <option value="Vending" @if($model->model_type) == 'Vending') selected="" @endif>Vending</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_REF')}} <span class="error">*</span></label>
                            <select name="model_is_refrigerated" class="trans-select form-control" required="">
                                <option value="">Select Status</option>
                                <option value="Y"  @if($model->model_is_refrigerated) == 'Y') selected="" @endif>Yes</option>
                                <option value="N" @if($model->model_is_refrigerated) == 'N') selected="" @endif>No</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_STATUS')}} <span class="error">*</span></label>
                            <select  name="model_avaialble_yn" class="form-control" required="">
                                <option value="">Select Status</option>
                                <option value="N" @if($model->model_avaialble_yn == 'N') selected="" @endif>Inactive</option>
                                <option value="Y" @if($model->model_avaialble_yn == 'Y') selected="" @endif>Active</option>
                            </select>
                        </div>       
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_MANUFACTURER')}} <span class="error">*</span></label>
                            <input  type="text" name="model_manufacturer" class="form-control" value="{{$model->model_manufacturer}}"  maxlength="30" autocomplete="new-model_manufacturer" required="">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_DESC')}}</label>
                            <textarea  name="model_description" class="form-control" value=""  maxlength="450" autocomplete="new-model_description" rows="8">{{$model->model_description}}</textarea>
                        </div>
                                             
                    </div>
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
                        <a href="{{url('admin/machine-model')}}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</form>
@endsection
@section('scripts')
    <script src="{{ asset('js/admin/machine_model.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection