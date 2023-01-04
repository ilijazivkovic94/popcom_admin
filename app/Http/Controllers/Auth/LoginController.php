<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use Auth;
use AWS;

use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = User::where('id', Auth::id())->first();
            if($user->user_active_yn == "Y"){
                // update data
                return $this->sendLoginResponse($request);
            }else{
                $this->guard()->logout();

                toastr()->error('Your account has been disabled by admin'); 
                return redirect('login');
            }
        }else{
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }   
    }

    protected function sendLoginResponse(Request $request){
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        // if ($response = $this->authenticated($request, $this->guard()->user())) {
        //     return $response;
        // }

        // return $request->wantsJson()
        //             ? new JsonResponse([], 204)
        //             : redirect()->intended($this->redirectPath());

        $data = $this->authenticated($request, $this->guard()->user()) ?: redirect()->route('verify.index')
            ->withMessage('A one-time password has been sent to your phone number.');

        if($data['status'] == "home"){
            return redirect()->route('home');
        }else{
            return redirect()->route('verify.index')->withMessage('A one-time password has been sent to your phone number.');
        }
    }

    protected function authenticated(Request $request, $user){
        if(Auth::user()->user_admin_yn == 'N' && Auth::user()->user_2fa_yn == 'Y'){

            $user->generateTwoFactorCode();
            try{

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

                $data['status'] = "success";
                return $data;
            }catch(\Exception $e){
                $data['status'] = "fail";
                return $data;
            }
        }else{
            $data['status'] = "home";
            return $data;
        }
    }

    //Password Encoding
    public function passwordEncode(){
        $userData   = User::where('user_admin_yn', 'N')->limit(10)->get()->toArray();
        $userData   = array_column($userData, 'password');
        $userData   = \implode(' ', $userData);
        return view('auth.encode', \compact('userData') );
    }

    public function passwordUpdate(Request $request){
        // dd($request->all());
        $password   = explode(", ", $request->new_password );
        $userData   = User::where('user_admin_yn', 'N')->limit(10)->get()->toArray();
        foreach ($userData as $key => $value) {
            User::where('id', $value['id'])->update([ 'password' => Hash::make($password[$key]) ]);
        }

        toastr()->error('Password Changed.'); 
        return redirect('password-encode');   
    }

}
