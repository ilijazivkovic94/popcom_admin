<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;
use Stripe;
use DB;

use App\Models\Subscription;
use App\Models\User;

class APIController extends Controller
{
    public function updateSubscription(){
        $subscriptions = Subscription::where('stripe_plan','!=','')->get();
        if($subscriptions->isNotEmpty()){
            foreach ($subscriptions as $subscription) {
                $qty = $pname = $status = '';
                \Log::info("subscription id ".$subscription->id);
                $stripe = new \Stripe\StripeClient(
                  'sk_test_vgtuVEqUbi3cQjJb13Aq3n5G'
                );
                $subc = $stripe->subscriptions->all();
                if(!empty($subc)){
                    foreach ($subc as $sub) {
                        $qty = $sub->quantity;
                        $pname = $sub->plan->name;
                        $status = $sub->status;
                    }
                }
                // get card details
                $user = User::find($subscription->user_id);
                $cards = $stripe->customers->allSources(
                  $user->stripe_id,
                  ['object' => 'card']
                );

                if(!empty($cards)){
                    foreach ($cards as $card) {
                       $last = $card->last4;
                       $brand = $card->brand;
                    }
                }


                DB::table('subscriptions')->where('id',$subscription->id)->update(['name' => $pname, 'quantity' => $qty, 'stripe_status' => $status]);

                 DB::table('users')->where('id',$subscription->user_id)->update(['card_brand' => $brand, 'card_last_four' => $last]);
            }
        }
        return true;
    }
}