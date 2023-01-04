<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\AccountTrait;
use App\Traits\CommonTrait;
use App\Traits\SubscriptionTrait;
use App\Models\Plan;
use App\Models\Account;
use App\Models\User;
use App\Models\Parameter;
use App\Models\Subscription;
use Storage;
use Mail;
use Stripe;
use App\Mail\CreateAccount;
use App\Mail\UpdatePassword;
use App\Mail\ActivateSubAccount;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    use AccountTrait, CommonTrait, SubscriptionTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->page_title = "Account List";
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $page_title =  $this->page_title;
        $plans = $this->getPlans();
        return view('admin.account.index', compact('page_title','plans'));
    }

    public function list(Request $request){
        return $this->getAllAccount($request);        
    }

    public function create(){
        $page_title =  $this->page_title;
        //$plans = Plan::get();
        $plans = $this->getPlans();
        return view('admin.account.create', compact('page_title','plans'));
    }

    public function store(Request $request){
        $input = $request->all();
        $data = $this->saveAccount($request);
        
        if($data['success']){
            if($input['account_status'] == 'Y'){
                // sent email to user
                try{
                    Mail::to($input['email'])->send(new CreateAccount($input));
                }catch(\Exception $e){
                    \Log::info("Account Creation email failed for ".$input['email']);
                }
            }

            toastr()->success('Account details saved successfully!'); 
            return redirect('home');
        }else{
            toastr()->error($data['message']); 
            return redirect('admin/account/create')->withInput();
        }
    }

    public function view($id){
        try{
            $id = decrypt($id);
        }catch(\Exception $e){
            toastr()->error('Something went wrong'); 
            return redirect('home');
        }
        $page_title =  $this->page_title;
        $user = User::find($id);
        $plans = Plan::get();
        return view('admin.account.view', compact('page_title','user','plans'));
    }

    public function edit($id){
        try{
            $id = decrypt($id);
            $page_title =  $this->page_title;
            $user = User::find($id);
            $plans = $this->getPlans();
            Stripe\Stripe::setApiKey('sk_test_vgtuVEqUbi3cQjJb13Aq3n5G');
            if ($user->hasPaymentMethod()) {
                $data['isPaymentMethodAttach'] = 'Y';
            }else{
                $data['isPaymentMethodAttach'] = 'N';
            }
            return view('admin.account.edit', compact('page_title','user', 'plans','data'));
        }catch(\Exception $e){
            toastr()->error('Something went wrong'); 
            return redirect('home');
        }
    }

    public function update(Request $request){
        $input = $request->all();
        $user = User::find($input['user_id']);
        $previous_status = $user->user_active_yn;

        $data = $this->updateAccount($request);

        if($previous_status == 'N' && $input['account_status'] == 'Y'){
            // send email
            try{
                Mail::to($input['email'])->send(new CreateAccount($input));
            }catch(\Exception $e){
                \Log::info("create account email failed for ".$input['email']);
            }
            // check account type
            if($input['account_type'] == 'sub'){
                // get parent account details
                $parentAccountDetails = $this->getParentAccount($user->accountDetails->account_id_parent);
                // send email to parent account
                try{
                    Mail::to($parentAccountDetails->email)->send(new ActivateSubAccount($input, $parentAccountDetails));
                }catch(\Exception $e){
                    \Log::info("create account email failed for ".$parentAccountDetails->email);
                }
            }
        }else{
            if(isset($input['password']) && $input['password']!='' && $input['account_status'] == 'Y'){
                // send email
                try{
                    Mail::to($input['email'])->send(new UpdatePassword($input));
                }catch(\Exception $e){
                    \Log::info("Update Password email failed for ".$input['email']);
                }
            }
        }
        
        if($data['success']){
            toastr()->success('Account details updated successfully!'); 
            return redirect('home');
        }else{
            toastr()->error($data['message']); 
            return redirect('home');
        }
    }

    public function updateStatus(Request $request){
        $pwd =  Str::random(8);
        $user = User::find($request->id);
        // update account status in account table
        $data = Account::findOrFail($user->account_id);
        $data->account_status = $request->type;
        $data->save();
        // send
        if($request->type == 'Y'){
            // update user password
            $user->password = bcrypt($pwd);
            $user->save();
            // sent email to user
            $input['account_name'] = $data->account_name;
            $input['password'] = $pwd;
            $input['email'] = $user->email;
            try{
                Mail::to($user->email)->send(new CreateAccount($input));
            }catch(\Exception $e){
                \Log::info("Account Creation email failed for ".$user->email);
            }

            if($data->account_type == 'sub'){
                 // get parent account details
                $parentAccountDetails = $this->getParentAccount($data->account_id_parent);
                // send email to parent account
                try{
                    $input['account_id'] = $user->account_id;
                    Mail::to($parentAccountDetails->email)->send(new ActivateSubAccount($input, $parentAccountDetails));
                }catch(\Exception $e){
                    \Log::info("create account email failed for ".$parentAccountDetails->email);
                }
            }
        }
        else{ 
            $this->cancelSubscription($request->id);
        }
        $this->modifyStatus($request, 'User', 'user_active_yn');
    }

    public function showGlobalSetting(){
        $page_title = 'Global Settings';
        $setting = array();
        $parameters = Parameter::get();
        foreach($parameters as $parameter){
            $setting[$parameter->parameter_key] = $parameter->parameter_value;
        }
        return view('admin.setting.edit',compact('page_title','setting'));
    }

    public function updateGlobalSetting(Request $request){
        $inputData = $request->all();
        foreach ($inputData as $key => $data) {
            if($key == 'svi_logo'){
                if($data!='') {
                    $files = $data;                
                    $name = time().$files->getClientOriginalName();
                    $filePath = 'parameter/svi_logo/'.$name;
                    Storage::disk('s3')->put($filePath, file_get_contents($files), 'public');                       
                    $url = Storage::disk('s3')->url($filePath);
                    Parameter::where('parameter_key','svi_logo')->update(['parameter_value' => $url]);
                }
            }else{
                Parameter::where('parameter_key',$key)->update(['parameter_value' => $data]);
            }
        }
        toastr()->success('Settings updated successfully!'); 
        return redirect('admin/global-setting');
    }
}