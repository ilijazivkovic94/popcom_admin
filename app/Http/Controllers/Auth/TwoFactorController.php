<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use AWS;

class TwoFactorController extends Controller
{

    public function index(){
        return view('auth.twoFactor');
    }

    public function store(Request $request){
        // dd($request);
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = Auth()->user();

        if( ($request->input('two_factor_code') != $user->two_factor_code)){
            return redirect()->back()->withErrors(['two_factor_code' => 'A one-time password you entered does not match.']);
        }else{
            $user->resetTwoFactorCode();
            return redirect()->route('home');
        }        
    }

    public function resend(){
        $user = Auth()->user();
        $user->generateTwoFactorCode();        
        
        // send sms
        $sms            = AWS::createClient('sns');
        $phone_number   = '+'.Auth::user()->accountSetting->country_code.Auth::user()->user_phone_no;
        $sendsms = $sms->publish([
            'Message'           => 'Your POPCOM OTP is: '.Auth::user()->two_factor_code.' If you did not request this OTP, you can ignore this message.',
            'PhoneNumber'       => $phone_number,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SMSType'  => [
                    'DataType'    => 'String',
                    'StringValue' => 'Transactional',
                ],
                'AWS.SNS.SMS.SenderID' => [
                    'DataType'      => 'String',
                    'StringValue'   => 'POPCOM'
                ]
            ],
        ]);

        return redirect()->back()->withMessage('A one-time password has been sent again to your phone number.');
    }
}

?>