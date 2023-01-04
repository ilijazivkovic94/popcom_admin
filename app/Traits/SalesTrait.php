<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use App\Helpers\CommonHelper;
use App\Models\Kiosk;
use App\Models\KioskModel;
use App\Models\Orders;
use App\Models\JourneyStep;
use App\Models\Journey;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\Account;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;



trait SalesTrait {  

	public function timeCondition($time,$accountSetting,$columnName=null){

		$columnName = !empty($columnName) ? $columnName : 'orders.created_at';
		switch ($time) {
			case 'today':
				$timeCondition = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')=DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d')";
			break;
			case 'week':
				$timeCondition = "YEARWEEK(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d'), 1) = YEARWEEK(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'), 1)";
			break;
			case 'month':
				$timeCondition = "MONTH(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = MONTH(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d')) AND YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'))";
			break;
			case 'year':
				$timeCondition = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'))";
			break;
			case 'lastyear':
				$timeCondition = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(DATE_SUB(DATE_FORMAT(CONVERT_TZ(NOW(),'UTC','".$accountSetting->accountSetting->account_timezone."'),'%Y-%m-%d'), INTERVAL 1 YEAR))";
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

	public function getSalesList(Request $request){
		 if ($request->ajax()) {

		 	$type=$request->type;

		 	
		 		$orderSelect = "orders.order_id,orders.order_total,orders.order_subtotal,orders.order_tax,orders.order_discount_value,carts.product_qty,IF(orders.created_at is not null,DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(orders.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC',(SELECT kiosk_timezone from kiosks where kiosk_id = journeys.kiosk_id)), '%m/%d/%Y %h:%i %p'), DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC',(SELECT kiosk_timezone from kiosks where kiosk_id = journeys.kiosk_id)), '%m/%d/%Y %h:%i %p')) as created_on,kiosks.kiosk_identifier,IF(customers.customer_email IS NULL,'Unknown',customers.customer_email) as customer_email,IF(orders.promo_id != 0,(SELECT promo_code from promos WHERE promo_id = orders.promo_id),'') as promo_code,accounts.account_name,kiosks.kiosk_timezone,(SELECT timezone_abbr from timezone where timezone=kiosks.kiosk_timezone) as timezone_abbr";
		 
		 		// $orderSelect = "SUM(orders.order_total) as total,sum(orders.order_subtotal) as subtotal,sum(orders.order_tax) as tax,sum(carts.product_qty) as product_qty";
		 	
			//DB::enableQueryLog();
		 	$orderData = JourneyStep::select(DB::raw($orderSelect))
		 					->leftjoin(with(new Cart())->getTable(),'journey_steps.journey__id','=','carts.journey_id')
		 					->leftjoin(with(new Journey())->getTable(),'journeys.journey_id','=','carts.journey_id')
		 					->leftjoin(with(new Orders())->getTable(),'carts.cart_id','=','orders.cart_id')
		 					->leftjoin(with(new Customer())->getTable(),'customers.customer_id','=','journeys.customer_id')
		 					->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
		 					->join(with(new Account())->getTable(),'kiosks.account_id','=','accounts.account_id');

		
		 					
		 	if($request->type == 'datatable'){
		 		$orderData = $orderData->groupBy('journeys.journey_id');
		 	}else{
		 		$orderData = $orderData->groupBy('journeys.journey_id');
		 	}			

		 	if(!empty($request->kiosk_id)){

		 		$orderData = $orderData
		 					->where('kiosks.kiosk_id',$request->kiosk_id);
		 	}elseif(!empty($request->accountId)){
		 		$orderData = $orderData->where('kiosks.account_id',$request->accountId);
		 	}else{
		 		$account_id = $this->getAccounts();
		        $orderData = $orderData->whereIn('kiosks.account_id',$account_id);
		 	}
		 	


		 	if(!empty($request->datepick)){
		 		$datpicker = explode(' to ', $request->datepick);
		 		$start = $datpicker[0];
		 		$end = $datpicker[1];
		 		//dd($end);
		 		$account_id = Auth::user()->account_id;            
            	$setting = User::with(['accountDetails', 'accountSetting'])->where('account_id', $account_id)->first();
		 		// $start = date("Y-m-d", strtotime($request->fromDate));
     			// $end = date("Y-m-d", strtotime($request->toDate));
		 		$datecondition = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(journeys.created_at/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$setting->accountSetting->account_timezone."'), '%Y-%m-%d') 
                    BETWEEN '" . $start . "' AND '" . $end . "' ";

                //dd($datecondition);
                $orderData = $orderData->whereRaw($datecondition);    
		 	}else{
		 		if(!empty($request->timeperiod)){
			 		$accountID = Auth::user()->account_id;            
	            	$setting = User::with(['accountDetails', 'accountSetting'])->where('account_id', $accountID)->first();
			 		$timeCondition = $this->timeCondition($request->timeperiod,$setting,'journeys.created_at');

			 		$orderData = $orderData->whereRaw($timeCondition);
			 	}
		 	}

		 	//added by komal for customer sales by email id
		 	if(!empty($request->email)) {
		 		$orderData = $orderData->where('customers.customer_email',decrypt($request->email));
		 	}

		 	if(!empty($request->cart_event)){
		 		if($request->cart_event == 'checkout'){
		 			$orderData = $orderData->where('journey_steps.journey_step_name',$request->cart_event);
		 		}elseif($request->cart_event == 'abandon'){
		 			$orderData = $orderData->where('journey_steps.journey_step_name',$request->cart_event);
		 		}else{
		 			// $orderData = $orderData->first();
		 			// dd(DB::getQueryLog());
		 		}
		 	}
		 

		 	if(Auth::user()->accountDetails->account_type == 'ent'){
		 		$orderColumn = array('order_id','created_on','account_name','kiosk_identifier','customer_email','product_qty','CAST(`order_subtotal` AS DECIMAL(10,2))','CAST(`order_tax` AS DECIMAL(10,2))','CAST(`order_total` AS DECIMAL(10,2))','CAST(`order_discount_value` AS DECIMAL(10,2))','promo_code');
		 	}else{
		 		$orderColumn = array('order_id','created_on','kiosk_identifier','customer_email','product_qty','CAST(`order_subtotal` AS DECIMAL(10,2))','CAST(`order_tax` AS DECIMAL(10,2))','CAST(`order_total` AS DECIMAL(10,2))','CAST(`order_discount_value` AS DECIMAL(10,2))','promo_code');
		 	}
		 	

		 	if(!empty($request->order[0]['dir'])){
		 		$orderData = $orderData->orderByRaw($orderColumn[$request->order[0]['column']]." ".$request->order[0]['dir']);
		 	}else{
		 		$orderData = $orderData->orderBy('orders.order_id','desc');
		 	}
		 	//$orderData = $orderData->first();
 //dd(DB::getQueryLog());
		 		//$orderData = $orderData->get();
 //dd(DB::getQueryLog());

		 	if($request->type == 'datatable'){

			 	return Datatables::of($orderData)
	                ->addIndexColumn()
	                ->editColumn('order_id', function ($row) {
	                	$order_id = !empty($row->order_id) ? $row->order_id : 0;
	                    return '<a href="#" class="orderDetail">'.$order_id.'</a>';
	                })
	                ->editColumn('created_on', function ($row) {
	                 	 //$created_on = CommonHelper::dateConversion($row->created_on, $row->kiosk_timezone, 'm/d/Y h:i A');
	                    return !empty($row->created_on)?$row->created_on."(".$row->timezone_abbr.")":"";
	                })
	                ->editColumn('order_subtotal', function ($row) {
	                    return '$'.number_format($row->order_subtotal,2);
	                })
	                ->editColumn('order_tax', function ($row) {
	                    return '$'.number_format($row->order_tax,2);
	                })
	                ->editColumn('order_discount_value', function ($row) {
	                    return '$'.number_format($row->order_discount_value,2);
	                })
	                ->editColumn('order_total', function ($row) {
	                    return '$'.number_format($row->order_total,2);
	                })
	                ->rawColumns(['order_id', 'total_order','order_subtotal','order_discount_value','order_tax','created_at'])
	                ->make(true);
            }else {
            	$orderData = $orderData->get()->toArray();
            	//dd(DB::getQueryLog());
            	$orderTotalData= array(
            		'product_qty' => 0,
            		'order_subtotal' => 0,
            		'order_tax' => 0,
            		'order_total' => 0
            	);
            	foreach ($orderData as $key => $value) {
            		$orderTotalData['product_qty'] = $orderTotalData['product_qty'] + $value['product_qty'];
            		$orderTotalData['order_subtotal'] = $orderTotalData['order_subtotal'] + $value['order_subtotal'];
            		$orderTotalData['order_tax'] = $orderTotalData['order_tax'] + $value['order_tax'];
            		$orderTotalData['order_total'] = $orderTotalData['order_total'] + $value['order_total'];
            	}
            	$output = array(
		            "quantity" => (!empty($orderTotalData['product_qty'])) ? $orderTotalData['product_qty'] : 0,
		            "subtotal" => !empty($orderTotalData['order_subtotal']) ? "$" . number_format($orderTotalData['order_subtotal'], 2) : "$0.00",
		            "tax" =>  !empty($orderTotalData['order_tax']) ? "$" . number_format($orderTotalData['order_tax'], 2) : "$0.00",
		            "total" =>  !empty($orderTotalData['order_total']) ? "$" . number_format($orderTotalData['order_total'], 2) : "$0.00",
		        );
		        //echo json_encode($output);
		         return response()->json($output);
            }
		 }
	}

	public function getSaleChart($time,$accountSetting,$kiosk_id=null,$strdt=null,$endt=null){
		$columnName = "orders.created_at";
		
		$times = $this->getChartTimeCondition($time,$accountSetting,$columnName,$strdt,$endt);

		$labels = CommonHelper::getDateArrayList($time,$strdt,$endt);
		$colors = array("91,193,205,1","135,185,77,1","144,97,184,1","234,84,84,1","255,192,1,1","58,139,202,1","237,190,64,1","171,78,252,1","193,148,23,1","193,148,23,1");
		//DB::enableQueryLog();
		$sales = Kiosk::Select(DB::raw($times['timeSelect']))
                            ->join(with(new Journey())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
                            ->join(with(new Orders())->getTable(),'journeys.journey_id','=','orders.journey_id')
                            ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
                            ->where('orders.dispensed_yn','Y')
                            ->where('journey_steps.journey_step_name','checkout')
                            ->whereRaw($times['whereCond'])
                            ->groupBy('times','kiosks.kiosk_id')
                            ->orderBy('times','Desc');

        if(!empty($kiosk_id)){
          $sales = $sales->whereIn('kiosks.kiosk_id',$kiosk_id);
           $yaxisData = Kiosk::Select('kiosk_identifier')
           						->whereIn('kiosk_id',$kiosk_id)
          						->get()->toArray();
        }else{
          $account_id = $this->getAccounts();
         
          $sales = $sales->whereIn('kiosks.account_id',$account_id);
          $yaxisData = Kiosk::Select('kiosk_identifier')
          					->whereIn('account_id',$account_id)
          					->get()->toArray();
        }     
        $sales = $sales->get()->toArray();
//dd(DB::getQueryLog());
        $chartData = array();
        $yaxis = array();
       	$data= array();
        foreach ($labels as $key => $value) {
        	if($time == 'today'){
        		array_push($yaxis,$value);
        	}else{
        		$val = date('m/d',strtotime($value));
        		array_push($yaxis,$val);
        	}
        	
        	array_push($data,0);
        }   
        foreach ($yaxisData as $key => $y) {
        	$color = $this->randomColor();
              $dummydata = array('label'=> $y['kiosk_identifier'],
                'backgroundColor'=> $color,
                'borderColor'=> $color,
                'borderWidth' => 1,
                'fill' => false,
                'data'=>$data
              );
             array_push($chartData,$dummydata);
        }

        foreach ($sales as $key => $value) {
        	$findKiosk = array_search($value['kiosk_identifier'], array_column($chartData, 'label'));

        	if($findKiosk !== false){
        		if($time == 'today'){
        			$needle = $labels[$value['times']];
        		}else{
        			$needle =$value['times'];
        		}
        		$findDataindex = array_search($needle, $yaxis);
        		if($findDataindex !== false){
        			$chartData[$findKiosk]['data'][$findDataindex] = $value['total'];
        		}
        		
        	}
        }
        return array('yaxis' => $yaxis, 'charData' => $chartData);
	}
	
	private function getChartTimeCondition($time,$accountSetting,$columnName,$start_date=null,$end_date=null){
		switch ($time) {
			case 'today':
                    $timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%H') times,SUM(orders.order_total) as total,kiosks.kiosk_id,kiosks.kiosk_identifier";
                    $whereCond = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d') = CURRENT_DATE()";

                   
                 break;
			case 'picker':
					$timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d') times,
                   		 SUM(orders.order_total) as total,kiosks.kiosk_id,kiosks.kiosk_identifier";

                    $whereCond = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."'";
				break;
			
		}

		return array('timeSelect' => $timeSelect,'whereCond' => $whereCond);
	}
	

	private function getCondition($time,$accountSetting,$start_date=null,$end_date=null){
		$columnName ="orders.created_at";
		switch ($time) {
			case 'today':
	                    $timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%H') times,SUM(orders.order_total) as total";
	                    $whereCond = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d') = CURRENT_DATE()";
	                   
	                 break;
			case 'week':
	                    $timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d') times,SUM(orders.order_total) as total";
	                    $whereCond = "WEEKOFYEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = WEEKOFYEAR(NOW())";
	                 break;
			case 'month':
					$timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d') times,
                   		 SUM(orders.order_total) as total";

                    $whereCond = "MONTH(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = MONTH(NOW()) and YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE())";
				break;
			case 'year':
					$timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%M') times,
                   		 SUM(orders.order_total) as total";

                    $whereCond = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE())";
				break;
			case 'lastyear':
					$timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%M') times,
                   		 SUM(orders.order_total) as total";

                    $whereCond = "YEAR(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d')) = YEAR(CURRENT_DATE()) - 1";
				break;
			case 'picker':
					$timeSelect = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%m/%d') times,
                   		 SUM(orders.order_total) as total";

                    $whereCond = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(".$columnName."/1000,'%Y-%m-%d %H:%i:%s %p'),'UTC','".$accountSetting->accountSetting->account_timezone."'), '%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."'";
				break;
		}

		return array('timeSelect' => $timeSelect,'whereCond' => $whereCond);
	}

	public function getProductSaleChart($time,$setting,$type,$accountId,$start_date=null,$end_date=null,$kiosk_id=null){
		$timeSelect = $this->getCondition($time,$setting,$start_date,$end_date);

		
		//$colors = array("91,193,205,1","135,185,77,1","144,97,184,1","234,84,84,1","255,192,1,1","58,139,202,1","237,190,64,1","171,78,252,1","97,165,13,1","12,166,205,1","211,0,0,1");
		//DB::enableQueryLog();
		
		$timeSelect['timeSelect'] = $timeSelect['timeSelect'].",products.product_id,products.product_name,products.product_image";
		//DB::enableQueryLog();
		$productSales = Product::Select(DB::raw($timeSelect['timeSelect']))
                            ->join(with(new ProductVariant())->getTable(),'products.product_id','=','product_variants.product_id')
                            ->join(with(new Cart())->getTable(),'carts.product_variant_id','=','product_variants.product_variant_id')
                            ->join(with(new Orders())->getTable(),'orders.cart_id','=','carts.cart_id')
                            ->join(with(new Journey())->getTable(),'journeys.journey_id','=','orders.journey_id')
                            ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
                            ->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
                            ->where('orders.dispensed_yn','Y')
                            ->where('journey_steps.journey_step_name','checkout')
                            ->whereRaw($timeSelect['whereCond']);
                            

        if(!empty($kiosk_id)){
          $productSales = $productSales->whereIn('kiosks.kiosk_id',$kiosk_id);
           $yaxisData = Kiosk::Select('products.product_id','products.product_name')
           						->join(with(new ProductVariant())->getTable(),'kiosks.product_variant_id','=','product_variants.product_variant_id')
           						->join(with(new Product())->getTable(),'products.product_id','=','product_variants.product_id')
           						->whereIn('kiosks.kiosk_id',$kiosk_id)
           						->groupBy('products.product_id')
          						->get()->toArray();
        }elseif(!empty($accountId)){
        	$productSales = $productSales->where('products.account_id',$accountId);
        	$yaxisData = Product::Select('products.product_id','products.product_name')
          					->where('account_id',$accountId)
          					->get()->toArray();
        }
        else{
         
          if(Auth::user()->accountDetails->account_type == 'sub'){
            $act_id = Auth::user()->accountDetails->account_id;
            $parent_id = Auth::user()->accountDetails->account_id_parent;
            $account_id = array($act_id,$parent_id);
          }else{
          	 $account_id = $this->getAccounts();
          }
          $productSales = $productSales->whereIn('kiosks.account_id',$account_id);
          $yaxisData = Product::Select('products.product_id','products.product_name')
          					->whereIn('account_id',$account_id)
          					->get()->toArray();
        }     
       
        //dd(DB::getQueryLog());

        if($type=="list"){
        	 $productSales = $productSales->groupBy('products.product_id')->orderBy('total','desc')->get()->toArray();

        	return $this->getListData($productSales);
        }else{
        	$productSales = $productSales->groupBy('times','products.product_id')->orderBy('times','desc')->get()->toArray();
        	//dd(DB::getQueryLog());
        	$labels = CommonHelper::getDateArrayList($time,$start_date,$end_date);


        	return $this->getChartData($productSales,$yaxisData,$labels,$time);
        }
//dd(DB::getQueryLog());
       

	}

	private function getChartData($productSales,$yaxisData,$labels,$time){
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
        $productIds = array_column($productSales, 'product_id');
        foreach ($yaxisData as $key => $y) {
        	if(in_array($y['product_id'], $productIds)){
        		$color = $this->randomColor();
              $dummydata = array('label'=> $y['product_name'],
                'backgroundColor'=> $color,
                'borderColor'=> $color,
                'stack'=> 'combined',
	            'type' => 'bar',
                'data'=>$data
              );
              $chartData[$y['product_id']] = $dummydata;
        	}
        	
             //array_push($chartData,$dummydata);
        }

        foreach ($productSales as $key => $value) {
    		if($time == 'today'){
    			$needle = $labels[$value['times']];
    		}else{
    			$needle = $value['times'];
    		}
    		$findDataindex = array_search($needle, $yaxis);
    		if($findDataindex !== false){
    			$chartData[$value['product_id']]['data'][$findDataindex] = $value['total'];
    		}
        }
        $chartData = array_values($chartData);
        return array('yaxis' => $yaxis, 'charData' => $chartData);
	}


	private function getListData($productSales){
		$allTotal = array_sum(array_column($productSales, 'total'));
		$listData = array();
		foreach ($productSales as $key => $value) {
			$sp = (100 *  $value['total']) / $allTotal;
			$dummyData = array(
				'product_id' => $value['product_id'],
				'product_name' => $value['product_name'],
				'product_image' => $value['product_image'],
				'total_sale' => $value['total'],
				'sale_percentage' => number_format((float)$sp, 2, '.', '')
			);

			array_push($listData,$dummyData);
		}
		return $listData;
	}	


	public function getCustomerSalesChart($time,$setting,$type,$accountId,$start_date=null,$end_date=null,$kiosk_id=null){
			$timeSelect = $this->getCondition($time,$setting,$start_date,$end_date);

			if($type=="gender"){
				$timeSelect['timeSelect'] = $timeSelect['timeSelect'].",(case when (customers.customer_gender IS NULL or customers.customer_gender = '' or customers.customer_gender = 'O') THEN 'U' else customers.customer_gender end ) as chart_id";
				$yaxisData = array(
					array('name'=>'Male','id'=>'M','color'=>'0,216,189,1'),
					array('name'=>'Female','id'=>'F','color'=>'126,89,249,1'),
					array('name'=>'Unknown','id'=>'U','color'=>'0,143,250,1'),
					array('name'=>'Net Sales','id'=>'T','color'=>'110,110,110,1'),
				);
			}else{
				$timeSelect['timeSelect'] = $timeSelect['timeSelect'].",(case when (customers.customer_age_group IS NULL or customers.customer_age_group = '' ) THEN 'Unknown' else customers.customer_age_group end ) as chart_id";
				$yaxisData = array(
					array('name'=>'Adult','id'=>'Adult','color'=>'126,89,249,1'),
					array('name'=>'Senior','id'=>'Senior','color'=>'0,143,250,1'),
					array('name'=>'Young Adult','id'=>'Young Adult','color'=>'0,216,189,1'),
					array('name'=>'Unknown','id'=>'Unknown','color'=>'255,228,51,1'),
					array('name'=>'Net Sales','id'=>'T','color'=>'110,110,110,1'),
				);
			}
			//DB::enableQueryLog();
		
			$productSales = Customer::Select(DB::raw($timeSelect['timeSelect']))
                            ->join(with(new Journey())->getTable(),'journeys.customer_id','=','customers.customer_id')
                             ->join(with(new JourneyStep())->getTable(),'journeys.journey_id','=','journey_steps.journey__id')
                            ->join(with(new Orders())->getTable(),'orders.journey_id','=','journeys.journey_id')
                            ->join(with(new Kiosk())->getTable(),'kiosks.kiosk_id','=','journeys.kiosk_id')
                            ->where('orders.dispensed_yn','Y')
                            ->where('journey_steps.journey_step_name','checkout')
                            ->whereRaw($timeSelect['whereCond'])
                            ->groupBy('times','chart_id')
                            ->orderBy('times','Desc');
            if(!empty($accountId)){
            	$productSales = $productSales->where('kiosks.account_id',$accountId);
            }else{
            	$account_id = $this->getAccounts();
         	 	$productSales = $productSales->whereIn('kiosks.account_id',$account_id);
            }
             
         	 $productSales = $productSales->get()->toArray();        
         //	 dd(DB::getQueryLog());
               
			$labels = CommonHelper::getDateArrayList($time,$start_date,$end_date);

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
	        	$color = $this->randomColor();
	              $dummydata = array('label'=> $y['name'],
	                'backgroundColor'=>  'rgba('.$y["color"].')',
	                'borderColor'=> 'rgba('.$y["color"].')',
	                'stack'=> 'combined',
	                'data'=>$data
	              );
	              if($y['id'] != 'T'){
	              	$dummydata['type']= 'bar';
	              }else{
	              	$dummydata['fill']= 'false';
	              }
	              $chartData[$y['id']] = $dummydata;
	             //array_push($chartData,$dummydata);
	        }

	        foreach ($productSales as $key => $value) {
	    		if($time == 'today'){
	    			$needle = $labels[$value['times']];
	    		}else{
	    			$needle = $value['times'];
	    		}
	    		$findDataindex = array_search($needle, $yaxis);
	    		if($findDataindex !== false){
	    			$chartData[$value['chart_id']]['data'][$findDataindex] = $value['total'];
	    			$chartData['T']['data'][$findDataindex] = $chartData['T']['data'][$findDataindex] + $value['total'];
	    		}
	        }
	        $chartData = array_values($chartData);
	        return array('yaxis' => $yaxis, 'charData' => $chartData);
			
	}


	function randomColor(){
	    $rand1 = mt_rand(0, 255);
	    $rand2 = mt_rand(0, 255);
	    $rand3 = mt_rand(0, 255);
	    return "rgba(".$rand1.",".$rand2.",".$rand3.", 1)";
	}
}