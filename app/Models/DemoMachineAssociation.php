<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class DemoMachineAssociation extends Model
{
    use HasFactory;
    protected $table    = 'demo_machine_associations';
    public $primaryKey  = 'demo_machine_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'machine_udid',
        'machine_id',
        'kiosk_id',
        'active_yn',
    ];

    public function kiosk(){
        return $this->hasOne(Kiosk::class,'kiosk_id','kiosk_id');
    }

    public static function getAllAccount($machine_id){

        $account = DemoMachineAssociation::with(array('kiosk' => function($query) {
            $query->select(DB::raw('kiosk_id,kiosk_identifier,kiosk_password,(SELECT account_org_name FROM account_setting where account_id=kiosks.account_id ) as org_name,(SELECT account_logo FROM account_setting where account_id=kiosks.account_id ) as org_logo'));
        }))->where('machine_udid', $machine_id)->get();
        
        return $account;
    }

    public static function setActiveAccount($data){
        $account = DemoMachineAssociation::where('machine_udid', $data['machine_uuid'])
        ->whereHas('kiosk', function($q) use($data) {
            $q->where(['kiosk_identifier' => $data['identifier'], 'kiosk_password' => $data['password']]);
        })->get();

        if($account->count() > 0){
            $accounts = $account->first();
            if($accounts->active_yn == 'Y'){
                DemoMachineAssociation::where('machine_id', $accounts->machine_id)->where('kiosk_id','!=',$accounts->kiosk_id)->update(['active_yn' => 'N']);
                return true;
            }else{
               $updatMachine = DemoMachineAssociation::where('machine_id', $accounts->machine_id)->update(['active_yn' => 'Y']);

               DemoMachineAssociation::where('machine_id', $accounts->machine_id)->where('kiosk_id', '!=', $accounts->kiosk_id)->update(['active_yn' => 'N']);

                if($updatMachine > 0){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }
}
