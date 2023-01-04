@extends('layout.default')
@section('content')

<form action="{{url('app/machines/save')}}" method="post" autocomplete="off" id="form_add_machine">
    @csrf
    <input type="hidden" name="account_id" value="{{$decrypt_id}}">

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Create Machine</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.MACHINE_NAME')}} <span class="error">*</span></label>
                            <input  type="text" name="kiosk_identifier" class="form-control" placeholder=""  value="{{old('kiosk_identifier')}}" maxlength="30" autocomplete="new-kiosk_identifier" required="">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.STREET')}} <span class="error">*</span></label>
                            <input  type="text" name="kiosk_street" class="form-control" placeholder="" value="{{old('kiosk_street')}}" maxlength="200" autocomplete="new-kiosk_street" required="">
                        </div>                        
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.CITY')}} <span class="error">*</span></label>
                            <input  type="text" name="kiosk_city" class="form-control" placeholder="" value="{{old('kiosk_city')}}" maxlength="50" autocomplete="new-kiosk_city" required="">
                        </div>       
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.STATE')}} <span class="error">*</span></label>
                            <input type="text" name="kiosks_state" id="kiosks_state" class="form-control" value="{{old('kiosks_state')}}" maxlength="50" autocomplete="new-kiosks_state" required="">
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.COUNTRY')}} <span class="error">*</span></label>
                            <input type="text" name="kiosk_country" class="form-control" value="{{old('kiosk_country')}}" maxlength="50" autocomplete="new-kiosk_country" required="">
                        </div>                        
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.ZIP')}} <span class="error">*</span></label>
                            <input type="text" name="kiosk_zip" class="form-control" value="{{old('kiosk_zip')}}" maxlength="6" autocomplete="new-kiosk_zip" required="" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <label class="col-form-label">{{__('labels.TIMEZONE')}} <span class="error">*</span></label>
                            <select name="kiosk_timezone" id="kiosk_timezone" class="form-control" required="">
                                <option value="">Select Time Zone</option>                                
                                <option value="America/New_York">ET	</option>
                                <option value="America/Chicago">CT</option>
                                <option value="America/Denver">MT</option>	
                                <option value="America/Los_Angeles">PT</option>	
                            </select>
                        </div>                                                            
                    </div>
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
                        <a href="{{ url('app/machines/list') }}/{{$decrypt_id}}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
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