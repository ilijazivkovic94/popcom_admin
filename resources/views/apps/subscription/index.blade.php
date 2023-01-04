@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header bg-pro">
                <h3 class="card-title color-white">Account Status</h3>
            </div>
            <div class="card-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-2">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-2 col-xs-10">
                            <label><b>Plan</b></label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-10 col-xs-2">
                            @if($user->accountDetails->account_bypass_subs == 'N')
                                @if(!empty($user->subscriptionDetails->name))
                                    <b>{{$user->subscriptionDetails->name}}</b>
                                @else
                                     <b>No Subscription. Admin has bypassed subscription plan for you.</b>
                                @endif
                            @else
                                <b>No Subscription. Admin has bypassed subscription plan for you.</b>
                            @endif
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-2 col-xs-10">
                            <label><b>No of Machines</b></label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12">
                            <b>{{$kiosk_count}}</b>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-lg-12">
                    <b>Configured Card</b>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-2 col-xs-10">
                            <label>Card last 4 digit </label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-10 col-xs-2">
                            @if($paymentMethod->last4!='')
                                 {{ $paymentMethod->last4}}
                            @else
                                {{ $paymentMethod->card->last4}}
                            @endif
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-2 col-xs-10"> 
                            <label>Brand</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12">
                            @if($paymentMethod->brand!='')
                                {{ $paymentMethod->brand}}
                            @else
                                {{ $paymentMethod->card->brand}}
                            @endif
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p><br/>If you want to update your card, please click on "Update Card Details" button</p>
                            <p>No Card Information is stored in Popcom and Stripe securely handles all your information, which is PCI DSS Compliant.</p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <a href="{{url('app/account-status/edit')}}"><button class="btn btn-primary" id="card-button">
                                Update Card Details
                            </button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection