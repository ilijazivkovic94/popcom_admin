<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use AWS;
use Auth;

use App\Models\User;
use App\Models\Account;
use App\Models\AccountSetting;
use App\Models\SubAccountSettings;

use App\Mail\AccountTestMail;
use Illuminate\Support\Facades\Mail;

trait SettingTrait {
   
    public function getSetting(){

        try {
            $accountID = Auth::user()->account_id;            
            return User::with(['accountDetails', 'accountSetting', 'accountSubSetting'])->where('account_id', $accountID)->first();
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function getParentData(){

        try {
            $accountID      = Auth::user()->account_id;       
            $accountType    = Auth::user()->accountDetails()->first();
            if($accountType->account_type == 'sub'){
                return User::with(['accountDetails', 'accountSetting'])->where('account_id', $accountType->account_id_parent)->first();
            }
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function updateSetting(Request $request){
        // dd($request->all());
        try {
            $accountID      = Auth::user()->account_id;
            $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();

            User::where('account_id', $accountID)->update(['user_fname' => $request->account_poc]);

            if($accountType == 'ent'){
                $temp = [
                    'account_org_name'          => $request->account_org_name,
                    'account_survey_url'        => $request->account_survey_url,
                    'account_poc'               => $request->account_poc,
                    'account_timezone'          => $request->account_timezone,
                    'main_setting_yn'           => 'Y'
                ];
            }else{
                $temp = [
                    'account_org_name'          => $request->account_org_name,
                    'account_survey_url'        => $request->account_survey_url,
                    'account_poc'               => $request->account_poc,
                    'account_timezone'          => $request->account_timezone,
                    'account_contact_email'     => $request->account_contact_email,
                    'account_contact_phone'     => $request->account_contact_phone,
                    'main_setting_yn'           => 'Y'
                ];
            }

            if($request->hasFile('account_logo')) {
                $files = $request->file('account_logo');                
                $name = time().$files->getClientOriginalName();
                $filePath = $accountID.'/logo/'.$name;
                Storage::disk('s3')->put($filePath, file_get_contents($files), 'public');                       
                $temp['account_logo'] = Storage::disk('s3')->url($filePath);
            }

            AccountSetting::where('account_id', $accountID)->update($temp);

            if($accountType == 'ent'){
                SubAccountSettings::where('account_id', $accountID)->update([
                    'products_price'    => $request->products_price,
                    'products_name'     => $request->products_name,
                    'products_create'   => $request->products_create,
                    'ads_status'        => $request->ads_status,
                    'ads_gender'        => $request->ads_gender,
                    'ads_age'           => $request->ads_age,
                    'ads_create'        => $request->ads_create,
                ]);
            }

            return true;
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function sendOTPData(Request $request){
        try {
            $accountID = Auth::user()->account_id;    
            $checkUser = User::select('password')->where([ 'account_id' => $accountID ])->first();
            if($checkUser){
                if(password_verify($request->current_password, Auth::user()->password)){
                    $otp = mt_rand(100000, 999999);
                    
                    User::where('account_id', $accountID)->update(['user_phone_no' => $request->user_phone_no]);
                    Account::where('account_id', $accountID)->update(['account_otp' => $otp]);
                    AccountSetting::where('account_id', $accountID)->update(['country_code' => $request->country_code]);

                    $phoneNub = "+".$request->country_code.''.$request->user_phone_no;
                    $message2 = "Your OTP for Two Factor Authentication activate: ".$otp." for POPCOM account";

                    $sms = AWS::createClient('sns');
                    $sms->publish([
                        'Message' => $message2,
                        'PhoneNumber' => $phoneNub,	
                        'MessageAttributes' => [
                            'AWS.SNS.SMS.SMSType'  => [
                                'DataType'    => 'String',
                                'StringValue' => 'Transactional',
                            ]
                        ],
                    ]);

                    return 1;
                }else{
                    return 'password';
                }
            }else{
                return 'password';
            }
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function checkOTPData(Request $request){

        try {
            $accountID = Auth::user()->account_id;    
            $checkUser = Account::where([ 'account_id' => $accountID, 'account_otp' => $request->user_otp ])->first();
            if($checkUser){
                User::where('account_id', $accountID)->update(['user_2fa_yn' => 'Y']);
                return 1;
            }else{
                return 'otp';
            }
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function updateReceiptData(Request $request){
        // dd($request->all());
        try {
            $accountID      = Auth::user()->account_id;
            $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();

            if($accountType == 'ent'){
                $temp = [
                    'receipt_custom_text_1'   => $request->receipt_custom_text_1,
                    'include_survey_url'      => $request->include_survey_url,
                    'receipt_survey_url'      => $request->receipt_survey_url,
                    'receipt_setting_yn'      => 'Y'
                ];
            }else{
                $temp = [
                    'receipt_custom_text_1'   => $request->receipt_custom_text_1,
                    'receipt_custom_text_2'   => $request->receipt_custom_text_2,
                    'receipt_sender_email'    => $request->receipt_sender_email,
                    'receipt_sender_password' => $request->receipt_sender_password,
                    'receipt_sender_host'     => $request->receipt_sender_host,
                    'receipt_sender_port'     => $request->receipt_sender_port,
                    'include_survey_url'      => $request->include_survey_url,
                    'receipt_survey_url'      => $request->receipt_survey_url,
                    'receipt_setting_yn'      => 'Y'
                ];
            }

            AccountSetting::where('account_id', $accountID)->update($temp);

            if($request->sendMail == 1){
                $mailSetup = [
                    'transport'     => 'smtp',
                    'port'          => $request->receipt_sender_port,
                    'host'          => $request->receipt_sender_host,
                    'username'      => $request->receipt_sender_email,
                    'password'      => $request->receipt_sender_password,
                    'encryption'    => 'tls',
                    'timeout'       => null,
                    'auth_mode'     => null,
                ];

                $formSetup = [
                    'address'   => $request->receipt_sender_email,
                    'name'      => '',
                ];
                
                Config::set('mail.mailers.smtp', $mailSetup);
                Config::set('mail.from', $formSetup);

                $dataArr    = [];
                $email      = Auth::user()->email;
                Mail::to($email)->send(new AccountTestMail($dataArr));
            }

            return 'Success';
        } catch (\Throwable $th) {
            // dd($th);
            return 'Fail';
        }
    }
}