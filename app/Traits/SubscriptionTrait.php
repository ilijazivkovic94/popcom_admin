<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Auth;
use Stripe;
use Config;
use App\Models\User;
use App\Models\Subscription;

trait SubscriptionTrait {  
    //use SubscriptionTrait;

    public function cancelSubscription($user_id){
         // set stripe api key
        Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
        
        $user = User::find($user_id);
        $getSubscription = Subscription::where('user_id',$user_id)->first();
        if(!empty($getSubscription)){
            if ($user->subscribed($getSubscription->name)) {
                if ($user->subscription($getSubscription->name)->cancelled()) {
                }else{
                    $user->subscription($getSubscription->name)->cancel();
                }
            }
        }
        return;
    }

    public function addSubscriptionQuantity($user_id){
          // set stripe api key
        Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
        
        $user = User::find($user_id);
        $getSubscription = Subscription::where('user_id',$user_id)->first();
        if(!empty($getSubscription)){
            if ($user->subscribed($getSubscription->name)) {
                $user->subscription($getSubscription->name)->incrementQuantity();
            }
        }

        return;
    }  

    public function removeSubscriptionQuantity($user_id){
         \Log::info("remove subsc");
          // set stripe api key
        Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
        
        $user = User::find($user_id);
        $getSubscription = Subscription::where('user_id',$user_id)->first();
        if(!empty($getSubscription)){
            if ($user->subscribed($getSubscription->name)) {
                 if($getSubscription->quantity == 1){
                     $user->subscription($getSubscription->name)->updateQuantity(0);
                 }else{
                    $user->subscription($getSubscription->name)->decrementQuantity();
                }
            }
        }

        return;
    }   

    public function checkUserSubscription($user_id){
          // set stripe api key
        Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
        
        $user = User::find($user_id);
        if($user->accountDetails->account_bypass_subs == 'Y'){
            $data['status'] = 'success';
            $data['subscription'] = 'No';
            return $data;
        }else{
            $getSubscription = Subscription::where('user_id',$user_id)->first();
            if(!empty($getSubscription)){
                $paymentMethod = $user->defaultPaymentMethod();
                if(empty($paymentMethod)){
                    $data['status'] = 'error';
                    $data['subscription'] = 'No';
                    $data['message'] = 'Please attach credit card';
                    return $data;
                }else{
                    $data['status'] = 'success';
                    $data['subscription'] = 'Yes';
                    return $data;
                }
            }else{
                $data['status'] = 'error';
                $data['subscription'] = 'No';
                $data['message'] = 'Please purchase subscription';
                return $data;
            }
        }
    }
}