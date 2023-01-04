<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;
use Auth;

use App\Models\Kiosk;
use App\Models\Orders;
use App\Models\JourneyStep;
use App\Models\Journey;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\Account;
use App\Models\User;

class VisitorsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    function __construct($request) {

        $this->request = $request;

    }


    private function timeCondition($time,$accountSetting){
        switch ($time) {
            case 'today':
                $timeCondition = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.    created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')=DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d')";
            break;
            case 'week':
                $timeCondition = "YEARWEEK(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.   created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d'), 1) = YEARWEEK(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'), 1)";
            break;
            case 'month':
                $timeCondition = "MONTH(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.  created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = MONTH(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d')) AND YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'))";
            break;
            case 'year':
                $timeCondition = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.   created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'))";
            break;
            case 'lastyear':
                $timeCondition = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.   created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_SUB(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'), INTERVAL 1 YEAR))";
            break;
        }
        return $timeCondition;
    }

    private function getAccounts(){
        if(Auth::user()->accountDetails->account_type == 'ent'){
            $act_id = Auth::user()->accountDetails->account_id;
            $account_ids = Account::select('account_id')
                                   ->where('account_id_parent',$act_id)
                                   ->get()
                                   ->toArray();
            $account_id = array_column($account_ids, 'account_id');
            array_push($account_id,$act_id);            
        }else{
             $account_id = [Auth::user()->accountDetails->account_id];
        }
        return $account_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        // return Product::all();
        $accountID = Auth::user()->account_id;
        $visitorSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC',(SELECT kiosk_timezone from kiosks where kiosk_id = journeys.kiosk_id)), '%m/%d/%Y %h:%i %p') as created_at,kiosks.kiosk_identifier,IF(customers.customer_email IS NULL,'Unknown',customers.customer_email) as customer_email,(case when (customers.customer_gender IS NULL or customers.customer_gender = '' or customers.customer_gender = 'O') THEN 'U' else customers.customer_gender end ) as customer_gender,journey_steps.journey_emotion_json,IF(customers.customer_age_group IS NULL,'Unknown',customers.customer_age_group) as customer_age_group,orders.order_transaction_ref,orders.order_total,(select account_name from accounts where account_id = kiosks.account_id) as account_name,kiosks.kiosk_timezone,
                (SELECT timezone_abbr from timezone where timezone=kiosks.kiosk_timezone) as timezone_abbr,orders.dispensed_yn";
        $visitorData = JourneyStep::select(DB::raw($visitorSelect))
                            ->join(with(new Journey())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
                            ->join(with(new Customer())->getTable(),'customers.customer_id','=','journeys.customer_id')
                            ->leftjoin(with(new Orders())->getTable(),'orders.journey_id','=','journeys.journey_id')
                            ->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
                            ->groupBy('journeys.journey_id');

        if(!empty($this->request->kiosk_id) && $this->request->kiosk_id != 'all'){
                $visitorData = $visitorData->where('kiosks.kiosk_id',$this->request->kiosk_id);
        }elseif(!empty($request->accountId)){
                $visitorData = $visitorData->where('kiosks.account_id',$request->accountId);
        }else{
            $account_id = $this->getAccounts();
            $visitorData = $visitorData->whereIn('kiosks.account_id',$account_id);
        }
        if(!empty($this->request->timeperiod)){

            $accountID = Auth::user()->account_id;            
            $setting = User::with(['accountDetails', 'accountSetting'])->where('account_id', $accountID)->first();
            $timeCondition = $this->timeCondition($this->request->timeperiod,$setting);

            //dd($timeCondition);
            $visitorData = $visitorData->whereRaw($timeCondition);
        }


            if(!empty($this->request->daterange)){
                $datpicker = explode(' to ', $this->request->daterange);
                $start = $datpicker[0];
                $end = $datpicker[0];
                $accountID = Auth::user()->account_id;            
                $setting = User::with(['accountDetails', 'accountSetting'])->where('account_id', $accountID)->first();
             
                $datecondition = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.    created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$setting->accountSetting->account_timezone."'), '%Y-%m-%d') 
                    BETWEEN '" . $start . "' AND '" . $end . "' ";
                $visitorData = $visitorData->whereRaw($datecondition);    
            }
        $visitorData = $visitorData->get();
        // return Customer::select('customers.*', 'journey_steps.journey_emotion_json', DB::raw('sum(orders.order_total) as total_order'))
        // ->leftJoin('journeys', 'journeys.customer_id', '=', 'customers.customer_id')
        // ->leftJoin('journey_steps', 'journey_steps.journey__id', '=', 'journeys.journey_id')
        // ->join('orders', 'orders.journey_id', '=', 'journeys.journey_id')
        // ->where('customers.account_id', $accountID)
        // ->whereNotNull('customers.customer_email')
        // ->groupBy('customers.customer_email')
        // ->get();   
        return $visitorData;
    }

    public function headings(): array {
        $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
        if($accountType == 'ent'){
            return [
                'Date',
                'Sub-Account Name',
                'Machine Name',
                'Email',
                'Gender',
                'Emotion',
                'Age Group',
                'Purchased?',
                'Checkout Amount',
            ];
        }else{
            return [
                'Date',
                'Machine Name',
                'Email',
                'Gender',
                'Emotion',
                'Age Group',
                'Purchased?',
                'Checkout Amount',
            ];
        }
    }

    public function prepareRows($rows): array
    {
        return array_map(function ($visitor) {
            switch($visitor->customer_gender){
                case 'M': $visitor->customer_gender = 'Male'; break;
                case 'F': $visitor->customer_gender = 'Female'; break;
                case 'U': $visitor->customer_gender = 'Unknown'; break;
            }
            
            $visitor->dispensed_yn = $visitor->dispensed_yn == 'Y' ? 'Yes' : 'No';

            if (!empty($visitor->journey_emotion_json)) {
                $dbemotion = json_decode($visitor->journey_emotion_json, true);
                if(!empty($dbemotion)){
                    $emotion_array = array_filter($dbemotion);
                    if (count($emotion_array) != 0) {
                        $emotion = array_keys($emotion_array, max($emotion_array));
                        $visitor->journey_emotion_json = implode("", $emotion);
                    } else {
                        $visitor->journey_emotion_json = "Neutral";
                    }
                }else{
                    $visitor->journey_emotion_json = "Unknown";
                }
                
            } else {
                $visitor->journey_emotion_json = "Unknown";
            }

            $visitor->order_total = (!empty($visitor->order_total)) ? $visitor->order_total : '0.0';


            return $visitor;
        }, $rows);
    }

    public function map($visitor): array {
        $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
         if($accountType == 'ent'){
             return [
                $visitor->created_at,
                $visitor->account_name,
                $visitor->kiosk_identifier,
                $visitor->customer_email,
                $visitor->customer_gender,
                $visitor->journey_emotion_json,
                $visitor->customer_age_group,
                $visitor->dispensed_yn,
                '$'.$visitor->order_total,          
            ];
         }else{
             return [
                $visitor->created_at,
                $visitor->kiosk_identifier,
                $visitor->customer_email,
                $visitor->customer_gender,
                $visitor->journey_emotion_json,
                $visitor->customer_age_group,
                $visitor->dispensed_yn,
                '$'.$visitor->order_total,          
            ];
         }
       
    }
}
