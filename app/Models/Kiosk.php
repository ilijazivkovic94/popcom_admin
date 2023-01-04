<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\KioskProduct;

class Kiosk extends Model
{
    use HasFactory;
    protected $table    = 'kiosks';
    public $primaryKey  = 'kiosk_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'account_id',
        'model_id',
        'kiosk_identifier',
        'kiosk_password',
        'kiosk_status',
        'kiosk_serial_no',
        'kiosk_facial_license',
        'kiosk_street',
        'kiosk_city',
        'kiosks_state',
        'kiosk_country',
        'kiosk_zip',
        'kiosk_timezone',
        'kiosk_low_inv_threshold',
        'kiosk_tax_rate',
        'created_at',
        'modified_at',
        'pos_pin',
        'pos_checkout_msg',
        'pos_min_age',
        'pos_age_regulation',
        'pos_consumption_units',
        'pos_consumption_period_hr',
        'template_name',
        'template_description',
        'template_created_dt',
        'template_bin_count',
        'template_bin_identity',
        'template_json',
        'template_status',
        'language',
        'currency',
        'alert_email_yn'
    ];

    public function getKiosks($input) {
        return $getActiveKiosks = Kiosk::where(['kiosk_identifier' => $input['identifier'], 'kiosk_password' => $input['password'], 'kiosk_status' => 'Y'])->first();
    }

    public function getKioskModel(){
        return $this->hasOne(KioskModel::class, 'kiosk_model_id', 'model_id');
    }

    public function account(){
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }

    public static function getKioskFromIdentifier($kiosk_identifier){
        $kiosk = Kiosk::select(DB::raw('*, (SELECT email from users where account_id=kiosks.account_id) as login_email'))->where('kiosk_identifier', $kiosk_identifier)->get();
        return $kiosk;
    }

    //DataTable
    public function getKioskProductCount($id){
        return KioskProduct::where('kiosk__id', $id)->sum('quantity');
    }

    //DataTable
    public function getKioskAlert($id, $alertNo){
        $alert      = 'N'; 
        $products   = 0;
        $getData = KioskProduct::where('kiosk__id', $id)->get();
        if(isset($getData) && count($getData) > 0){
            foreach ($getData as $key => $value) {
                $products = $products + $value->quantity;
                if($value->quantity > $alertNo && $alert == 'N'){
                    $alert = 'N';
                }

                if($value->quantity < $alertNo){
                    $alert = 'Y';                    
                }
            }
        }

        return $alert;
    }


}
