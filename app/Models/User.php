<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'account_id',
        'user_admin_yn',
        'user_fname',
        'user_lname',
        'email',
        'password',
        'user_2fa_yn',
        'created_at',
        'modified_at',
        'user_active_yn',
        'email_verified_at',
        'trial_ends_at',
        'two_factor_code',
        'expires_at',
        'verify_yn'
    ];

    public $timestamps = false;
    protected $cardUpFront = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function accountDetails(){
        return $this->hasOne('App\Models\Account', 'account_id', 'account_id');
    }

    public function accountSetting(){
        return $this->hasOne('App\Models\AccountSetting', 'account_id', 'account_id');
    }

    public function accountSubSetting(){
        return $this->hasOne(SubAccountSettings::Class, 'account_id', 'account_id');
    }

    public static function userData($account_id, $selected){
        $account_id = decrypt($account_id);
        $data = User::where('account_id', $account_id)->first();
        if($data){
            return $data->$selected;
        }else{
            return '';
        }        
    }

    public static function subAccountSetting($account_id){
        return SubAccountSettings::where('account_id', $account_id)->first();
    }

    public function subscriptionDetails(){
        return $this->hasOne('App\Models\Subscription', 'user_id', 'id');
    }

    public function generateTwoFactorCode(){
        $code = rand(100000, 999999);
        $this->timestamps               = false;
        $this->two_factor_code          = $code;
        $this->expires_at               = 120;
        $this->verify_yn                = 'N';
        $this->save();
    }

    public function resetTwoFactorCode(){
        $this->timestamps               = false;
        $this->two_factor_code          = null;
        $this->expires_at               = null;
        $this->verify_yn                = 'Y';
        $this->save();
    }

    public function logoutFactorCode(){
        $this->timestamps               = false;
        $this->two_factor_code          = null;
        $this->expires_at               = null;
        $this->verify_yn                = 'N';
        $this->save();
    }
}
