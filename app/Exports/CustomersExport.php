<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;
use Auth;

use App\Models\Account;
use App\Models\JourneyStep;
use App\Models\Kiosk;
use App\Models\KioskProduct;
use App\Models\Customer;
use App\Models\Orders;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $accountID      = Auth::user()->account_id;
        $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
        if($accountType == 'ent'){
            $subAccountID = Account::where('account_id_parent', $accountID)->pluck('account_id')->all();
            $kioskID = Kiosk::whereIn('account_id', $subAccountID)->pluck('kiosk_id')->all();
        }else{
            $kioskID = Kiosk::where('account_id', $accountID)->pluck('kiosk_id')->all();
        }
        
        return Orders::select('customers.*', DB::raw('sum(orders.order_total) as total_order'), 'journeys.journey_id')
            ->join('journeys', 'journeys.journey_id', '=', 'orders.journey_id')
            ->join('customers', 'customers.customer_id', '=', 'journeys.customer_id')
            ->whereIn('journeys.kiosk_id', $kioskID)
            ->whereNotNull('customers.customer_email')
            ->groupBy('customers.customer_id')->get();        
        
        // return Customer::select('customers.*', 'journey_steps.journey_emotion_json', DB::raw('sum(orders.order_total) as total_order'))
        //     ->Join('journeys', 'journeys.customer_id', '=', 'customers.customer_id')
        //     ->Join('journey_steps', 'journey_steps.journey__id', '=', 'journeys.journey_id')
        //     ->Join('orders', 'orders.journey_id', '=', 'journeys.journey_id')
        //     ->where('customers.account_id', $accountID)
        //     ->whereNotNull('customers.customer_email')
        //     ->groupBy('customers.customer_email')->get();   
    }

    public function headings(): array {
        return [
            'Customer ID',
            'Email',
            'Gender',
            'Age Group',
            'Emotion',
            'Lifetime Orders Total',
        ];
    }

    public function map($customer): array {
        $jourStep = JourneyStep::where('journey__id', $customer->journey_id)->first();
        $emotion = "Neutral";
        if ($jourStep) {
            if($jourStep->journey_emotion_json != ''){
                $emotion_array = array_filter(json_decode($jourStep->journey_emotion_json, true));
                if (count($emotion_array) != 0) {
                    $emotion = array_keys($emotion_array, max($emotion_array));
                    $emotion = implode("", $emotion);
                } 
            }
        }

        return [
            $customer->customer_id,
            $customer->customer_email,
            ($customer->customer_gender == 'M' ? 'Male' : 'Female'),
            ($customer->customer_age_group != '' ? $customer->customer_age_group : 'Unknown'),
            $emotion,
            '$'.$customer->total_order,          
        ];
    }
}
