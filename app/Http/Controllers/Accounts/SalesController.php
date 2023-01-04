<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;

use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Traits\CommonTrait;
use App\Traits\SalesTrait;
use App\Traits\DashboardDataTrait;
use App\Traits\SettingTrait;
use App\Helpers\CommonHelper;

class SalesController extends Controller
{
    use CommonTrait,SalesTrait,SettingTrait,DashboardDataTrait;

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title = "All Sales";
        $getMachine = $this->getMachines();
        $accType = Auth::user()->accountDetails->account_type;
        $email = !empty($request->email)?$request->email:'';
        $subAccounts = CommonHelper::SubAccountDetails();
        return view('apps.sales.index', compact('page_title', 'getMachine','accType','subAccounts','email'));
    }

    public function analytics(Request $request){
        $page_title = "Sales analytics";
        $getMachine = $this->getMachines();
         $accType = Auth::user()->accountDetails->account_type;

        $subAccounts = CommonHelper::SubAccountDetails();
        if($request->ajax()){
            $getData = $this->getData($request->kiosk_id,$request->accountId);
            //print_r($getData);
            $view = view('apps.sales.ajaxcard', compact('getData'))->render();

            return response()->json(['html'=>$view]);
        }
        $getData = $this->getData();
        $setting = $this->getSetting();
        $listData = $this->getProductSaleChart('today',$setting,'list',null);
        
        return view('apps.sales.analytic', compact('page_title', 'getMachine','getData','listData','accType','subAccounts'));
    }

    public function getSaleChartData(Request $request){
        if($request->ajax()){
            $setting = $this->getSetting();
            if(!empty($request->kiosk_id)){
                $kiosk_id = $request->kiosk_id;
            }else{
                $kiosk_id = null;
            }
            if(!empty($request->startdt) && !empty($request->enddt)){
                 $charData = $this->getSaleChart($request->time,$setting,$kiosk_id,$request->startdt,$request->enddt);
            }else{
                 $charData = $this->getSaleChart($request->time,$setting,$kiosk_id);
            }
           
            return response()->json([$charData]);
        }
    }

    public function getProductSaleData(Request $request){
        if($request->ajax()){
            $setting = $this->getSetting();
            if(!empty($request->kiosk_id)){
                $kiosk_id = $request->kiosk_id;
            }else{
                $kiosk_id = null;
            }
            if(!empty($request->startdt) && !empty($request->enddt)){
                 $charData = $this->getProductSaleChart($request->time,$setting,$request->type,$request->accountId,$request->startdt,$request->enddt);
            }else{
                 $charData = $this->getProductSaleChart($request->time,$setting,$request->type,$request->accountId);
            }

            return response()->json([$charData]);
        }
        
    }

    public function getCustomerSalesData(Request $request){
        if($request->ajax()){
            $setting = $this->getSetting();
            if(!empty($request->kiosk_id)){
                $kiosk_id = $request->kiosk_id;
            }else{
                $kiosk_id = null;
            }
            if(!empty($request->startdt) && !empty($request->enddt)){
                 $charData = $this->getCustomerSalesChart($request->time,$setting,$request->type,$request->accountId,$request->startdt,$request->enddt);
            }else{
                 $charData = $this->getCustomerSalesChart($request->time,$setting,$request->type,$request->accountId);
            }

            return response()->json([$charData]);
        }
        
    }

    public function getData($kiosk_id=null,$accountId=null){
        $setting = $this->getSetting();
        $month['salesData'] = $this->getSalesData('month',$setting,$accountId,$kiosk_id);
        $month['visitors'] = $this->visitorData('month',$setting,$accountId,$kiosk_id);
        $month['conversion'] = $this->customerData('month',$setting,$accountId,$kiosk_id);

        $week['salesData'] = $this->getSalesData('week',$setting,$accountId,$kiosk_id);
        $week['visitors'] = $this->visitorData('week',$setting,$accountId,$kiosk_id);
        $week['conversion'] = $this->customerData('week',$setting,$accountId,$kiosk_id);

        $today['salesData'] = $this->getSalesData('today',$setting,$accountId,$kiosk_id);
        $today['visitors'] = $this->visitorData('today',$setting,$accountId,$kiosk_id);
        $today['conversion'] = $this->customerData('today',$setting,$accountId,$kiosk_id);


        return array('month'=>$month,'week'=>$week,'today'=>$today);
    }

    //List
    public function list(Request $request){
        return $this->getSalesList($request);        
    }

    //Export
    public function export(Request $request){
        $fileName = 'sales_'.Config::get('constants.CURRENTEPOCH').'.xlsx';
        return Excel::download(new SalesExport($request), $fileName);
    }

    public function getSalesTotal(Request $request){
         return $this->getSalesList($request);        
    }
}
