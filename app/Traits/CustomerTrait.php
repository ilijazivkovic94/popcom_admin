<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Auth;

use App\Models\Account;
use App\Models\JourneyStep;
use App\Models\Kiosk;
use App\Models\KioskProduct;
use App\Models\Customer;
use App\Models\Orders;

trait CustomerTrait {

    public function getAllCustomer(Request $request){
        if ($request->ajax()) {
            
            $accountID      = Auth::user()->account_id;
            $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
            if($accountType == 'ent'){
                $subAccountID = Account::where('account_id_parent', $accountID)->pluck('account_id')->all();
                $kioskID = Kiosk::whereIn('account_id', $subAccountID)->pluck('kiosk_id')->all();
            }else{
                $kioskID = Kiosk::where('account_id', $accountID)->pluck('kiosk_id')->all();
            }
           
            if($request->kiosk_id != ''){
                unset($kioskID);
                $kioskID[] = $request->kiosk_id; 
            }
            
            $data = Orders::select('customers.*', DB::raw('sum(orders.order_total) as total_order'), 'journeys.journey_id' )
            ->selectRaw('MAX(orders.order_id) as max_order')
            ->join('journeys', 'journeys.journey_id', '=', 'orders.journey_id')
            ->join('customers', 'customers.customer_id', '=', 'journeys.customer_id')
            ->whereIn('journeys.kiosk_id', $kioskID)
            ->whereNotNull('customers.customer_email')
            ->groupBy('customers.customer_id')
            ->orderBy('max_order', 'DESC');

            if($request->sub_account != ''){
                $data = $data->where('customers.account_id', $request->sub_account);
            }
            $data = $data->get();
            
            // DB::raw('sum(orders.order_total) as total_order')
      
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('customer_age_group', function ($row) {
                    return $row->customer_age_group != '' ? $row->customer_age_group : 'Unknown';
                })
                ->editColumn('total_order', function ($row) {
                    return '$'.($row->total_order != '' ? number_format($row->total_order, 2) : 0);
                })
                ->editColumn('customer_gender', function ($row) {
                    if($row->customer_gender != ''){
                        $gender = $row->customer_gender == 'M' ? 'Male' : 'Female';
                    }else{
                        $gender = 'unknown';
                    }
                    
                    return "<div class='symbol symbol-35 mr-2'><img src='".asset('public/assets/svg/' . strtolower($gender).'.svg')."'>".$gender."</div>";
                })
                ->addColumn('journey_emotion_json', function ($row) {
                    $img = "<img src='".asset('public/assets/svg/neutral.svg')."'> Neutral";

                    $jourStep = JourneyStep::where('journey__id', $row->journey_id)->first();
                    if ($jourStep) {
                        if($jourStep->journey_emotion_json != ''){
                            $emotion_array = array_filter(json_decode($jourStep->journey_emotion_json, true));
                            if (count($emotion_array) != 0) {
                                $emotion = array_keys($emotion_array, max($emotion_array));
                                $img = "<img src='". asset('public/assets/svg/' . implode("", $emotion) . '.svg') . "'>" . implode("", $emotion);
                            } 
                        }
                    }

                    return '<div class="symbol symbol-35 mr-2">'.$img.'</div>';
                })
                ->addColumn('action', function($row){
                    $id     = encrypt($row->customer_email);
                    $btn    = "<a href='sales?email=$id' class='btn btn-sm btn-primary btn-text-primary btn-icon' title='Edit'><i class='fas fa-shopping-bag fsize13'></i></a>";
                    return $btn;
                })
                ->rawColumns(['action', 'journey_emotion_json', 'total_order', 'customer_gender'])
                ->make(true);
        }
    } 

    public function getMachine(){
        $accountID      = Auth::user()->account_id;
        $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
        if($accountType == 'ent'){
            $subAccountID = Account::where('account_id_parent', $accountID)->pluck('account_id')->all();
            return Kiosk::whereIn('account_id', $subAccountID)->get();
        }else{
            return Kiosk::where('account_id', $accountID)->get();
        }
    }

    public function getNullEmailCustomersCount() {
        $accountID      = Auth::user()->account_id;
        $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
        if($accountType == 'ent'){
            $subAccountID = Account::where('account_id_parent', $accountID)->pluck('account_id')->all();
            $kioskID = Kiosk::whereIn('account_id', $subAccountID)->pluck('kiosk_id')->all();
        }else{
            $kioskID = Kiosk::where('account_id', $accountID)->pluck('kiosk_id')->all();
        }
        
        $details['uniqueCount'] = Orders::select('customers.*')
        ->join('journeys', 'journeys.journey_id', '=', 'orders.journey_id')
        ->join('customers', 'customers.customer_id', '=', 'journeys.customer_id')
        ->whereIn('journeys.kiosk_id', $kioskID)
        ->whereNotNull('customers.customer_email')
        ->groupBy('customers.customer_id')->get()->count();

        $details['remainingCount'] = Customer::select('customers.customer_id')
        ->join('journeys', 'journeys.journey_id', '=', 'customers.customer_id')
        ->join('carts', 'carts.journey_id', '=', 'journeys.journey_id')
        ->whereIn('journeys.kiosk_id', $kioskID)
        ->whereNull('customers.customer_email')
        ->groupBy('journeys.customer_id')->get()->count();

        return $details;
    }
}