<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;

use App\Models\User;

use App\Traits\CommonTrait;
use App\Traits\SettingTrait;
use App\Models\Parameter;

class SettingController extends Controller
{
    use CommonTrait, SettingTrait;

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title     = "Manage Setting";
        $setting        = $this->getSetting();
        $countryCode    = $this->getCountryCode();
        if($setting->accountDetails->account_type == 'sub'){
            $SubSetting = User::subAccountSetting($setting->accountDetails->account_id_parent);
            return view('apps.setting.index', compact('page_title', 'setting', 'countryCode', 'SubSetting'));
        }
        return view('apps.setting.index', compact('page_title', 'setting', 'countryCode'));
    }

    //Update
    public function update(Request $request){
        $data = $this->updateSetting($request);
        if($data == true){
            $msg = Config::get('constants.SettingUpdateSuccess');
            toastr()->success($msg, 'Setting');  
            return redirect('home');          
        }else{
            $msg = Config::get('constants.CommonError');
            toastr()->error($msg);
            return redirect('app/setting');
        }        
    }

    //Send OTP
    public function sendOTP(Request $request){
        $data = $this->sendOTPData($request);
        if($data == 'password'){
            return response()->json(['status' => false, 'message' => Config::get('constants.SettingPassError') ], 200);
        }elseif($data == 'Fail'){
            return response()->json(['status' => false, 'message' => Config::get('constants.CommonError') ], 200);
        }else{
            return response()->json(['status' => true, 'message' => Config::get('constants.SettingOTPSuccess') ], 200);
        }
    }

    //Check OTP
    public function checkOTP(Request $request){
        $data = $this->checkOTPData($request);
        if($data == 'otp'){
            return response()->json(['status' => false, 'message' => Config::get('constants.SettingOTPError') ], 200);
        }elseif($data == 'Fail'){
            return response()->json(['status' => false, 'message' => Config::get('constants.CommonError') ], 200);
        }else{
            return response()->json(['status' => true, 'message' => Config::get('constants.SettingAuthSuccess') ], 200);
        }
    }

    //Receipt
    public function receipt(Request $request){
        $page_title     = "Receipt Settings";
        $setting        = $this->getSetting();
        $countryCode    = $this->getCountryCode();
        $parentData     = $this->getParentData();
        $custome_text1  = Config::get('constants.custome_text1');
        $custome_text2  = Config::get('constants.custome_text2');
        return view('apps.setting.receipt', compact('page_title', 'setting', 'countryCode', 'parentData','custome_text1', 'custome_text2'));
    }

    //Update Receipt
    public function updateReceipt(Request $request){
        $data = $this->updateReceiptData($request);
        if($data == 'Fail'){
            if($request->sendMail == 1){
                $msg = Config::get('constants.ReceiptEmailError');
            }else{
                $msg = Config::get('constants.CommonError');
            }            
            toastr()->error($msg);
            return redirect('app/setting/receipt');
        }else{
            if($request->sendMail == 1){
                $msg = Config::get('constants.ReceiptEmailSuccess');
                toastr()->success($msg, 'Receipt Setting');
                return redirect('app/setting/receipt');
            }else{
                $msg = Config::get('constants.ReceiptUpdateSuccess');
                toastr()->success($msg, 'Receipt Setting');
                return redirect('home'); 
            }      
        }        
    }
}
