<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;
use Stripe;

use App\Models\User;
use App\Models\Kiosk;

class SubscriptionController extends Controller
{
    public function __construct(){
        $this->title = 'Content';
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title = $this->title;

        $user = User::find(Auth::id());
        $kiosk_count = Kiosk::where('account_id',$user->account_id)->where('kiosk_status','Y')->count();
        Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
        if($user->accountDetails->account_bypass_subs == 'N'){
            $paymentMethod = $user->defaultPaymentMethod();
            if(empty($paymentMethod)){
                 return view('apps.subscription.update_payment_method', ['intent' => Auth::user()->createSetupIntent(), 'user' => $user, 'showPlan' => 'Yes','kiosk_count' => $kiosk_count]);
            }else{
                return view('apps.subscription.index',compact('user','paymentMethod','kiosk_count'));
            }
        }else{
            $paymentMethod = $user->defaultPaymentMethod();
            if(empty($paymentMethod)){
                return view('apps.subscription.update_payment_method', ['intent' => Auth::user()->createSetupIntent(), 'user' => $user, 'showPlan' => 'Yes', 'kiosk_count' => $kiosk_count]);
            }else{
                return view('apps.subscription.index',compact('user','paymentMethod','kiosk_count'));
            }
        }
    }

    //Save
    public function store(Request $request){
        $input = $request->all();
        unset($input['_token']);

        Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
        $user = User::find(Auth::id());
        $user->updateDefaultPaymentMethod($input['payment_method_identifier']);

        toastr()->success('The card has been verified successfully');
        return redirect('app/account-status');
    }

    public function edit(){
        Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
        return view('apps.subscription.update_payment_method', ['intent' => Auth::user()->createSetupIntent(), 'showPlan' => 'No']);
    }
}