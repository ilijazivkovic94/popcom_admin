<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Auth;

class TwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next){
        if(Auth::user()->user_admin_yn == 'N' && Auth::user()->user_2fa_yn == 'Y'){
            if(Auth::user()->verify_yn == 'Y'){
                return $next($request);
            }else{
                return redirect()->route('verify.index')->withMessage('A one-time password has been sent to your phone number.');
            }
        }else{
            return $next($request);
        }        
    }
}
