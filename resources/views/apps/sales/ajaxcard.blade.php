<h2 class="main-content_header mt-3 mb-3">Sales</h2>
<div class="row row-equal-height mb-3">
	<!-- Sales Card -->
    <?php $monthData = $getData['month'];
       // echo "<pre>"; print_r($monthData);echo "</pre>"; die; 
     ?>
	<div class="col-lg-4 col-sm-6 col-12 sale-anylatic">
		<div class="card sale-card card-custom gutter-b example example-compact">
			<div class="card-header align-items-center">
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
            					<span class="<?php echo $spanclass;?>"> <?php echo $icon." ".number_format($monthvisits,2); ?>% </span>
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
                                    if($monthData['visitors']['count']>=$monthData['conversion']['count'] ){
                                        if($monthData['conversion']['count'] != 0){
                                            $monthconversionper = ( (float)$monthData['conversion']['count'] / (float)$monthData['visitors']['count']) * 100;
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
            <div class="card-header align-items-center">
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
                                <span class="<?php echo $spanclass;?>"> <?php echo $icon." ".number_format($monthvisits,2); ?>% </span>
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
            <div class="card-header align-items-center">
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
                                <span class="<?php echo $spanclass;?>"> <?php echo $icon." ".number_format($monthvisits,2); ?>% </span>
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