<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\PaymentMethod;
use Auth;
use Stripe;
use DB;
use Config;
use App\Helpers\CommonHelper;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Account;
use App\Models\AccountSetting;
use App\Models\Plan;
use App\Models\SubAccountSettings;
use App\Models\Subscription;
use App\Traits\CommonTrait;
use App\Traits\SubscriptionTrait;
use App\Models\Ad;

trait AccountTrait {    

    use CommonTrait, SubscriptionTrait;

    public function getAllAccount(Request $request){
        if ($request->ajax()) {
            $data = User::where('user_admin_yn','N')->with('accountDetails')->get();
        
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_active_yn', function ($row) {
                    if($row->user_active_yn == 'Y'){
                        $status = "<button title='Active' data-id='$row->id' data-type='N' class='btn btn-success btn-sm status'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' data-id='$row->id' data-type='Y' class='btn btn-danger btn-sm status'>Inactive</button>";
                    }
                    return $status;
                })
                ->editColumn('account_type', function ($row) {
                    if($row->accountDetails['account_type'] == 'ent'){
                        $account_type = "Parent";
                    }
                    elseif($row->accountDetails['account_type'] == 'sub'){
                        $account_type = "Sub-Account";
                    }else{
                        $account_type = "Standard";
                    }
                    return $account_type;
                })
                ->addColumn('action', function($row){
                    $id = encrypt($row->id);
                    $btn = "<a href='admin/account/edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon mb-2' title='Edit Account'><i class='fas fa-edit fsize13'></i></a> 
                        <button data-url='access-login/$id' class='btn btn-sm btn-dark btn-text-primary btn-icon mb-2 show_popup' title='Login as subadmin' data-message='Your current session will expire, Please click below to continue.' data-title='Login as subadmin'><i class='fas fa-user fsize13'></i></button>
                        ";
                        //  <a href='admin/account/view/$id' class='btn btn-sm btn-warning btn-text-primary btn-icon mb-2' title='View'><i class='fas fa-eye fsize13'></i></a> 
                    if($row->accountDetails['account_type']!= 'ent'){
                        $btn .= "<a href='admin/machine/$id' class='btn btn-sm btn-warning btn-text-primary btn-icon mb-2' title='Manage Machines of Account'><i class='fas fa-industry fsize13'></i></a>";
                    }
                    return $btn;
                })
                ->editColumn('created_at', function ($row) {
                    if($row->created_at!=''){
                        return CommonHelper::DateFormat($row->created_at);
                    }else{
                        return;
                    }
                })
                ->addColumn('stripe_plan', function ($row) {
                    if($row->accountDetails->account_bypass_subs == 'Y'){
                        return 'bypassed';
                    }else{
                        $subscription = DB::table('subscriptions')->where('user_id',$row->id)->first();
                        if(!empty($subscription)){
                            return $subscription->name;
                        }
                    }   return '';
                })
                ->addColumn('parent', function ($row) {
                    if($row->accountDetails['account_type'] == 'sub'){
                        $account = Account::where('account_id',$row->accountDetails['account_id_parent'])->first();
                        if(!empty($account)){
                            return $account->account_name;
                        }
                    }
                    return 'N/A';
                })
                ->addColumn('status', function ($row) {
                    return $row->user_active_yn;
                })
                ->rawColumns(['user_active_yn','created_at','action'])
                ->make(true);
        }
    }

    // store data into account, user and account setting table
    public function saveAccount(Request $request){
        DB::beginTransaction();
        try{
            $input          = $request->all();
            $accountData    = $this->checkAccount($input['account_name']);
            if($accountData->isNotEmpty()){
                $response['message'] = config('message.EXISTS_ACCOUNT_NAME');
                $response['success'] = false;
                return $response;
            }
 
            $data = $this->checkEmail($input['email']);
            if($data->isNotEmpty()){
                $response['message'] = config('message.EXISTS_EMAIL');
                $response['success'] = false;
                return $response;
            }

            if($request->bypass_subscription!=''){
                $input['account_bypass_subs'] = 'Y';
            }else{
                $input['account_bypass_subs'] = 'N';
            }

            $input['password']      = bcrypt($input['password']);
            $input['created_at']    = round(microtime(true) * 1000);
            $input['modified_at']   = round(microtime(true) * 1000);

            // save data into account table
            $account = Account::create($input);

            $input['account_id']        = $account->account_id;
            $input['user_active_yn']    = $input['account_status'];
            $input['user_admin_yn']     = 'N';
            $input['trial_ends_at']     = now()->addDays(10);
            // save data into user table
            $user = User::create($input);
            // create stripe customer
            Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
            $user->createAsStripeCustomer();
            // save data into account setting table
            AccountSetting::create($input);
            // save data into sub account setting table if account type is parent
            if($input['account_type'] == 'ent'){
                SubAccountSettings::create($input);
            }
            // save data into ads table account type is standard
            if($input['account_type'] == 'std'){
                $adInput['ad_title']    = 'PopCom PopShop Video';
                $adInput['ad_type']     = 'video';
                $adInput['ad_status']   = 'Y';
                $adInput['created_at']  = round(microtime(true) * 1000);
                $adInput['modified_at'] = round(microtime(true) * 1000);
                $adInput['ad_data']     = 'https://popcom-saas.s3.us-east-2.amazonaws.com/147/popcomtestvideoadvertisement_1617956762072.mp4';
                $adInput['account_id']  =  $input['account_id'];
                Ad::create($adInput);
                // save default product
                $this->saveDefaultProduct($input['account_id']);
            }

            // add subscription
            if(isset($input['plan_id']) && $input['account_bypass_subs'] == 'N' && $input['account_status'] == 'Y'){ 
                //$plan = Plan::find($input['plan_id']);
                $planDetails = $this->getPlanDetails($input['plan_id']);
                $productDetails = $this->getProductDetails($planDetails->product);
                $anchor = Carbon::parse('first day of next month');
                if($planDetails->interval == 'month'){
                    // $anchor =  Carbon::now()->endOfMonth();
                    // $anchor = $anchor->startOfDay();
                    $anchor =  Carbon::now();
                    // $billing_cycle_anchor_time = strtotime(date('Y-m-d', mktime(0, 0, 0, date('m')+1, 1, date('Y'))));
                    \Log::info($anchor);
                    $subsc = $user->newSubscription($productDetails->name, $planDetails->id)
                        ->trialUntil(Carbon::now()->addDays(1)->startOfDay())
                        // ->anchorBillingCycleOn($anchor)
                        ->quantity(0)
                        ->create(null);
                }elseif($planDetails->interval == 'day'){
                    $billing_cycle_anchor_time = strtotime(date("Y-m-d", strtotime("+1 day")));
                    $subsc = $user->newSubscription($productDetails->name, $planDetails->id)
                        ->trialUntil(Carbon::now()->addDays(1)->startOfDay())
                        // ->anchorBillingCycleOn($billing_cycle_anchor_time)
                        ->quantity(0)
                        ->create(null);
                }

                DB::table('subscriptions')->where('id',$subsc->id)->update(['account_id' => $input['account_id']]);
            }

            $response['success'] = true;
            DB::commit();
        }catch(\Exception $e){
            
            $response['message'] = $e;
            $response['success'] = false;
            DB::rollback();
            return $response;
        }
        return $response;
    }

    public function updateAccount(Request $request){
        try{
            $input = $request->all();
            $user = User::find($input['user_id']);
            $previous_status = $user->user_active_yn;
            // set stripe api key
            Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));

            if($request->bypass_subscription!=''){
                $input['account_bypass_subs'] = 'Y';
            }
            else{
                $input['account_bypass_subs'] = 'N';
            }
            if(isset($input['password']) && $input['password']!=''){
                $input['password'] = bcrypt($input['password']);
            }
            $input['user_active_yn'] = $input['account_status'];
            $searchInput['id'] = $input['user_id'];
            $accountSearchInput['account_id'] = $input['account_id'];
            $accountSettingSearchInput['account_setting_id'] = $input['account_setting_id'];

            User::updateorCreate($searchInput, $input);
            Account::updateorCreate($accountSearchInput, $input);
            accountSetting::updateorCreate($accountSettingSearchInput, $input);
            // check if user has already subscription or not
            $getSubscription = Subscription::where('user_id',$input['user_id'])->first();
            
            if(!empty($getSubscription)){
                // now tries to do by-pass
                if($input['account_bypass_subs'] == 'Y'){
                    // cancel subscription
                    $this->cancelSubscription($input['user_id']);
                }else{
                    if($input['account_status'] == 'Y'){
                        $planDetails = $this->getPlanDetails($input['plan_id']);
                        $productDetails = $this->getProductDetails($planDetails->product);
                        $user->subscription($getSubscription->name)->swap($input['plan_id']);
                    }else{
                        // cancel subcription
                        $this->cancelSubscription($input['user_id']);
                    }
                }
            }else{
                // / add subscription
                if(isset($input['plan_id']) && $input['account_bypass_subs'] == 'N' && $input['account_status'] == 'Y'){  
                    $planDetails = $this->getPlanDetails($input['plan_id']);
                    $productDetails = $this->getProductDetails($planDetails->product);
                    // $anchor = Carbon::parse('first day of next month');
                    if($planDetails->interval == 'month'){
                       // $anchor = $anchor->startOfDay();
                        $subsc = $user->newSubscription($productDetails->name, $planDetails->id)
                            ->trialUntil(Carbon::now()->addDays(1)->startOfDay())
                            // ->anchorBillingCycleOn($anchor)
                            ->quantity(0)
                            ->create(null);
                    }elseif($planDetails->interval == 'day'){
                         $billing_cycle_anchor_time = strtotime(date("Y-m-d", strtotime("+1 day")));
                        $subsc = $user->newSubscription($productDetails->name, $planDetails->id)
                            ->trialUntil(Carbon::now()->addDays(1)->startOfDay())
                            // ->anchorBillingCycleOn($billing_cycle_anchor_time)
                            ->quantity(0)
                            ->create(null);
                    }

                    DB::table('subscriptions')->where('id',$subsc->id)->update(['account_id' => $input['account_id']]);
                }
            }
            // check status for sending email
            $response['success'] = true;
        }catch(\Exception $e){
            $response['message'] = $e;
            $response['success'] = false;
        }
        return $response;
    }

    public function checkEmail($email){
        $user = User::where('email',$email)->get();
        return $user;
    }

    public function checkAccount($account_name){
        $account = Account::where('account_name',$account_name)->get();
        return $account;
    }
}