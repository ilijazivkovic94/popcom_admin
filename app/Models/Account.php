<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table    = 'accounts';
    public $primaryKey  = 'account_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'account_name',
        'created_at',
        'modified_at',
        'account_last_login_dt',
        'account_type',
        'account_id_parent',
        'account_bypass_subs',
        'account_otp',
        'account_temp_password',
        'account_status',
        'demo_active',
    ];

    public function Account_Setting(){
        return $this->hasOne(AccountSetting::class, 'account_id', 'account_id');
    }

    public function Account_SubSetting(){
        return $this->hasOne(SubAccountSettings::class, 'account_id', 'account_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'account_id', 'account_id');
    }

    // public function kiosks(){
    //     return $this->hasMany(Kiosk::class,'account_id','account_id');
    // }

    // public function user(){
    //     return $this->hasMany(User::class,'account_id','account_id');
    // }
}
