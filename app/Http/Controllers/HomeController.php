<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use DB;
use Stripe;
use Config;

use App\Models\User;
use App\Models\Category;
use App\Models\Video;
use App\Models\Group;
use App\Models\Subscription;
use App\Models\Kiosk;
use App\Models\Account  ;


use App\Traits\CommonTrait;
use App\Traits\DashboardDataTrait;
use App\Traits\SettingTrait;
use App\Helpers\CommonHelper;

class HomeController extends Controller
{
    use CommonTrait, DashboardDataTrait, SettingTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        if(Auth::user()->user_admin_yn == 'Y'){
            $page_title = "Account List";
            $plans = $this->getPlans();
            return view('admin.account.index',compact('page_title','plans'));
        }else{

            $page_title = "Home";
            $setting    = $this->getSetting();
            $machines   = $this->getMachines();
            $accType    = Auth::user()->accountDetails->account_type;
            $subAccounts = CommonHelper::SubAccountDetails();
            Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
            // check user subscription
            if(Auth::user()->accountDetails->account_bypass_subs == 'N'){
                // check subscription purchase or not
                $getSubscription = Subscription::where('user_id',Auth::id())->first();
                if(!empty($getSubscription)){
                    // check wether subscription is active or not
                    if(Auth::user()->subscribed($getSubscription->name)) {
                        // check user has default method or not
                        $paymentMethod = Auth::user()->defaultPaymentMethod();
                        if(empty($paymentMethod)){
                            return redirect('app/account-status');
                        } else{
                            // check whether any machine assign or not
                            $kiosk_count = Kiosk::where('account_id',Auth::user()->account_id)->where('kiosk_status','Y')->count();
                            if($kiosk_count == 0){
                                return redirect('app/account-status');
                            }
                        }
                    } else{
                        return redirect('app/account-status');
                    }
                } else {
                    return redirect('app/account-status');
                }
            }

            if ($request->ajax()) {
                $input          = $request->all();
                $account_id     = (!empty($input['account_id'])) ? $input['account_id'] : null;
                $kiosk_id = (!empty($input['kiosk_id'])) ? $input['kiosk_id'] : null;
                $salesData      = $this->getSalesData($input['time'],$setting,$account_id);
                $customerData   = $this->customerData($input['time'],$setting,$account_id);
                $visitorData    = $this->visitorData($input['time'],$setting,$account_id);
                $viewsData    = $this->viewsData($input['time'],$setting,$account_id);
                $machines       = $this->getMachines($account_id);

                $view           = view('apps.homeajax', compact('salesData', 'machines', 'visitorData', 'customerData', 'accType', 'subAccounts', 'account_id', 'viewsData'))->render();
                return response()->json(['html' => $view]);
            }

            $salesData      = $this->getSalesData('today',$setting);
            $customerData   = $this->customerData('today',$setting);
            $visitorData    = $this->visitorData('today',$setting);
            $viewsData    = $this->viewsData('today', $setting);

            return view('apps.index', compact('page_title', 'salesData', 'machines', 'visitorData', 'customerData', 'accType', 'subAccounts', 'viewsData'));
        }
    }

    public function counterData(Request $request){
        if ($request->ajax()) {
                $input = $request->all();
                $setting = $this->getSetting();
                $kiosk_id = ($input['kiosk_id'] == 'all') ? null : $input['kiosk_id'];

                $customerData = $this->customerData($input['time'],$setting,$input['account_id'],$kiosk_id);
                $visitorData = $this->visitorData($input['time'],$setting,$input['account_id'],$kiosk_id);

                $view = view('apps.home.ajaxcard',compact('visitorData','customerData'))->render();

                return response()->json(['html'=>$view]);
        }
    }

    public function account(){
        $page_title = "Account";
        return view('admin.profile.index', compact('page_title'));
    }

    public function update_password(Request $request){
        try{
            $input = $request->all();
            $searchinput['id'] = Auth::id();

            if(password_verify($input['old_password'], Auth::user()->password)){
                if($request->new_password!=''){
                    $input['password'] = Hash::make($request->new_password);
                }
                else{
                    unset($input['password']);
                }

                User::updateorCreate($searchinput, $input);
                toastr()->success('Password updated successfully!');
            }
            else{
                toastr()->error('Old password does not match');
            }
        }catch(\Exception $e){
            toastr()->error('Something went wrong');
        }
        return redirect('account');
    }

    public function logout() {
        Auth::user()->logoutFactorCode();
        Auth::logout();
        return redirect('/login');
    }

    public function accessLogin($id){
        try{
            if($id!=1){
                $id = decrypt($id);
            }
        }catch(\Exception $e){
            toastr()->error('Something went wrong');
            return redirect('home');
        }
        $this->doLogin($id);
        return redirect('home');
    }

    public function get_low_inventory_alert_data(){
        $account_id = Auth::user()->accountDetails->account_id;
        $notification_alert = Auth::user()->accountDetails->notification_alert;
        $tempArray = array();
        if($notification_alert == 'Y'){
            $machine_variant_list = $this->_get_tenant_machine_variant_list($account_id);

            if($machine_variant_list !== false){
                foreach ($machine_variant_list as $key => $row) {
                    // print_r($row);
                    if(($row['quantity'] < $row['kiosk_low_inv_threshold'] || $row['quantity'] == 0) && $row['bay_no']!=null && $row['bay_no'] <= $row['template_bin_count']) {
                        $abc = explode("," , $row['template_bin_identity']);
                        $row['bay_no'] = $abc[$row['bay_no'] - 1];
                        $tempArray[] = $row;
                    }
                }

                if(count($tempArray) > 0){
                    return response()->json(['success'=>true,'result'=>$tempArray]);
                }else{
                    return response()->json(['success'=>false]);
                }
            }else{
                return response()->json(['success'=>false]);
            }
        }else{
             return response()->json(['success'=>false]);
         }
    }

    public function disable_notification_alert(){
        $account_id = Auth::user()->accountDetails->account_id;

        $update = Account::where('account_id',$account_id)
                            ->update(['notification_alert'=>'N']);
        if($update){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }

    }

}
