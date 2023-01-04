@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/custom/datepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/custom/chartjs/Chart.min.css') }}" />
@endsection

@section('content')
<div class="row">
	<div class="col-12 text-center">
		<h2 class="font-weight-normal">How are sales looking?</h2>
		<h1 class="mb-3">Dive into your Sales Analytics.</h1>
		
	</div>
</div>
<div class="row">
    <?php if($accType == 'ent'): ?>
        <div class="col-6">
            <span class="d-block mb-3">Select Sub Account </span>
            <select form="exportFrm" class="form-control select-box" name="accountId" id="subaccount">
                <option value="">Select Account</option>
                <?php if($subAccounts->count() > 0): ?>
                    <?php 
                        foreach ($subAccounts as $key => $acct) {
                            echo "<option value='".$acct->account_id."'>".$acct->account_name."</option>";
                        } 
                    ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-6">
            <span class="d-block mb-3">Select Machine </span>
            <select name="kiosk_id" form="exportFrm" id="kiosk_id" class="form-control select-box">
               <option value="">Select Machines</option>
               <option value="">All Machines</option>
            </select>
        </div>

    <?php else: ?>
        <div class="col-6 text-center">
            <div class="form-group">
                <select name="kiosk_id" id="kiosk_id" class="form-control select-box" required="">
                <option value="">All Machines</option>
                @foreach ($getMachine as $item)
                <option value="{{$item->kiosk_id}}">{{$item->kiosk_identifier}}</option>
                @endforeach                            
            </select>
            </div>
        </div>
    <?php endif; ?>
    
</div>
<div id="loader">
  <div class="d-flex justify-content-center align-items-center h-100">
    <div class="spinner-border text-light" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
</div>
<input type="hidden" id="_t" value="{{ csrf_token() }}">
<div id="home-data">
<h2 class="main-content_header mt-3 mb-3 pb-3">Sales</h2>
<div class="row row-equal-height mb-3">
	<!-- Sales Card -->
    <?php $monthData = $getData['month'];
    //echo "<pre>"; print_r($monthData);echo "</pre>"; 
     ?>
	<div class="col-lg-4 col-sm-6 col-12 sale-anylatic">
		<div class="card sale-card card-custom gutter-b example example-compact">
			<div class="card-header align-items-center justify-content-between">
				<h3 class="breakdown-header_heading mb-0">This month</h3>
                    <span class="breakdown-header_indicator month_text"><?php echo date('M') ?></span>
			</div>
            <div class="card-body bg-grey">
            	<div class="row">
            		<div class="col-lg-7">
            			<div class="d-flex justify-content-between mb-4">
            				<span class="price">$<?php echo number_format($monthData['salesData']['saleData']['sales_total'],2); ?></span>
            				<span class="lastMonth">
                                <?php if($monthData['salesData']['saleData']['sales_total'] != 0){
                                    if($monthData['salesData']['saleData']['sales_prevTotal'] != 0){
                                        $monthsale = ((float)$monthData['salesData']['saleData']['sales_total'] / (float)$monthData['salesData']['saleData']['sales_prevTotal']) * 100;
                                        $monthsale = $monthsale - 100;
                                    }else{
                                        $monthsale = 100;
                                    }

                                }else{
                                    $monthsale = 0;
                                } 

                                $spanclass         = $monthsale<=0?"is-negative":"is-positive";
                                $icon  = $monthsale<=0 ? '<i class="fas fa-arrow-down"></i>':'<i class="fas fa-arrow-up"></i>';
                                ?>
                                <span class="{{ $spanclass }}"><?php echo $icon.' '.number_format($monthsale,2); ?>%</span>
                            </span>
            			</div>
            			<div class="d-flex justify-content-between">
            				<div class="vistorPer mr-2">
            					<label>Visitor</label>
                                 <?php if($monthData['visitors']['count'] != 0){
                                    if($monthData['visitors']['prevcount'] != 0){
                                        $monthvisits = ((float)$monthData['visitors']['count'] / (float)$monthData['visitors']['prevcount']) * 100;
                                        $monthvisits = $monthvisits - 100;
                                    }else{
                                       $monthvisits = ((float)$monthData['visitors']['count']) * 100;
                                    }

                                }elseif($monthData['visitors']['count'] == 0 && $monthData['visitors']['prevcount'] != 0){
                                    $monthvisits = ((float)$monthData['visitors']['prevcount']) * 100;
                                    $monthvisits = '-'.$monthvisits;
                                }else{
                                    $monthvisits = 0;
                                } 

                                $spanclass         = $monthvisits<=0?"is-negative":"is-positive";
                                $icon  = $monthvisits<=0 ? '<i class="fas fa-arrow-down"></i>':'<i class="fas fa-arrow-up"></i>';
                                ?>
            					<span class="<?php echo $spanclass;?>"> <?php echo $icon." ".number_format($monthvisits,2); ?>% </span>
            				</div>
            				<div class="conversionPer">
            					<label>Conversions</label>
                                <?php if($monthData['conversion']['count'] != 0){
                                    if($monthData['conversion']['prevcount'] != 0){
                                        $monthconversion = ((float)$monthData['conversion']['count'] / (float)$monthData['conversion']['prevcount']) * 100;
                                        $monthconversion = $monthconversion - 100;
                                    }else{
                                       $monthconversion = ((float)$monthData['conversion']['count']) * 100;
                                    }

                                }elseif($monthData['conversion']['count'] == 0 && $monthData['conversion']['prevcount'] != 0){
                                    $monthconversion = ((float)$monthData['conversion']['prevcount']) * 100;
                                    $monthconversion = '-'.$monthconversion;
                                }else{
                                    $monthconversion = 0;
                                } 

                                $spanclass         = $monthconversion<=0?"is-negative":"is-positive";
                                $icon  = $monthconversion<=0 ? '<i class="fas fa-arrow-down"></i>':'<i class="fas fa-arrow-up"></i>';
                                ?>
            					<span class="<?php echo $spanclass;?>"> <?php echo $icon." ".number_format($monthconversion,2); ?>% </span>
            				</div>
            			</div>
            		</div>
            		<div class="col-lg-5">
            			<div class="d-flex align-items-center mb-4">
                            <div class="icon w-100">
                                <img src="{{ asset('images/visitors.svg') }}" width="30px" />
                            </div>
							<div class="d-flex flex-column font-weight-bold conversionRate">
								<span class="">Visitors</span>
								<span class="vistiorCount">
                                    <?php echo number_format($monthData['visitors']['count']);
                                        ?>
                                </span>
							</div>
						</div>
						<div class="d-flex align-items-center">
							<div class="icon w-100"><img src="{{ asset('images/conversions.svg') }}" width="28px"/></div>
							<div class="d-flex flex-column font-weight-bold conversionRate">
								<span class="">Conversion</span>
								<span class="vistiorCount">
                                <?php if($monthData['visitors']['count'] != 0 || $monthData['conversion']['count'] != 0){
                                    if($monthData['visitors']['count'] >= $monthData['conversion']['count'] ){
                                        if($monthData['conversion']['count'] != 0){
                                            $monthconversionper = ( (float)$monthData['conversion']['count'] / (float)$monthData['visitors']['count']) * 100;
                                            //echo "VVVVVVVVV=>".$monthconversionper;

                                        }else{
                                            $monthconversionper = 100;
                                        }
                                        
                                    }else{
                                       if($monthData['visitors']['count'] != 0){
                                            $monthconversionper = ( (float)$monthData['visitors']['count'] / (float)$monthData['conversion']['count']) * 100;
                                            $monthconversionper = "-".$monthconversionper;
                                        }else{
                                            $monthconversionper = -100;
                                        }
                                    }

                                }else{
                                    $monthconversionper = 0;
                                } 

                                    echo number_format($monthconversionper,2,".","")."%";
                                ?>

                                </span>
							</div>
						</div>
            		</div>
            	</div>
            </div>
        </div>
	</div>
	<!-- End Sales Card -->
     <?php $weekData = $getData['week'];
        //print_r($weekData);
      ?>
    <div class="col-lg-4 col-sm-6 col-12 sale-anylatic">
        <div class="card sale-card card-custom gutter-b example example-compact">
            <div class="card-header align-items-center justify-content-between">
                <h3 class="breakdown-header_heading mb-0">This Week</h3>
                    <span class="breakdown-header_indicator month_text"><?php echo date('W'); ?></span>
            </div>
            <div class="card-body bg-grey">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="price">$<?php echo number_format($weekData['salesData']['saleData']['sales_total'],2); ?></span>
                            <span class="lastMonth">
                                <?php if($weekData['salesData']['saleData']['sales_total'] != 0){
                                    if($weekData['salesData']['saleData']['sales_prevTotal'] != 0){
                                        $monthsale = ((float)$weekData['salesData']['saleData']['sales_total'] / (float)$weekData['salesData']['saleData']['sales_prevTotal']) * 100;
                                        $monthsale = $monthsale - 100;
                                    }else{
                                        $monthsale = 100;
                                    }

                                }else{
                                    $monthsale = 0;
                                } 

                                $spanclass         = $monthsale<=0?"is-negative":"is-positive";
                                $icon  = $monthsale<=0 ? '<i class="fas fa-arrow-down"></i>':'<i class="fas fa-arrow-up"></i>';
                                ?>
                                <span class="{{ $spanclass }}"><?php echo $icon.' '.number_format($monthsale,2); ?>%</span>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="vistorPer mr-2">
                                <label>Visitor</label>
                                 <?php if($weekData['visitors']['count'] != 0){
                                    if($weekData['visitors']['prevcount'] != 0){
                                        $monthvisits = ((float)$weekData['visitors']['count'] / (float)$weekData['visitors']['prevcount']) * 100;
                                        $monthvisits = $monthvisits - 100;
                                    }else{
                                       $monthvisits = ((float)$weekData['visitors']['count']) * 100;
                                    }

                                }elseif($weekData['visitors']['count'] == 0 && $weekData['visitors']['prevcount'] != 0){
                                    $monthvisits = ((float)$weekData['visitors']['prevcount']) * 100;
                                    $monthvisits = '-'.$monthvisits;
                                }else{
                                    $monthvisits = 0;
                                } 

                                $spanclass         = $monthvisits<=0?"is-negative":"is-positive";
                                $icon  = $monthvisits<=0 ? '<i class="fas fa-arrow-down"></i>':'<i class="fas fa-arrow-up"></i>';
                                ?>
                                <span class="<?php echo $spanclass;?>"> <?php echo $icon." ".number_format($monthvisits,2); ?>% </span>
                            </div>
                            <div class="conversionPer">
                                <label>Conversions</label>
                                <?php if($weekData['conversion']['count'] != 0){
                                    if($weekData['conversion']['prevcount'] != 0){
                                        $monthconversion = ((float)$weekData['conversion']['count'] / (float)$weekData['conversion']['prevcount']) * 100;
                                        $monthconversion = $monthconversion - 100;
                                    }else{
                                       $monthconversion = ((float)$weekData['conversion']['count']) * 100;
                                    }

                                }elseif($weekData['conversion']['count'] == 0 && $weekData['conversion']['prevcount'] != 0){
                                    $monthconversion = ((float)$weekData['conversion']['prevcount']) * 100;
                                    $monthconversion = '-'.$monthconversion;
                                }else{
                                    $monthconversion = 0;
                                } 

                                $spanclass         = $monthconversion<=0?"is-negative":"is-positive";
                                $icon  = $monthconversion<=0 ? '<i class="fas fa-arrow-down"></i>':'<i class="fas fa-arrow-up"></i>';
                                ?>
                                <span class="<?php echo $spanclass;?>"> <?php echo $icon." ".number_format($monthconversion,2); ?>% </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon w-100">
                                <img src="{{ asset('images/visitors.svg') }}" width="30px" />
                            </div>
                            <div class="d-flex flex-column font-weight-bold conversionRate">
                                <span class="">Visitors</span>
                                <span class="vistiorCount">
                                    <?php echo number_format($weekData['visitors']['count']);
                                        ?>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon w-100"><img src="{{ asset('images/conversions.svg') }}" width="28px"/></div>
                            <div class="d-flex flex-column font-weight-bold conversionRate">
                                <span class="">Conversion</span>
                                <span class="vistiorCount">
                                <?php if($weekData['visitors']['count'] != 0 || $weekData['conversion']['count'] != 0){
                                    if($weekData['visitors']['count']>=$weekData['conversion']['count'] ){
                                        if($weekData['conversion']['count'] != 0){
                                            $monthconversionper = ( (float)$weekData['conversion']['count'] / (float)$weekData['visitors']['count']) * 100;
                                        }else{
                                            $monthconversionper = 100;
                                        }
                                        
                                    }else{
                                       if($weekData['visitors']['count'] != 0){
                                            $monthconversionper = ( (float)$weekData['visitors']['count'] / (float)$weekData['conversion']['count']) * 100;
                                            $monthconversionper = "-".$monthconversionper;
                                        }else{
                                            $monthconversionper = -100;
                                        }
                                    }

                                }else{
                                    $monthconversionper = 0;
                                } 

                                    echo number_format($monthconversionper,2,".","")."%";
                                ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Sales Card -->
    <?php $todayData = $getData['today']; ?>
    <div class="col-lg-4 col-sm-6 col-12 sale-anylatic">
        <div class="card sale-card card-custom gutter-b example example-compact">
            <div class="card-header align-items-center justify-content-between">
                <h3 class="breakdown-header_heading mb-0">Today</h3>
                    <span class="breakdown-header_indicator month_text"><?php echo date('M d'); ?></span>
            </div>
            <div class="card-body bg-grey">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="price">$<?php echo number_format($todayData['salesData']['saleData']['sales_total'],2); ?></span>
                            <span class="lastMonth">
                                <?php if($todayData['salesData']['saleData']['sales_total'] != 0){
                                    if($todayData['salesData']['saleData']['sales_prevTotal'] != 0){
                                        $monthsale = ((float)$todayData['salesData']['saleData']['sales_total'] / (float)$todayData['salesData']['saleData']['sales_prevTotal']) * 100;
                                        $monthsale = $monthsale - 100;
                                    }else{
                                        $monthsale = 100;
                                    }

                                }else{
                                    $monthsale = 0;
                                } 

                                $spanclass         = $monthsale<=0?"is-negative":"is-positive";
                                $icon  = $monthsale<=0 ? '<i class="fas fa-arrow-down"></i>':'<i class="fas fa-arrow-up"></i>';
                                ?>
                                <span class="{{ $spanclass }}"><?php echo $icon.' '.number_format($monthsale,2); ?>%</span>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="vistorPer mr-2">
                                <label>Visitor</label>
                                 <?php if($todayData['visitors']['count'] != 0){
                                    if($todayData['visitors']['prevcount'] != 0){
                                        $monthvisits = ((float)$todayData['visitors']['count'] / (float)$todayData['visitors']['prevcount']) * 100;
                                        $monthvisits = $monthvisits - 100;
                                    }else{
                                       $monthvisits = ((float)$todayData['visitors']['count']) * 100;
                                    }

                                }elseif($todayData['visitors']['count'] == 0 && $todayData['visitors']['prevcount'] != 0){
                                    $monthvisits = ((float)$todayData['visitors']['prevcount']) * 100;
                                    $monthvisits = '-'.$monthvisits;
                                }else{
                                    $monthvisits = 0;
                                } 

                                $spanclass         = $monthvisits<=0?"is-negative":"is-positive";
                                $icon  = $monthvisits<=0 ? '<i class="fas fa-arrow-down"></i>':'<i class="fas fa-arrow-up"></i>';
                                ?>
                                <span class="<?php echo $spanclass;?>"> <?php echo $icon." ".number_format($monthvisits,2); ?>% </span>
                            </div>
                            <div class="conversionPer">
                                <label>Conversions</label>
                                <?php if($todayData['conversion']['count'] != 0){
                                    if($todayData['conversion']['prevcount'] != 0){
                                        $monthconversion = ((float)$todayData['conversion']['count'] / (float)$todayData['conversion']['prevcount']) * 100;
                                        $monthconversion = $monthconversion - 100;
                                    }else{
                                       $monthconversion = ((float)$todayData['conversion']['count']) * 100;
                                    }

                                }elseif($todayData['conversion']['count'] == 0 && $todayData['conversion']['prevcount'] != 0){
                                    $monthconversion = ((float)$todayData['conversion']['prevcount']) * 100;
                                    $monthconversion = '-'.$monthconversion;
                                }else{
                                    $monthconversion = 0;
                                } 

                                $spanclass         = $monthconversion<=0?"is-negative":"is-positive";
                                $icon  = $monthconversion<=0 ? '<i class="fas fa-arrow-down"></i>':'<i class="fas fa-arrow-up"></i>';
                                ?>
                                <span class="<?php echo $spanclass;?>"> <?php echo $icon." ".number_format($monthconversion,2); ?>% </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon w-100">
                                <img src="{{ asset('images/visitors.svg') }}" width="30px" />
                            </div>
                            <div class="d-flex flex-column font-weight-bold conversionRate">
                                <span class="">Visitors</span>
                                <span class="vistiorCount">
                                    <?php echo number_format($todayData['visitors']['count']);
                                        ?>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon w-100"><img src="{{ asset('images/conversions.svg') }}" width="28px"/></div>
                            <div class="d-flex flex-column font-weight-bold conversionRate">
                                <span class="">Conversion</span>
                                <span class="vistiorCount">
                                <?php if($todayData['visitors']['count'] != 0 || $todayData['conversion']['count'] != 0){
                                    if($todayData['visitors']['count']>=$todayData['conversion']['count'] ){
                                        if($todayData['conversion']['count'] != 0){
                                            $monthconversionper = ( (float)$todayData['conversion']['count'] / (float)$todayData['visitors']['count']) * 100;
                                        }else{
                                            $monthconversionper = 100;
                                        }
                                        
                                    }else{
                                       if($todayData['visitors']['count'] != 0){
                                            $monthconversionper = ( (float)$todayData['visitors']['count'] / (float)$todayData['conversion']['count']) * 100;
                                            $monthconversionper = "-".$monthconversionper;
                                        }else{
                                            $monthconversionper = -100;
                                        }
                                    }

                                }else{
                                    $monthconversionper = 0;
                                } 

                                    echo number_format($monthconversionper,2,".","")."%";
                                ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Sales Card -->
	

</div>
</div>
<div class="row align-items-center">
    <div class="col-5">
         <div class="form-group">
            <span class="d-block mb-3">Select a date range </span>
            <div class="col-12 p-0">
             <input type="text" class="form-control text-center" autocomplete="off" name="daterange" value="" placeholder="Select Dates" />
            </div>
        </div>      
    </div>
    <div class="col-6">
        <div class="button-group mt-lg-5">
            <span class="d-block mb-3">&nbsp; </span>
            <button type="button" class="btn btn-select dropdown-toggle" data-toggle="dropdown"><span class="machine-label">All Machine</span> <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <?php $machine = $getMachine->toArray(); ?>
                <?php 
                    foreach ($machine as $key => $val) {
                        //echo "<option value='".$val['kiosk_id']."'>".$val['kiosk_identifier']."</option>";
                        echo '<li><a href="#" class="small" data-value="'.$val['kiosk_id'].'" tabIndex="-1"><input type="checkbox"/>&nbsp; <span>'.$val['kiosk_identifier'].'</span></a></li>';
                    } 
                ?>
            </ul>
          </div>
    </div>
    <input type="hidden" id="kiosk_ids" />
    <input type="hidden" id="_t" value="{{ csrf_token() }}">
</div>

<div class="row mt-4">
    <div class="col-12">
         <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-9">
                        <h4>Total Sales</h4>
                        <canvas id="saleChart"></canvas>
                        <div class="mt-4 text-center">
                            <h4 class="saleChartLabel">Today</h4>
                        </div>
                    </div>
                    <div class="col-lg-3 mt-5 pt-lg-5">
                        <div class="allmachineTotal mb-4"></div>
                        <div class="selectedmachines"></div>
                    </div>
                </div>
            </div>
         </div>
    </div>
</div>

<div class="row mt-4 row-equal-height">
    <div class="col-6">
        <div class="row align-items-center">
            <div class="col-6">
                <div class="form-group">
                    <span class="d-block mb-3">Select a date range </span>
                    <div class="col-12 p-0">
                     <input type="text" class="form-control daterange text-center" autocomplete="off" id="productSalePicker" value="" placeholder="Select Dates" />
                    </div>
                </div> 
            </div>
            <div class="col-6">
                <span class="d-block mb-3">Select a period </span>
                <div class="has-select form-group">
                    <select class="select-box form-control" id="product-datePeriod">
                        <option selected value="today">Today</option>
                        <option value="week">This week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                        <option value="lastyear">Last Year</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
             <div class="card card-custom gutter-b example example-compact">
                <div class="card-body">
                    <h4>Total Sales (by date)</h4>
                    <div class="row">
                        <div class="col-12 p-0">
                        <canvas id="productSaleChart"></canvas>
                         <div class="mt-4 text-center">
                            <h4 class="productSaleChartLabel">Today</h4>
                         </div>
                        </div>
                    </div>
                </div>
             </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="row align-items-center">
            <div class="col-6">
                <div class="form-group">
                    <span class="d-block mb-3">Select a date range </span>
                    <div class="col-12 p-0">
                     <input type="text" class="form-control daterange text-center" autocomplete="off" id="productListPicker" value="" placeholder="Select Dates" />
                    </div>
                </div> 
            </div>
            <div class="col-6">
                <span class="d-block mb-3">Select a period </span>
                <div class="has-select form-group">
                    <select class="select-box form-control" id="productlist-datePeriod">
                        <option selected value="today">Today</option>
                        <option value="week">This week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                        <option value="lastyear">Last Year</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
             <div class="card card-custom gutter-b example example-compact">
                <div class="card-body">
                    <h4>Total Sales (per product)</h4>
                    <div class="table table-responsive">
                        <table class="table" id="listTable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Sales($)</th>
                                    <th>SalesMix %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listData as $key => $value) { ?>
                                   
                                    <tr>
                                        <td><span class="produt_img"><img src="{{ $value['product_image'] }}" /></span>{{ $value['product_name'] }}</td>
                                        <td>${{ number_format($value['total_sale'],2) }}</td>
                                        <td>{{ $value['sale_percentage'] }}%</td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
             </div>
            </div>
        </div>
    </div>
</div>


<div class="row mt-4 row-equal-height">
    <div class="col-6">
        <div class="row align-items-center">
            <div class="col-6">
                <div class="form-group">
                    <span class="d-block mb-3">Select a date range </span>
                    <div class="col-12 p-0">
                     <input type="text" class="form-control daterange text-center" autocomplete="off" id="genderSalePicker" value="" placeholder="Select Dates" />
                    </div>
                </div> 
            </div>
            <div class="col-6">
                <span class="d-block mb-3">Select a period </span>
                <div class="has-select form-group">
                    <select class="select-box form-control" id="gender-datePeriod">
                        <option selected value="today">Today</option>
                        <option value="week">This week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                        <option value="lastyear">Last Year</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
             <div class="card card-custom gutter-b example example-compact">
                <div class="card-body">
                    <h4>Sales By Gender</h4>
                    <div class="row">
                        <div class="col-12 p-0">
                            <canvas id="saleGenderChart"></canvas>
                            <div class="mt-4 text-center">
                                <h4 class="saleGenderLabel">Today</h4>
                             </div>
                        </div>
                    </div>
                </div>
             </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="row align-items-center">
            <div class="col-6">
                <div class="form-group">
                    <span class="d-block mb-3">Select a date range </span>
                    <div class="col-12 p-0">
                     <input type="text" class="form-control daterange text-center" autocomplete="off" id="ageGroupPicker" value="" placeholder="Select Dates" />
                    </div>
                </div> 
            </div>
            <div class="col-6">
                <span class="d-block mb-3">Select a period </span>
                <div class="has-select form-group">
                    <select class="select-box form-control" id="agegroup-datePeriod">
                        <option selected value="today">Today</option>
                        <option value="week">This week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                        <option value="lastyear">Last Year</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
             <div class="card card-custom gutter-b example example-compact">
                <div class="card-body">
                    <h4>Sales By Age Group</h4>
                    <div class="row">
                        <div class="col-12 p-0">
                            <canvas id="saleAgeGroupChart"></canvas>
                            <div class="mt-4 text-center">
                                <h4 class="saleAgeGroupLabel">Today</h4>
                             </div>
                        </div>
                    </div>
                </div>
             </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
    <script src="{{ asset('plugins/custom/datepicker/moment.min.js') }}" ></script>
    <script src="{{ asset('plugins/custom/datepicker/daterangepicker.js') }}" ></script>
    <script src="{{ asset('plugins/custom/chartjs/chart.min.js') }}" ></script>

    <script src="{{ asset('js/account/saleChart.js') }}?v={{ config('constants.WEB_VERSION') }}" ></script>
@endsection

