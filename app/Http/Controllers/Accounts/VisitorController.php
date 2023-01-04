<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;

use App\Exports\VisitorsExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Traits\CommonTrait;
use App\Traits\VisitorTrait;
//use App\Traits\DashboardDataTrait;
use App\Traits\SettingTrait;
use App\Helpers\CommonHelper;

class VisitorController extends Controller
{
    use CommonTrait,SettingTrait,VisitorTrait;

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title = "All Visitors";
        $getMachine = $this->getMachines();
        $accType = Auth::user()->accountDetails->account_type;

            $subAccounts = CommonHelper::SubAccountDetails();
        return view('apps.visitors.index', compact('page_title', 'getMachine','accType','subAccounts'));
    }

    public function analytics(Request $request){
        $page_title = "Visitors analytics";
        $getMachine = $this->getMachines();
        $accType = Auth::user()->accountDetails->account_type;

        $subAccounts = CommonHelper::SubAccountDetails();
        if($request->ajax()){
            $getData = $this->getData($request->kiosk_id,$request->accountId);
            //print_r($getData);
            $view = view('apps.visitors.ajaxcard', compact('getData'))->render();

            return response()->json(['html'=>$view]);
        }
        $getData = $this->getData();
        $setting = $this->getSetting();
       // $listData = $this->getProductSaleChart('today',$setting,'list');
        
        return view('apps.visitors.analytic', compact('page_title', 'getMachine','getData','accType','subAccounts'));
    }


    public function getAnalyticChartData(Request $request){
        if($request->ajax()){
            $setting = $this->getSetting();
            if(!empty($request->kiosk_id)){
                $kiosk_id = $request->kiosk_id;
            }else{
                $kiosk_id = null;
            }

            if(!empty($request->startdt) && !empty($request->enddt)){
                 $genderChart = $this->genderChart($request->time,$setting,$request->startdt,$request->enddt,$kiosk_id,$request->accountId);
                 $ageData = $this->agegroupChart($request->time,$setting,$request->startdt,$request->enddt,$kiosk_id,$request->accountId);
                 $emotionData = $this->emotionChart($request->time,$setting,$request->startdt,$request->enddt,$kiosk_id,$request->accountId);
            }else{
                 $genderChart = $this->genderChart($request->time,$setting,null,null,$kiosk_id,$request->accountId);
                 $ageData = $this->agegroupChart($request->time,$setting,null,null,$kiosk_id,$request->accountId);
                 $emotionData = $this->emotionChart($request->time,$setting,null,null,$kiosk_id,$request->accountId);
            }
            
            return response()->json(['genderChart'=>$genderChart,'ageData'=> $ageData,'emotionData' => $emotionData]);
        }
        
    }

    private function getData($kiosk_id=null,$accountId=null){
        $setting = $this->getSetting();
        $month = $this->getVisitorAnalyticsData('month',$setting,$kiosk_id,$accountId);
        $week = $this->getVisitorAnalyticsData('week',$setting,$kiosk_id,$accountId);
        $today = $this->getVisitorAnalyticsData('today',$setting,$kiosk_id,$accountId);
        return array('month'=>$month,'week'=>$week,'today'=>$today);
    }

    public function list(Request $request){
        return $this->getVisitorList($request);        
    }

    public function export(Request $request){
        //echo "TGG";
        $fileName = 'visitor_'.Config::get('constants.CURRENTEPOCH').'.xlsx';
        //print_r($request); die;
        return Excel::download(new VisitorsExport($request), $fileName);
    }


}