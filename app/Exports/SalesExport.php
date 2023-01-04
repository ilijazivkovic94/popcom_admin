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

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    function __construct($request) {

        $this->request = $request;

    }


    public function timeCondition($time,$accountSetting){
        switch ($time) {
            case 'today':
                $timeCondition = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')=DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d')";
            break;
            case 'week':
                $timeCondition = "YEARWEEK(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d'), 1) = YEARWEEK(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'), 1)";
            break;
            case 'month':
                $timeCondition = "MONTH(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = MONTH(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d')) AND YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'))";
            break;
            case 'year':
                $timeCondition = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'))";
            break;
            case 'lastyear':
                $timeCondition = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_SUB(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'), INTERVAL 1 YEAR))";
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
        $orderSelect = "orders.order_id,orders.order_total,orders.order_subtotal,orders.order_tax,orders.order_discount_value,carts.product_qty,DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC',(SELECT kiosk_timezone from kiosks where kiosk_id = journeys.kiosk_id)), '%m/%d/%Y %h:%i %p') as created_on,kiosks.kiosk_identifier,IF(customers.customer_email IS NULL,'Unknown',customers.customer_email) as customer_email,IF(orders.promo_id != 0,(SELECT promo_code from promos WHERE promo_id = orders.promo_id),'') as promo_code, (select product_identifier from product_variants where product_variant_id = carts.product_variant_id) as product_identifier, (select variant_name from product_variants where product_variant_id = carts.product_variant_id) as variant_name, (select variant_sku from product_variants where product_variant_id = carts.product_variant_id) as variant_sku, (select product_name from product_variants join products on products.product_id =  product_variants.product_id where product_variants.product_variant_id = carts.product_variant_id) as product_name,(SELECT timezone_abbr from timezone where timezone=kiosks.kiosk_timezone) as timezone_abbr";
        $orderData = Orders::select(DB::raw($orderSelect))
                            ->join(with(new Cart())->getTable(),'carts.cart_id','=','orders.cart_id')
                            ->join(with(new Journey())->getTable(),'journeys.journey_id','=','orders.journey_id')
                            ->join(with(new JourneyStep())->getTable(),'journey_steps.journey__id','=','journeys.journey_id')
                            ->join(with(new Customer())->getTable(),'customers.customer_id','=','journeys.customer_id')
                            ->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
                            ->groupBy('orders.order_id');
                            
                        
            if(!empty($this->request->kiosk_id)){
                $orderData = $orderData->where('kiosks.kiosk_id',$this->request->kiosk_id);
            }elseif(!empty($request->accountId)){
                $orderData = $orderData->where('kiosks.account_id',$request->accountId);
            }else{
                $account_id = $this->getAccounts();
                $orderData = $orderData->whereIn('kiosks.account_id',$account_id);
            }
            if(!empty($this->request->timeperiod)){
                $accountID = Auth::user()->account_id;            
                $setting = User::with(['accountDetails', 'accountSetting'])->where('account_id', $accountID)->first();
                $timeCondition = $this->timeCondition($this->request->timeperiod,$setting);

                $orderData = $orderData->whereRaw($timeCondition);
            }

            if(!empty($this->request->cart_event)){
                if($request->cart_event == 'checkout'){
                    $orderData = $orderData->where('journey_steps.journey_step_name',$this->request->cart_event);
                }elseif($request->cart_event == 'abandon'){
                    $orderData = $orderData->where('journey_steps.journey_step_name',$this->request->cart_event);
                }
            }
            //dd($this->request->all());
            if(!empty($this->request->email)) {
                $orderData = $orderData->where('customers.customer_email',decrypt($this->request->email));
            }
            if(!empty($this->request->fromDate) && !empty($this->request->toDate)){
                $accountID = Auth::user()->account_id;            
                $setting = User::with(['accountDetails', 'accountSetting'])->where('account_id', $accountID)->first();
                $start = date("Y-m-d", strtotime($this->request->fromDate));
                $end = date("Y-m-d", strtotime($this->request->toDate));
                $datecondition = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$setting->accountSetting->account_timezone."'), '%Y-%m-%d') 
                    BETWEEN '" . $start . "' AND '" . $end . "' ";
                $orderData = $orderData->whereRaw($datecondition);    
            }
        $salesData = $orderData->get();
        
        return $salesData;
    }

    public function headings(): array {
        return [
            'Order Id',
            'Date',
            'Machine Name',
            'Customer Email',
            'Quantity',
            'Product Identifier',
            'Product Name',
            'Variant Type',
            'Variant Name',
            'Sub Total',
            'Tax',
            'Total',
            'Discount Total',
            'Code'
        ];
    }

    

    public function map($sales): array {
        return [
            $sales->order_id,
            $sales->created_on.'('.$sales->timezone_abbr.')',
            $sales->kiosk_identifier,
            $sales->customer_email,
            $sales->product_qty,
            $sales->product_identifier,
            $sales->product_name,
            $sales->variant_sku,
            $sales->variant_name,
            '$'.$sales->order_subtotal,
            '$'.$sales->order_tax,
            '$'.$sales->order_total,
            '$'.$sales->order_discount_value,
            $sales->promo_code          
        ];
    }
}
