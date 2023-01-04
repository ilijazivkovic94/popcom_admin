<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use App\Helpers\CommonHelper;
use App\Models\Kiosk;
use App\Models\Orders;
use App\Models\JourneyStep;
use App\Models\Journey;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\Account;
use App\Models\User;
// use App\Models\Product;
// use App\Models\ProductVariant;



trait VisitorTrait {  

	public function timeCondition($time,$accountSetting){
		switch ($time) {
			case 'today':
				$timeCondition = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')=DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d')";
			break;
			case 'week':
				$timeCondition = "YEARWEEK(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d'), 1) = YEARWEEK(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'), 1)";
			break;
			case 'month':
				$timeCondition = "MONTH(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = MONTH(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d')) AND YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'))";
			break;
			case 'year':
				$timeCondition = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'))";
			break;
			case 'lastyear':
				$timeCondition = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_SUB(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'), INTERVAL 1 YEAR))";

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

	public function getVisitorList(Request $request){
		if ($request->ajax()) {
			$type=$request->type;

			if($request->type == 'datatable'){
		 		$visitorSelect = "journey_steps.journey__id,DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC',(SELECT kiosk_timezone from kiosks where kiosk_id = journeys.kiosk_id)), '%m/%d/%Y %h:%i %p') as created_at,kiosks.kiosk_identifier,IF(customers.customer_email IS NULL,'Unknown',customers.customer_email) as customer_email,(case when (customers.customer_gender IS NULL or customers.customer_gender = '' or customers.customer_gender = 'O') THEN 'U' else customers.customer_gender end ) as customer_gender,journey_steps.journey_emotion_json,IF(customers.customer_age_group IS NULL,'Unknown',customers.customer_age_group) as customer_age_group,orders.order_transaction_ref,orders.order_total,(select account_name from accounts where account_id = kiosks.account_id) as account_name,kiosks.kiosk_timezone,
		 		(SELECT timezone_abbr from timezone where timezone=kiosks.kiosk_timezone) as timezone_abbr,orders.dispensed_yn";
		 	}else{
		 		$visitorSelect = "SUM(orders.order_total) as total";
		 	}

		 	//DB::enableQueryLog();
		 	$visitorData = JourneyStep::select(DB::raw($visitorSelect))
		 					->join(with(new Journey())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
		 					->join(with(new Customer())->getTable(),'customers.customer_id','=','journeys.customer_id')
		 					->leftjoin(with(new Orders())->getTable(),'orders.journey_id','=','journeys.journey_id')
		 					->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
		 					->groupBy('journeys.journey_id');
		 					
		 				//dd(DB::getQueryLog());	
		 	//if($request->type == 'datatable'){
		 	//	$visitorData = $visitorData->groupBy('journey_steps.journey_step_id');
		 	//}				
		 	if(!empty($request->kiosk_id) && $request->kiosk_id != 'all'){
		 		$visitorData = $visitorData->where('kiosks.kiosk_id',$request->kiosk_id);
		 	}elseif(!empty($request->accountId)){
		 		$visitorData = $visitorData->where('kiosks.account_id',$request->accountId);
		 	}else{
		 		$account_id = $this->getAccounts();
		        $visitorData = $visitorData->whereIn('kiosks.account_id',$account_id);
		 	}
		 	


		 	if(!empty($request->datepick)){
		 		$datpicker = explode(' to ', $request->datepick);
		 		$start = $datpicker[0];
		 		$end = $datpicker[1];
		 		$accountID = Auth::user()->account_id;            
            	$setting = User::with(['accountDetails', 'accountSetting'])->where('account_id', $accountID)->first();
		 		// $start = date("Y-m-d", strtotime($fromDate));
     //            $end = date("Y-m-d", strtotime($toDate));
		 		$datecondition = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$setting->accountSetting->account_timezone."'), '%Y-%m-%d') 
                    BETWEEN '" . $start . "' AND '" . $end . "' ";
                $visitorData = $visitorData->whereRaw($datecondition);    
		 	}else{
		 		if(!empty($request->timeperiod)){
			 		$accountID = Auth::user()->account_id;            
	            	$setting = User::with(['accountDetails', 'accountSetting'])->where('account_id', $accountID)->first();
			 		$timeCondition = $this->timeCondition($request->timeperiod,$setting);

			 		$visitorData = $visitorData->whereRaw($timeCondition);
			 	}
		 	}

		 	if(!empty($request->search['value'])){
		 		$searchCond = "(kiosks.kiosk_identifier like '%".$request->search['value']."%' OR customer_email like '%".$request->search['value']."%' OR customer_age_group like '%".$request->search['value']."%' )";
		 		$visitorData = $visitorData->whereRaw($searchCond);
		 	}
		 	
		 	if(Auth::user()->accountDetails->account_type == 'ent'){
		 			$orderColumn = array('created_at','account_name','kiosk_identifier','customer_email','customer_gender',null,'customer_age_group','dispensed_yn','CAST(`order_total` AS DECIMAL(10,2))');
		 	}else{
		 		$orderColumn = array('created_at','kiosk_identifier','customer_email','customer_gender',null,'customer_age_group','dispensed_yn','CAST(`order_total` AS DECIMAL(10,2))');
		 	}
		 	

		 	if(!empty($request->order[0]['dir'])){
		 		$visitorData = $visitorData->orderByRaw($orderColumn[$request->order[0]['column']]." ".$request->order[0]['dir']);
		 	}else{
		 		$visitorData = $visitorData->orderBy('journeys.journey_id','desc');
		 	}
		 
//$visitorData = $visitorData->get();
		// dd(DB::getQueryLog());	
		 	if($request->type == 'datatable'){
			 	return Datatables::of($visitorData)
	                ->addIndexColumn()
	                ->editColumn('customer_gender', function ($row) {
	                	switch ($row->customer_gender) {
	                		case 'M':
	                			 $img = "<img class='mr-2' src='".asset('public/assets/svg/male.svg')."'> Male";
	                			break;
	                		case 'F':
	                			 $img = "<img class='mr-2' src='".asset('public/assets/svg/female.svg')."'> Female";
	                			break;
	                		case 'U':
	                			 $img = "<img class='mr-2' src='".asset('public/assets/svg/unknown.svg')."'> Unknown";
	                			break;
	                	}
	                    return '<div class="d-flex align-items-center symbol symbol-35">'.$img.'</div>';
	                })
	                ->editColumn('journey_emotion_json', function ($row) {

	                
	                     if (!empty($row->journey_emotion_json)) {
	                     	$dbemotion = json_decode($row->journey_emotion_json,true);
	                     	if(!empty($dbemotion)){
	                     		$emotion_array = array_filter($dbemotion);
		                        if (count($emotion_array) != 0) {
		                            $emotion = array_keys($emotion_array, max($emotion_array));
		                            $img = "<img class='mr-2' src='". asset('public/assets/svg/' . implode("", $emotion) . '.svg') . "'>" . implode("", $emotion);
		                        } else {
		                            $img = "<img class='mr-2' src='".asset('public/assets/svg/neutral.svg')."'> Neutral";
		                        }
	                     	}else{
	                     		 $img = "<img class='mr-2' src='".asset('public/assets/svg/unknown.svg')."'> Unknown";
	                     	}
	                    } else {
	                        $img = "<img class='mr-2' src='".asset('public/assets/svg/unknown.svg')."'> Unknown";
	                    }

	                    return '<div class="d-flex align-items-center symbol symbol-35 mr-2">'.$img.'</div>';
	                    
	                })

	                ->editColumn('created_at', function ($row) {
	                 	// $created_on = CommonHelper::dateConversion($row->created_at, $row->kiosk_timezone, 'm/d/Y h:i A');
	                 	$created_on = $row->created_at;
	                    return $created_on."(".$row->timezone_abbr.")";

	                })
	                ->editColumn('dispensed_yn', function ($row) {
	                    return ($row->dispensed_yn == 'Y' ? 'Yes' : 'No');
	                })
	                ->editColumn('order_total', function ($row) {
	                	//if(!empty(var))
	                    return !empty($row->order_total) ? '$'.number_format($row->order_total,2) : '$0.0';
	                })
	                ->rawColumns(['customer_gender', 'journey_emotion_json','dispensed_yn','order_total','created_at'])
	                ->make(true);
            }else{
            	$visitorData = $orderData->first();
            	$output = array(
		            "total" =>  "$" . number_format($orderData->total, 2),
		        );
		        //echo json_encode($output);
		         return response()->json($output);
            }

		}
	}

	public function getVisitorAnalyticsData($time,$setting,$kiosk_id=null,$accountId=null){

		$timeCondition = $this->timeCondition($time,$setting);

		$analyticData = array();
		$genderSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','America/New_York'), '%Y-%m-%d') as times,
 count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','America/New_York'), '%Y-%m-%d')) as timescount,
(case when (customers.customer_gender IS NULL or customers.customer_gender = '' or customers.customer_gender = 'O') THEN 'U' else customers.customer_gender end ) as customer_gender";
		$genderVisitor = Journey::select(DB::raw($genderSelect))
								->join(with(new Customer())->getTable(),'customers.customer_id','=','journeys.customer_id')
		 						->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
		 						->whereRaw($timeCondition)
		 						->groupBy('customer_gender');
		if(!empty($kiosk_id)){
	 		$genderVisitor = $genderVisitor->where('kiosks.kiosk_id',$kiosk_id);
	 	}elseif(!empty($accountId)){
	 		$genderVisitor = $genderVisitor->where('kiosks.account_id',$accountId);
	 	}else{
	 		$account_id = $this->getAccounts();
	        $genderVisitor = $genderVisitor->whereIn('kiosks.account_id',$account_id);
	 	}

	 	$genderVisitor = $genderVisitor->get()->toArray();

	 	$totals = array_sum(array_column($genderVisitor, 'timescount'));

	 	foreach ($genderVisitor as $key => $value) {
	 		if($totals > 0){
	 			$analyticData[$value['customer_gender']] = ($value['timescount'] / $totals) * 100;
	 		}else{
	 			$analyticData[$value['customer_gender']] = 0;
	 		}
	 		
	 	}

	 	$ageGroupSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','America/New_York'), '%Y-%m-%d') as times,
 count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','America/New_York'), '%Y-%m-%d')) as timescount,
IF(customers.customer_age_group IS NULL,'Unknown',customers.customer_age_group) as customer_age_group";
		$ageGroupVisitor = Journey::select(DB::raw($ageGroupSelect))
								->join(with(new Customer())->getTable(),'customers.customer_id','=','journeys.customer_id')
		 						->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
		 						->whereRaw($timeCondition)
		 						->groupBy('customer_age_group');
		if(!empty($kiosk_id)){
	 		$ageGroupVisitor = $ageGroupVisitor->where('kiosks.kiosk_id',$kiosk_id);
	 	}elseif(!empty($accountId)){
	 		$ageGroupVisitor = $ageGroupVisitor->where('kiosks.account_id',$accountId);
	 	}else{
	 		$account_id = $this->getAccounts();
	        $ageGroupVisitor = $ageGroupVisitor->whereIn('kiosks.account_id',$account_id);
	 	}

	 	$ageGroupVisitor = $ageGroupVisitor->get()->toArray();


	 	foreach ($ageGroupVisitor as $key => $value) {
	 		$label = str_replace(' ', '_', $value['customer_age_group']);
	 		if($totals > 0){
	 			$analyticData[strtolower($label)] = ($value['timescount'] / $totals) * 100;
	 		}else{
	 			$analyticData[strtolower($label)] = 0;
	 		}
	 		
	 	}

	 	return array('data'=>$analyticData,'total'=>$totals);
	}

	private function getCondition($time,$accountSetting,$start_date=null,$end_date=null){
		$columnName ="journeys.created_at";
		switch ($time) {
			case 'today':
	                    $timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%H') times,count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%H')) as timecount";
	                    $whereCond = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d') = CURRENT_DATE()";
	                   
	                 break;
			case 'week':
	                    $timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d') times,count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d')) as timecount";
	                    $whereCond = "WEEKOFYEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = WEEKOFYEAR(NOW())";
	                 break;
			case 'month':
					$timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d') times,count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d')) as timecount";

                    $whereCond = "MONTH(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = MONTH(NOW()) and YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE())";
				break;
			case 'year':
					$timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%M') times,count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m')) as timecount";

                    $whereCond = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE())";
				break;
			case 'lastyear':
					$timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%M') times,count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m')) as timecount";

                    $whereCond = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE()) - 1";
				break;
			case 'picker':
					$timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d') times,count(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d')) as timecount";

                    $whereCond = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."'";
				break;
		}

		return array('timeSelect' => $timeSelect,'whereCond' => $whereCond);
	}

	public function genderChart($time,$setting,$strt_dt=null,$end_dt=null,$kiosk_id=null,$accountId = null){

		$timeCondition = $this->getCondition($time,$setting,$strt_dt,$end_dt);

		$genderSelect = $timeCondition['timeSelect'].", (case when (customers.customer_gender IS NULL or customers.customer_gender = '' or customers.customer_gender = 'O') THEN 'U' else customers.customer_gender end ) as customer_gender" ;
		$genderVisitor = Journey::select(DB::raw($genderSelect))
								->join(with(new Customer())->getTable(),'customers.customer_id','=','journeys.customer_id')
		 						->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
		 						->whereRaw($timeCondition['whereCond'])
		 						->groupBy('times','customer_gender');
		$yaxisData = array(
					array('name'=>'Male','id'=>'M','color'=>'0,216,189,1'),
					array('name'=>'Female','id'=>'F','color'=>'126,89,249,1'),
					array('name'=>'Unknown','id'=>'U','color'=>'0,143,250,1'),
				);
		if(!empty($kiosk_id)){
	 		$genderVisitor = $genderVisitor->where('kiosks.kiosk_id',$kiosk_id);
	 	}elseif(!empty($accountId)){
	 		$genderVisitor = $genderVisitor->where('kiosks.account_id',$accountId);
	 	}else{
	 		$account_id = $this->getAccounts();
	        $genderVisitor = $genderVisitor->whereIn('kiosks.account_id',$account_id);
	 	}

	 	$genderVisitor = $genderVisitor->get()->toArray();


	 	$labels = CommonHelper::getDateArrayList($time,$strt_dt,$end_dt);

			$chartData = array();
	        $yaxis = array();
	       	$data= array();
	        foreach ($labels as $key => $value) {
	        	switch ($time) {
	        		case 'today':
	        			array_push($yaxis,$value);
	        			break;
	        		case 'week':
	        			 $val = date('m/d',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'month':
	        				$val = date('m/d',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'year':
	        				$val = date('F',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'lastyear':
	        				$val = date('F',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'picker':
	        				$val = date('m/d',strtotime($value));
	    					array_push($yaxis,$val);
	        			break;
	        		
	        	}
	        	
	        	array_push($data,0);
	        }   
	        foreach ($yaxisData as $key => $y) {
	        	//$color = $this->randomColor();
	              $dummydata = array('label'=> $y['name'],
	                'backgroundColor'=>  'rgba('.$y["color"].')',
	                'borderColor'=> 'rgba('.$y["color"].')',
	                'stack'=> 'combined',
	                'type' => 'bar',
	                'data'=>$data
	              );
	             
	              $chartData[$y['id']] = $dummydata;
	             //array_push($chartData,$dummydata);
	        }

	        foreach ($genderVisitor as $key => $value) {
	    		if($time == 'today'){
	    			$needle = $labels[$value['times']];
	    		}else{
	    			$needle = $value['times'];
	    		}
	    		$findDataindex = array_search($needle, $yaxis);
	    		if($findDataindex !== false){
	    			$chartData[$value['customer_gender']]['data'][$findDataindex] = $value['timecount'];
	    		}
	        }
	        $chartData = array_values($chartData);
	        return array('yaxis' => $yaxis, 'charData' => $chartData);
	}

	public function agegroupChart($time,$setting,$strt_dt=null,$end_dt=null,$kiosk_id=null,$accountId = null){

		$timeCondition = $this->getCondition($time,$setting,$strt_dt,$end_dt);

		$ageSelect = $timeCondition['timeSelect'].", IF(customers.customer_age_group IS NULL,'Unknown',customers.customer_age_group) as customer_age_group" ;
		$ageVisitor = Journey::select(DB::raw($ageSelect))
								->join(with(new Customer())->getTable(),'customers.customer_id','=','journeys.customer_id')
		 						->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
		 						->whereRaw($timeCondition['whereCond'])
		 						->groupBy('times','customer_age_group');
		$yaxisData = array(
					array('name'=>'Adult','id'=>'adult','color'=>'126,89,249,1'),
					array('name'=>'Senior','id'=>'senior','color'=>'0,143,250,1'),
					array('name'=>'Young Adult','id'=>'young_adult','color'=>'0,216,189,1'),
					array('name'=>'Unknown','id'=>'unknown','color'=>'255,228,51,1')
				);
		if(!empty($kiosk_id)){
	 		$ageVisitor = $ageVisitor->where('kiosks.kiosk_id',$kiosk_id);
	 	}elseif(!empty($accountId)){
	 		$ageVisitor = $ageVisitor->where('kiosks.account_id',$accountId);
	 	}else{
	 		$account_id = $this->getAccounts();
	        $ageVisitor = $ageVisitor->whereIn('kiosks.account_id',$account_id);
	 	}

	 	$ageVisitor = $ageVisitor->get()->toArray();


	 	$labels = CommonHelper::getDateArrayList($time,$strt_dt,$end_dt);

			$chartData = array();
	        $yaxis = array();
	       	$data= array();
	        foreach ($labels as $key => $value) {
	        	switch ($time) {
	        		case 'today':
	        			array_push($yaxis,$value);
	        			break;
	        		case 'week':
	        			 $val = date('m/d',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'month':
	        				$val = date('m/d',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'year':
	        				$val = date('F',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'lastyear':
	        				$val = date('F',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'picker':
	        				$val = date('m/d',strtotime($value));
	    					array_push($yaxis,$val);
	        			break;
	        		
	        	}
	        	
	        	array_push($data,0);
	        }   
	        foreach ($yaxisData as $key => $y) {
	              $dummydata = array('label'=> $y['name'],
	                'backgroundColor'=>  'rgba('.$y["color"].')',
	                'borderColor'=> 'rgba('.$y["color"].')',
	                'stack'=> 'combined',
	                'type' => 'bar',
	                'data'=>$data
	              );
	             
	              $chartData[$y['id']] = $dummydata;
	             //array_push($chartData,$dummydata);
	        }

	        foreach ($ageVisitor as $key => $value) {
	    		if($time == 'today'){
	    			$needle = $labels[$value['times']];
	    		}else{
	    			$needle = $value['times'];
	    		}
	    		$findDataindex = array_search($needle, $yaxis);
	    		if($findDataindex !== false){
	    			$keylbl = str_replace(' ', '_', $value['customer_age_group']);
	    			$chartData[strtolower($keylbl)]['data'][$findDataindex] = $value['timecount'];
	    		}
	        }
	        $chartData = array_values($chartData);
	        return array('yaxis' => $yaxis, 'charData' => $chartData);
	}


	public function emotionChart($time,$setting,$strt_dt=null,$end_dt=null,$kiosk_id=null,$accountId = null){

		$timeCondition = $this->getCondition($time,$setting,$strt_dt,$end_dt);
		//DB::enableQueryLog();
		$ageSelect = $timeCondition['timeSelect'].", journey_steps.journey_emotion_json,journey_steps.journey_step_id" ;
		$emotionVisitor = Journey::select(DB::raw($ageSelect))
								->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
								->join(DB::raw("(select journey__id, max(journey_step_id) as maxStepid
								          from journey_steps
								          group by journey__id
								     ) as newjourneystep"),'journeys.journey_id','=','newjourneystep.journey__id')
		 						->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
		 						->whereRaw($timeCondition['whereCond'])
		 						->whereRaw('journey_steps.journey_step_id = newjourneystep.maxStepid')
		 						->groupBy('times','journeys.journey_id');
		$yaxisData = array(
					array('name'=>'Anger','id'=>'anger','color'=>'126,89,249,1'),
					array('name'=>'Disgust','id'=>'disgust','color'=>'0,143,250,1'),
					array('name'=>'Fear','id'=>'fear','color'=>'0,216,189,1'),
					array('name'=>'Joy','id'=>'joy','color'=>'255,228,51,1'),
					array('name'=>'Sadness','id'=>'sadness','color'=>'0,216,189,1'),
					array('name'=>'Surprise','id'=>'surprise','color'=>'214,39,40,1'),
					array('name'=>'Neutral','id'=>'neutral','color'=>'91,193,205,1'),
					array('name'=>'Unknown','id'=>'unknown','color'=>'31,119,180,1')

				);
		if(!empty($kiosk_id)){
	 		$emotionVisitor = $emotionVisitor->where('kiosks.kiosk_id',$kiosk_id);
	 	}elseif(!empty($accountId)){
	 		$emotionVisitor = $emotionVisitor->where('kiosks.account_id',$accountId);
	 	}else{
	 		$account_id = $this->getAccounts();
	        $emotionVisitor = $emotionVisitor->whereIn('kiosks.account_id',$account_id);
	 	}

	 	$emotionVisitor = $emotionVisitor->get()->toArray();

	 	//dd(DB::getQueryLog());
	 	$labels = CommonHelper::getDateArrayList($time,$strt_dt,$end_dt);

			$chartData = array();
	        $yaxis = array();
	       	$data= array();
	        foreach ($labels as $key => $value) {
	        	switch ($time) {
	        		case 'today':
	        			array_push($yaxis,$value);
	        			break;
	        		case 'week':
	        			 $val = date('m/d',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'month':
	        				$val = date('m/d',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'year':
	        				$val = date('F',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'lastyear':
	        				$val = date('F',strtotime($value));
	        				array_push($yaxis,$val);
	        			break;
	        		case 'picker':
	        				$val = date('m/d',strtotime($value));
	    					array_push($yaxis,$val);
	        			break;
	        		
	        	}
	        	
	        	array_push($data,0);
	        }   
	        foreach ($yaxisData as $key => $y) {
	              $dummydata = array('label'=> $y['name'],
	                'backgroundColor'=>  'rgba('.$y["color"].')',
	                'borderColor'=> 'rgba('.$y["color"].')',
	                'stack'=> 'combined',
	                'type' => 'bar',
	                'data'=>$data
	              );
	             
	              $chartData[$y['id']] = $dummydata;
	        }

	       
	        foreach ($emotionVisitor as $key => $value) {
	    		if($time == 'today'){
	    			$needle = $labels[$value['times']];
	    		}else{
	    			$needle = $value['times'];
	    		}
	    		$findDataindex = array_search($needle, $yaxis);
	    		if($findDataindex !== false){

	    			if(!empty($value['journey_emotion_json'])){
	    				$dbemotion = json_decode($value['journey_emotion_json'],true);

	    				$convertedemotion   = !empty($dbemotion) ? array_filter($dbemotion ) : array();
							if(count($convertedemotion) > 0){
								$emotionlist 		= implode(",", $convertedemotion);
								$emotionarr  		= explode(",", $emotionlist);
								$maxemotion  		= max($emotionarr);
								$emotiontype 		= array_search($maxemotion, $convertedemotion);

								$chartData[$emotiontype]['data'][$findDataindex] = $chartData[$emotiontype]['data'][$findDataindex] + 1;
							}else{
								$chartData['unknown']['data'][$findDataindex] = $chartData['unknown']['data'][$findDataindex] + 1;
							}
					}else{
						$chartData['unknown']['data'][$findDataindex] = $chartData['unknown']['data'][$findDataindex] + 1;
					}
	    			
	    		}
	        }
	        $chartData = array_values($chartData);
	        return array('yaxis' => $yaxis, 'charData' => $chartData);
	}
}