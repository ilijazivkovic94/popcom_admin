@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <style>
        .promotions-add_formLeft .input-group-addon {
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 400;
            line-height: 2;
            text-align: ;            
        }
        .promotions-add_formLeft .input-group-addon:first-child {
            border-right: 0;
        }
        .promotions-add_formLeft .input-group-addon:first-child{
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .promotions-add_formBlock {
            margin-bottom: 30px;
        }
        .promotions-add_formBlock .modal-body h1, .promotions-add_formBlock .modal-body h1 strong {
            font-size: 20px;
        }
        .promotions-add_formLeft .input-group .input-group-addon.postpend {
            padding: 0;
        }
                
        .promotions-add_formLeft .input-group .input-group-addon button {
            color: #fff;
            border-radius: 0px;
            background-color: rgb(191, 15, 15);
            border-color: rgb(191, 15, 15);
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
    </style>
@endsection

@section('content')
<form action="{{url('app/promotion/update')}}" method="post" autocomplete="off" id="form">
    <input type="hidden" name="promo_id" value="{{$promo->promo_id}}">
    @csrf
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Update Promotion / {{$promo->promo_code}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12" >
                            <div class="form-group">
                                <label class="col-form-label">{{__('labels.CODE')}}<span class="error">*</span></label>
                                <input  type="text" name="promo_code" class="form-control" placeholder=""  value="{{$promo->promo_code}}" maxlength="50" autocomplete="new-promo_code" required="">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('labels.PROMO_DESC')}} </label>
                                <textarea type="text" name="promo_cart_desc" class="form-control" value="" maxlength="500" rows="3">{{$promo->promo_cart_desc}}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('labels.PROMO_MSG')}}</label>
                                <textarea  name="promo_optin_message" id="promoMsg" class="form-control" value=""  maxlength="200" rows="2">{{$promo->promo_optin_message}}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('labels.PROMO_DISCOUNT')}}<span class="error">*</span></label>
                                <input  type="number" name="promo_discount" id="promo_disc" class="form-control" placeholder=""  value="{{$promo->promo_discount}}" maxlength="50" autocomplete="new-promo_discount" required="" min=0 max="100" step="1">
                            </div> 
                            <div class="form-group">
                                <label class="col-form-label">{{__('labels.STATUS')}}<span class="error">*</span></label>
                                <select  name="promo_status" class="form-control" required="">
                                    <option value="N" @if($promo->promo_status == 'N') selected @endif>Inactive</option>
                                    <option value="Y" @if($promo->promo_status == 'Y') selected @endif>Active</option>
                                </select>
                            </div>  
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12" >
                            <div class="promotions-add_formLeft">
                                
                                <div class="form-group">
                                    <label>Assigned Machines</label>
                                    <ul>
                                        @foreach($asignedMachins as $machine)
                                            <li><a href="{{ url('app/machines/edit')}}/{{ encrypt($machine->kiosk_id) }}" class="">{{ $machine->kiosk_identifier }}</a></li>
                                        @endforeach
                                    </ul>
                                </div> 

                                <div class="form-group">
                                    <label>POS Opt-in Preview</label>
                                </div>
                                
                                <div class="promotions-add_formBlock" style="border:1px solid #ccc">
                                    <div class="text-center modal-body">
                                        <h1 id="promo_msg">{{$promo->promo_optin_message}}</h1>
                                        <h1 id="promo_perc" class="mb-5">
                                            <strong><span id="promo_discount">{{$promo->promo_discount}}</span>%</strong>
                                        </h1>
                                        <div class="form-group mx-auto input-group mb-0">
                                            <span class="input-group-addon btn btn-secondary">Email</span>
                                            <input class="form-control" readonly="" placeholder="Enter Your Email" value="">
                                            <span class="input-group-addon postpend ">
                                                <button type="button" disabled class="btn btn-secondary" >Enter</button>
                                            </span>
                                        </div>
                                        <p class="font-weight-light mt-2">We use this information to send you a receipt.&nbsp;</p>
                                    </div>
                                    <h5><br><span></span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 text-center">
                            <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
                            <a href="{{url('app/promotion')}}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                        </div>
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