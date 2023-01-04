@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
@endsection

@section('content')

<form action="{{url('app/account-status/save')}}" method="post" autocomplete="off" id="form">
    @csrf
    <input type="hidden" name="payment_method_identifier" value="" id="payment_method_identifier">
</form>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header bg-pro">
                <h3 class="card-title color-white">Account Status</h3>
            </div>
            <div class="card-body">
                @if($showPlan == "Yes")
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-2">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-2 col-xs-10">
                            <label><b>Plan</b></label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-10 col-xs-2">
                            @if($user->accountDetails->account_bypass_subs == 'N')
                                <b>{{!empty($user->subscriptionDetails->name)?$user->subscriptionDetails->name:''}}</b>
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
                @endif
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="col-form-label">Card Holder Name</label>
                        <input id="card-holder-name" type="text" class="form-control" placeholder="Card Holder Name" required="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="col-form-label">Please attach your card to begin your subscription</label>
                        <!-- Stripe Elements Placeholder -->
                        <div id="card-element"></div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <button class="btn btn-primary" id="card-button" data-secret="{{ $intent->client_secret }}">
                        Link your Card
                    </button>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p><br/>The Card you add here will be used to charge your monthly machine subscription fee</p>
                    <p>No Card Information is stored in Popcom and Stripe securely handles all your information, which is PCI DSS Compliant.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>

<script>
    var stripekey = "<?php echo Config::get('services.stripe.key') ?>";
    const stripe = Stripe(stripekey);

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: { name: cardHolderName.value }
                }
            }
        );
        if (error) {
            toastr.error(error.message);
        } else {
            $("#payment_method_identifier").val(setupIntent.payment_method);
            $("#form").submit();
        }
    });
</script>

@endsection