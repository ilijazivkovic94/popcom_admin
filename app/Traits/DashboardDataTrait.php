<?php

namespace App\Traits;

use App\Models\AdmQueueData;
use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;
use App\Helpers\CommonHelper;
use App\Models\Kiosk;
use App\Models\KioskModel;
use App\Models\KioskProduct;
use App\Models\Orders;
use App\Models\JourneyStep;
use App\Models\Journey;
use App\Models\Customer;
use App\Models\Account;
use App\Models\Product;
use App\Models\ProductVariant;

trait DashboardDataTrait {

    private function getTimeCondition($time,$accountSetting,$column=null){
        if(!empty($column)){
            $columnName = $column;
        }else{
            $columnName = 'orders.created_at';
        }
        switch ($time) {
            case 'today':
                    $timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d') times,
                        count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) timecount";

                    $havingCond = "times IN(CURDATE(),CURDATE() - INTERVAL 1 DAY,CURDATE() - INTERVAL 2 DAY)";

                    $today = date('Y-m-d');
                    $yesterday = date('Y-m-d',strtotime("-1 days"));
                    $daybefore = date('Y-m-d',strtotime("-2 days"));
                    $compareData = array(
                        array('type'=>'current','data'=>$today,'value'=>0,'total'=>0),
                        array('type'=>'prev','data'=>$yesterday,'value'=>0,'total'=>0),
                        array('type'=>'daybefore','data'=>$daybefore,'value'=>0,'total'=>0)
                    );
                break;
            case 'yesterday':
                    $timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d') times,
                        count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) timecount";
                    $havingCond = "times IN(CURDATE() - INTERVAL 1 DAY,CURDATE() - INTERVAL 2 DAY,CURDATE() - INTERVAL 3 DAY)";

                    $today = date('Y-m-d',strtotime("-1 days"));
                    $yesterday = date('Y-m-d',strtotime("-2 days"));
                    $daybefore = date('Y-m-d',strtotime("-3 days"));
                    $compareData = array(
                        array('type'=>'current','data'=>$today,'value'=>0,'total'=>0),
                        array('type'=>'prev','data'=>$yesterday,'value'=>0,'total'=>0),
                        array('type'=>'daybefore','data'=>$daybefore,'value'=>0,'total'=>0)
                    );

                break;
            case 'week':
                    $timeSelect = "WEEKOFYEAR (DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) times,
                        count(WEEKOFYEAR (DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d'))) timecount";
                    $havingCond = "times IN(WEEKOFYEAR(NOW()),WEEKOFYEAR(NOW()) -1,WEEKOFYEAR(NOW()) -2)";

                    $ddate = date('Y-m-d');
                    $date = new DateTime($ddate);
                    $week = $date->format("W");
                    $compareData = array(
                        array('type'=>'current','data'=>$week,'value'=>0,'total'=>0),
                        array('type'=>'prev','data'=>($week-1),'value'=>0,'total'=>0),
                        array('type'=>'daybefore','data'=>($week-2),'value'=>0,'total'=>0)
                    );
                break;
            case 'month':
                 $timeSelect = "MONTH(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) times,
                    count(MONTH(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d'))) timecount,".$columnName;

                     $havingCond = "times IN(MONTH(NOW()),MONTH(NOW()) -1,MONTH(NOW()) -2) and YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE())";

                    $ddate = date('Y-m-d');
                    $date = new DateTime($ddate);
                    $month = $date->format("n");
                    $compareData = array(
                        array('type'=>'current','data'=>$month,'value'=>0,'total'=>0),
                        array('type'=>'prev','data'=>($month-1),'value'=>0,'total'=>0),
                        array('type'=>'daybefore','data'=>($month-2),'value'=>0,'total'=>0)
                    );
                break;
        }

        return array('timeSelect' => $timeSelect,'havingCond' => $havingCond,'compareData' => $compareData);
    }

    private function getAccountId(){
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


    public function getSalesData($time,$setting,$accountId=null,$kiosk_id=null){

        $getTimeCondition = $this->getTimeCondition($time,$setting);

        $account_id = $this->getAccountId();

        $saleSelect = $getTimeCondition['timeSelect'].", SUM(orders.order_total) as total";
        // DB::enableQueryLog();
        $salesCurrentRate = Kiosk::Select(DB::raw($saleSelect))
                            ->join(with(new Journey())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
                            ->join(with(new Orders())->getTable(),'journeys.journey_id','=','orders.journey_id')
                            ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
                            ->where('orders.dispensed_yn','Y')
                            ->where('journey_steps.journey_step_name','checkout')
                            ->groupBy('times')
                            ->havingRaw($getTimeCondition['havingCond'])
                            ->orderBy('times','Desc');

        if(!empty($kiosk_id)){
          $salesCurrentRate = $salesCurrentRate->where('kiosks.kiosk_id',$kiosk_id);
        }elseif (!empty($accountId)) {
          $salesCurrentRate = $salesCurrentRate->where('kiosks.account_id',$accountId);
        }else{
          $salesCurrentRate = $salesCurrentRate->whereIn('kiosks.account_id',$account_id);
        }

        $salesCurrentRate = $salesCurrentRate->get()->toArray();
        //dd(DB::getQueryLog());


        foreach ($salesCurrentRate as $key => $value) {
           $findD = array_search($value['times'], array_column($getTimeCondition['compareData'], 'data'));
           //var_dump($findD);
           if($findD !== false){
                $getTimeCondition['compareData'][$findD]['value'] = $value['timecount'];
                $getTimeCondition['compareData'][$findD]['total'] = $value['total'];
           }
        }

        //DB::enableQueryLog();
        // $visitorSelect = $getTimeCondition['timeSelect'].', count(journeys.customer_id) customers';
        // $visitorCurrentRate = Kiosk::Select(DB::raw($visitorSelect))
        //                     ->join(with(new Journey())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
        //                     ->join(with(new Orders())->getTable(),'journeys.journey_id','=','orders.journey_id')
        //                     ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
        //                     ->whereIn('journey_steps.journey_step_name',['cartadd','checkout','product'])
        //                     ->groupBy('times')
        //                     ->havingRaw($getTimeCondition['havingCond'])
        //                     ->orderBy('times','Desc');4
      $getVisitorTimeCondition = $this->getTimeCondition($time,$setting,'journeys.created_at');
      $timeSelect = $getVisitorTimeCondition['timeSelect'].", count(DISTINCT(customer_id)) as customers";
      $visitorCurrentRate = Journey::select(DB::raw($timeSelect))
                            ->groupBy('times')
                            ->havingRaw($getVisitorTimeCondition['havingCond'])
                            ->orderBy('times','Desc');
        if(!empty($kiosk_id)){
          $visitorCurrentRate = $visitorCurrentRate->where('journeys.kiosk_id',$kiosk_id);
        }elseif (!empty($accountId)) {
          $visitorCurrentRate = $visitorCurrentRate->whereIn('journeys.kiosk_id',function($q) use($accountId){
                                $q->select('kiosk_id')
                                ->from(with(new Kiosk)->getTable())
                                ->where('account_id', $accountId);
                            });
        }else{
          $visitorCurrentRate = $visitorCurrentRate->whereIn('journeys.kiosk_id',function($q) use($account_id){
                                $q->select('kiosk_id')
                                ->from(with(new Kiosk)->getTable())
                                ->whereIn('account_id', $account_id);
                            });
        }

        $visitorCurrentRate = $visitorCurrentRate->get()->toArray();

        //dd(DB::getQueryLog());
        foreach ($visitorCurrentRate as $key => $value) {
           $findD = array_search($value['times'], array_column($getTimeCondition['compareData'], 'data'));
           if($findD !== false){
                if($getTimeCondition['compareData'][$findD]['type'] == 'prev'){

                  $currentSaleRate = ($getTimeCondition['compareData'][$findD]['value'] / $value['customers']) * 100;
                  $getTimeCondition['compareData'][$findD]['value'] = round($currentSaleRate,2);
                }else{
                   $currentSaleRate = ($getTimeCondition['compareData'][$findD]['value'] / $value['timecount']) * 100;
                $getTimeCondition['compareData'][$findD]['value'] = round($currentSaleRate,2);
                }

           }
        }
        //  print_r($getTimeCondition['compareData']);
         $machineData = array(
            'machine_name'=>'',
            'machine_total' => 0,
            'machine_prevtotal' => 0,
            'machine_rate' => 0,
            'machine_prevrate' => 0,
         );
         $machineSelect = $getTimeCondition['timeSelect'].", kiosks.kiosk_identifier,kiosks.kiosk_id,SUM(orders.order_total) as total";
         switch ($time) {
             case 'today':
                  $machineHavingCur = "times IN(CURDATE())";
                  $machineHavingPrev = "times IN(CURDATE() - INTERVAL 1 DAY)";
                 break;
            case 'yesterday':
                  $machineHavingCur = "times IN(CURDATE() - INTERVAL 1 DAY)";
                  $machineHavingPrev = "times IN(CURDATE() - INTERVAL 2 DAY)";
                 break;
            case 'week':
                  $machineHavingCur = "times IN(WEEKOFYEAR(NOW()))";
                  $machineHavingPrev = "times IN(WEEKOFYEAR(NOW())-1)";
                 break;
            case 'month':
                  $machineHavingCur = "times IN(MONTH(NOW())) and YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$setting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE())";
                  $machineHavingPrev = "times IN(MONTH(NOW())-1) and YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$setting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE())";


                 break;
         }
       // DB::enableQueryLog();
         $topMachineSalesCount = Kiosk::Select(DB::raw($machineSelect))
                            ->join(with(new Journey())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
                            ->join(with(new Orders())->getTable(),'journeys.journey_id','=','orders.journey_id')
                            ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
                            ->join(with(new KioskModel())->getTable(),'kiosks.model_id','=','kiosk_model.kiosk_model_id')
                            ->where('orders.dispensed_yn','Y')
                            ->where('journey_steps.journey_step_name','checkout')
                            ->groupBy('times','kiosks.kiosk_id')
                            ->havingRaw($machineHavingCur)
                            ->orderBy('timecount','desc');
        if (!empty($accountId)) {
          $topMachineSalesCount = $topMachineSalesCount->where('kiosks.account_id',$accountId);
        }else{
          $topMachineSalesCount = $topMachineSalesCount->whereIn('kiosks.account_id',$account_id);
        }

        $topMachineSalesCount = $topMachineSalesCount->limit(1)->first();
        // $topMachineSalesCount = $topMachineSalesCount->get();
        // dd(DB::getQueryLog());

        if(!empty($topMachineSalesCount)){
             //DB::enableQueryLog();
             // $topMachinevisitorRate = Kiosk::Select(DB::raw($getTimeCondition['timeSelect']))
             //                ->join(with(new Journey())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
             //                ->join(with(new Orders())->getTable(),'journeys.journey_id','=','orders.journey_id')
             //                ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
             //               ->where('journeys.kiosk_id',$topMachineSalesCount->kiosk_id)
             //                ->whereIn('journey_steps.journey_step_name',['cartadd','checkout','product'])
             //                ->groupBy('times')
             //                ->havingRaw($machineHavingCur)
             //                ->orderBy('timecount','Desc')
             //                ->first();
              if($time == 'month'){
                 $machineHavingCur = "times IN(MONTH(NOW())) and YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$setting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE())";
              }
              $timeSelect = $getVisitorTimeCondition['timeSelect'].", count(DISTINCT(customer_id)) as customers";
              $topMachinevisitorRate = Journey::select(DB::raw($timeSelect))
                                    ->where('journeys.kiosk_id',$topMachineSalesCount->kiosk_id)
                                    ->groupBy('times')
                                    ->havingRaw($machineHavingCur)
                                    ->orderBy('timecount','Desc')
                                    ->first();

                   //           dd(DB::getQueryLog());
            $machineData['machine_rate'] = ($topMachineSalesCount->timecount / $topMachinevisitorRate->customers) * 100;

            $machineData['machine_name'] = $topMachineSalesCount->kiosk_identifier;
            $machineData['machine_total'] = $topMachineSalesCount->total;

            $topMachineSalePrev = Kiosk::Select(DB::raw($machineSelect))
                            ->join(with(new Journey())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
                            ->join(with(new Orders())->getTable(),'journeys.journey_id','=','orders.journey_id')
                            ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
                            ->join(with(new KioskModel())->getTable(),'kiosks.model_id','=','kiosk_model.kiosk_model_id')
                            ->where('orders.dispensed_yn','Y')
                           ->where('journeys.kiosk_id',$topMachineSalesCount->kiosk_id)
                            ->where('journey_steps.journey_step_name','checkout')
                            ->groupBy('times')
                            ->havingRaw($machineHavingPrev)
                            ->orderBy('timecount','Desc')
                            ->first();

            if(!empty($topMachineSalePrev)){
                // $topMachinevisitorPrevRate = Kiosk::Select(DB::raw($getTimeCondition['timeSelect']))
                //             ->join(with(new Journey())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
                //             ->join(with(new Orders())->getTable(),'journeys.journey_id','=','orders.journey_id')
                //             ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
                //            ->where('journeys.kiosk_id',$topMachineSalesCount->kiosk_id)
                //             ->whereIn('journey_steps.journey_step_name',['cartadd','checkout','product'])
                //             ->groupBy('times')
                //             ->havingRaw($machineHavingPrev)
                //             ->orderBy('timecount','Desc')
                //             ->first();
              if($time == 'month'){
                 $machineHavingPrev = "times IN(MONTH(NOW())-1) and YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$setting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE())";
              }
              $timeSelect = $getVisitorTimeCondition['timeSelect'].", count(DISTINCT(customer_id)) as customers";
              $topMachinevisitorPrevRate = Journey::select(DB::raw($timeSelect))
                                    ->where('journeys.kiosk_id',$topMachineSalesCount->kiosk_id)
                                    ->groupBy('times')
                                    ->havingRaw($machineHavingPrev)
                                    ->orderBy('timecount','Desc')
                                    ->first();
                 $machineData['machine_prevrate'] = ($topMachineSalePrev->timecount / $topMachinevisitorPrevRate->customers) * 100;
                 $machineData['machine_prevtotal'] = $topMachineSalePrev->total;

            }

        }

        $salesData = array('sales_total' => 0,'sales_prevTotal' => 0,'machine_total' => 0,'machine_prevtotal' => 0,'machine_name' => '');
        $salesConversionData = array('sales_rate' => 0,'sales_prevRate' => 0,'machine_rate' => 0, 'machine_prevrate' => 0,'machine_name' => '');

        $salescurIdx = array_search('current', array_column( $getTimeCondition['compareData'], 'type'));
        $salesprevIdx = array_search('prev', array_column( $getTimeCondition['compareData'], 'type'));


        //print_r($getTimeCondition['compareData'][$salesprevIdx]);
        $salesData['sales_total'] =  $getTimeCondition['compareData'][$salescurIdx]['total'];
        $salesData['sales_prevTotal'] =  $getTimeCondition['compareData'][$salesprevIdx]['total'];
        $salesData['machine_total'] = $machineData['machine_total'];
        $salesData['machine_prevtotal'] = $machineData['machine_prevtotal'];
        $salesData['machine_name'] = $machineData['machine_name'];

        $salesConversionData['sales_rate'] = $getTimeCondition['compareData'][$salescurIdx]['value'];
        $salesConversionData['sales_prevRate'] =  $getTimeCondition['compareData'][$salesprevIdx]['value'];
        $salesConversionData['machine_rate'] = $machineData['machine_rate'];
        $salesConversionData['machine_prevrate'] = $machineData['machine_prevrate'];
        $salesConversionData['machine_name'] = $machineData['machine_name'];


        $result = array('saleData' => $salesData , 'salesConversionData' =>$salesConversionData );
       // print_r($result); die;
        return $result;

    }

    public function customerData($time,$setting,$accountId=null,$kiosk_id=null){
       $account_id = $this->getAccountId();
       $getTimeCondition = $this->getTimeCondition($time,$setting,'journeys.created_at');
       $timeSelect = $getTimeCondition['timeSelect'].", count(DISTINCT(journeys.customer_id)) as customers";
       //DB::enableQueryLog();
       $customer = Journey::select(DB::raw($timeSelect))
                             ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
                            ->where('journey_steps.journey_step_name','cartadd')
                            ->groupBy('times')
                            ->havingRaw($getTimeCondition['havingCond']);
        if(!empty($kiosk_id)){
          $customer = $customer->where('journeys.kiosk_id',$kiosk_id);
        }elseif (!empty($accountId)) {
          $customer = $customer->whereIn('journeys.kiosk_id',function($q) use($accountId){
                                $q->select('kiosk_id')
                                ->from(with(new Kiosk)->getTable())
                                ->where('account_id', $accountId);
                            });
        }else{
          $customer = $customer->whereIn('journeys.kiosk_id',function($q) use($account_id){
                                $q->select('kiosk_id')
                                ->from(with(new Kiosk)->getTable())
                                ->whereIn('account_id', $account_id);
                            });
        }
        $customer = $customer->get();
        //dd(DB::getQueryLog());
        $customerData = array('count' => 0,'prevcount'=>0);
        foreach ($customer as $key => $value) {
           $findD = array_search($value['times'], array_column($getTimeCondition['compareData'], 'data'));
           if($findD !== false){
                if($getTimeCondition['compareData'][$findD]['type'] == 'current'){
                    $customerData['count'] = $value['customers'];
                }elseif ($getTimeCondition['compareData'][$findD]['type'] == 'prev') {
                     $customerData['prevcount'] = $value['customers'];
                }

           }
        }

        return $customerData;

    }
    public function visitorData($time,$setting,$accountId=null,$kiosk_id=null){
       $account_id = $this->getAccountId();
       $getTimeCondition = $this->getTimeCondition($time,$setting,'journeys.created_at');
       $timeSelect = $getTimeCondition['timeSelect'].", count(customer_id) as customers";
      // DB::enableQueryLog();
       $customer = Journey::select(DB::raw($timeSelect))
                            ->groupBy('times')
                            ->havingRaw($getTimeCondition['havingCond']);
        if(!empty($kiosk_id)){
          $customer = $customer->where('journeys.kiosk_id',$kiosk_id);
        }elseif (!empty($accountId)) {
          $customer = $customer->whereIn('journeys.kiosk_id',function($q) use($accountId){
                                $q->select('kiosk_id')
                                ->from(with(new Kiosk)->getTable())
                                ->where('account_id', $accountId);
                            });
        }else{
          $customer = $customer->whereIn('journeys.kiosk_id',function($q) use($account_id){
                                $q->select('kiosk_id')
                                ->from(with(new Kiosk)->getTable())
                                ->whereIn('account_id', $account_id);
                            });
        }
        $customer = $customer->get();
      //  dd(DB::getQueryLog());
       $visitorData = array('count' => 0,'prevcount'=>0);
        foreach ($customer as $key => $value) {
           $findD = array_search($value['times'], array_column($getTimeCondition['compareData'], 'data'));
           if($findD !== false){
                if($getTimeCondition['compareData'][$findD]['type'] == 'current'){
                    $visitorData['count'] = $value['customers'];
                }elseif ($getTimeCondition['compareData'][$findD]['type'] == 'prev') {
                     $visitorData['prevcount'] = $value['customers'];
                }

           }
        }
        return $visitorData;
    }

    private function getQueueData($date_period, $kiosk_id, $isTop = false) {
        $query = AdmQueueData::whereBetween('timestamp', [$date_period[0], $date_period[1]]);

        $kiosk_ids = [];

        if($kiosk_id) {
            $kiosk = Kiosk::find($kiosk_id);
            $query->where('deviceId', $kiosk->kiosk_facial_license);
            $kiosk_ids[] = $kiosk_id;
        } else {
            $deviceIds = Kiosk::where('account_id', \Illuminate\Support\Facades\Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_facial_license');
            if($deviceIds && count($deviceIds) > 0) {
                $query->whereIn('deviceId', $deviceIds);
            }
            $ids = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_id');
            $kiosk_ids = $ids;
        }
        $data = $query->get();

        $query = AdmQueueData::select(DB::raw('*, COUNT(*) as total'));
        $query->groupBy('deviceId');
        $query->orderBy('total', 'DESC');
        $firstMachine = $query->first();

        return ['data' => $data, 'machine' => $firstMachine];
    }

    public function viewsData($time,$setting,$accountId=null,$kiosk_id=null) {
        $date_period = [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'];

        if($time == 'yesterday') {
            $yesterday = date('Y-m-d',strtotime("-1 days"));
            $date_period = [$yesterday . ' 00:00:00', $yesterday . ' 23:59:59'];
        } elseif ($time == 'week') {
            $day = date('w');
            $startWeekDay = date('Y-m-d', strtotime('-'.$day.' days'));
            $lastWeekDay = date('Y-m-d', strtotime('+'.(6-$day).' days'));
            $date_period = [$startWeekDay . ' 00:00:00', $lastWeekDay . ' 23:59:59'];
        } elseif ($time == 'month') {
            $startDay = date('Y-m-01');
            $lastDay = date("Y-m-t", strtotime(date('Y-m-d')));;
            $date_period = [$startDay . ' 00:00:00', $lastDay . ' 23:59:59'];
        } elseif ($time == 'today') {
            $date_period = [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'];
        }
        $topMachine = 0;
        $data = $this->getQueueData($date_period, $kiosk_id, $topMachine);
        $lookers = [];
        $passers = [];
        $top_lookers = [];
        $top_passers = [];
        foreach ($data['data'] as $row) {
            if(strpos($row->deviceName, '-ADS') && $row->isView == 1) {
                $lookers[] = $row;
                if($row->deviceName == $data['machine']) {
                    $top_lookers[] = $row;
                }
            }
            if(strpos($row->deviceName, '-ADS') && $row->isImpression == 1) {
                $passers[] = $row;
                if($row->deviceName == $data['machine']) {
                    $top_passers[] = $row;
                }
            }
        }
        return ['lookers' => $lookers, 'passers' => $passers, 'top_lookers' => $top_lookers, 'top_passers' => $top_passers];
    }

    public function topMachineData($time,$setting,$accountId=null,$kiosk_id=null) {
        $date_period = [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'];

        if($time == 'yesterday') {
            $yesterday = date('Y-m-d',strtotime("-1 days"));
            $date_period = [$yesterday . ' 00:00:00', $yesterday . ' 23:59:59'];
        } elseif ($time == 'week') {
            $day = date('w');
            $startWeekDay = date('Y-m-d', strtotime('-'.$day.' days'));
            $lastWeekDay = date('Y-m-d', strtotime('+'.(6-$day).' days'));
            $date_period = [$startWeekDay . ' 00:00:00', $lastWeekDay . ' 23:59:59'];
        } elseif ($time == 'month') {
            $startDay = date('Y-m-01');
            $lastDay = date("Y-m-t", strtotime(date('Y-m-d')));;
            $date_period = [$startDay . ' 00:00:00', $lastDay . ' 23:59:59'];
        }
        $topMachine = 1;
        $data = $this->getQueueData($date_period, $kiosk_id, $topMachine);
        dd($data);
    }

    public function _get_tenant_machine_variant_list($account_id){

        $account_id = $this->getAccountId();

        if(!empty($account_id)){
            $machineList = KioskProduct::select('kiosks.kiosk_id','kiosks.kiosk_identifier as machine_name','kiosks.kiosk_low_inv_threshold','kiosk_product.bay_no','kiosk_product.quantity','products.product_name','kiosks.template_bin_count','kiosks.template_bin_identity')
                                  ->join(with(new Kiosk())->getTable(),'kiosk_product.kiosk__id','=','kiosks.kiosk_id')
                                  ->join(with(new ProductVariant())->getTable(),'product_variants.product_variant_id','=','kiosk_product.product_variant_id')
                                  ->join(with(new Product())->getTable(),'product_variants.product_id','=','products.product_id')
                                  ->whereIn('kiosks.account_id',$account_id)
                                  ->get();
            if($machineList->count() >0){
              return $machineList->toArray();
            }else{
              return false;
            }
        }
    }
}
