<h2 class="main-content_header mt-5 mb-3 pb-2">Engagement</h2>
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
                <h4>{{ number_format($monthData['total']) }}</h4>
            	<div class="row">
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Seniors</label>
                        <span class="content-value">
                            <?php if(array_key_exists('seniors', $monthData['data'])): ?>
                                <?php echo number_format($monthData['data']['seniors'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Adults</label>
                        <span class="content-value">
                            <?php if(array_key_exists('adults', $monthData['data'])): ?>
                                <?php echo number_format($monthData['data']['adults'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Young Adults</label>
                        <span class="content-value">
                            <?php if(array_key_exists('young_adults', $monthData['data'])): ?>
                                <?php echo number_format($monthData['data']['young_adults'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Unknown</label>
                        <span class="content-value">
                            <?php if(array_key_exists('unknown', $monthData['data'])): ?>
                                <?php echo number_format($monthData['data']['unknown'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
            	</div>
                <div class="row mt-3">
                    <div class="col-4 gender-card px-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light mr-2">
                                <span class="symbol-labels">
                                    <img src="{{ asset('public/assets/svg/male.svg') }}" class="h-50 align-self-center">
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <span class="text-dark-75 font-size-h6 mb-0">Male</span>
                                <span class="content-value">
                                    <?php if(array_key_exists('M', $monthData['data'])): ?>
                                        <?php echo number_format($monthData['data']['M'],2).'%'; ?>
                                    <?php else: ?>
                                        0.00%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 gender-card px-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light mr-2">
                                <span class="symbol-labels">
                                    <img src="{{ asset('public/assets/svg/female.svg') }}" class="h-50 align-self-center">
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <span class="text-dark-75 font-size-h6 mb-0">Female</span>
                                <span class="content-value">
                                    <?php if(array_key_exists('F', $monthData['data'])): ?>
                                        <?php echo number_format($monthData['data']['F'],2).'%'; ?>
                                    <?php else: ?>
                                        0.00%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 gender-card px-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light mr-2">
                                <span class="symbol-labels">
                                    <img src="{{ asset('public/assets/svg/unknown.svg') }}" class="h-50 align-self-center" width="28">
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <span class="text-dark-75 font-size-h6 mb-0">Unknown</span>
                                <span class="content-value">
                                    <?php if(array_key_exists('U', $monthData['data'])): ?>
                                        <?php echo number_format($monthData['data']['U'],2).'%'; ?>
                                    <?php else: ?>
                                        0.00%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
	
    <!-- Week Sales Card -->
    <?php $weekData = $getData['week']; ?>
    <div class="col-lg-4 col-sm-6 col-12 sale-anylatic">
        <div class="card sale-card card-custom gutter-b example example-compact">
            <div class="card-header align-items-center justify-content-between">
                <h3 class="breakdown-header_heading mb-0">This week</h3>
                    <span class="breakdown-header_indicator month_text"><?php echo date('W') ?></span>
            </div>
            <div class="card-body bg-grey">
                <h4>{{ number_format($weekData['total']) }}</h4>
                <div class="row">
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Seniors</label>
                        <span class="content-value">
                            <?php if(array_key_exists('seniors', $weekData['data'])): ?>
                                <?php echo number_format($weekData['data']['seniors'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Adults</label>
                        <span class="content-value">
                            <?php if(array_key_exists('adults', $weekData['data'])): ?>
                                <?php echo number_format($weekData['data']['adults'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Young Adults</label>
                        <span class="content-value">
                            <?php if(array_key_exists('young_adults', $weekData['data'])): ?>
                                <?php echo number_format($weekData['data']['young_adults'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Unknown</label>
                        <span class="content-value">
                            <?php if(array_key_exists('unknown', $weekData['data'])): ?>
                                <?php echo number_format($monthData['data']['unknown'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4 gender-card px-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light mr-2">
                                <span class="symbol-labels">
                                    <img src="{{ asset('public/assets/svg/male.svg') }}" class="h-50 align-self-center">
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <span class="text-dark-75 font-size-h6 mb-0">Male</span>
                                <span class="content-value">
                                    <?php if(array_key_exists('M', $weekData['data'])): ?>
                                        <?php echo number_format($weekData['data']['M'],2).'%'; ?>
                                    <?php else: ?>
                                        0.00%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 gender-card px-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light mr-2">
                                <span class="symbol-labels">
                                    <img src="{{ asset('public/assets/svg/female.svg') }}" class="h-50 align-self-center">
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <span class="text-dark-75 font-size-h6 mb-0">Female</span>
                                <span class="content-value">
                                    <?php if(array_key_exists('F', $weekData['data'])): ?>
                                        <?php echo number_format($weekData['data']['F'],2).'%'; ?>
                                    <?php else: ?>
                                        0.00%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 gender-card px-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light mr-2">
                                <span class="symbol-labels">
                                    <img src="{{ asset('public/assets/svg/unknown.svg') }}" class="h-50 align-self-center" width="28">
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <span class="text-dark-75 font-size-h6 mb-0">Unknown</span>
                                <span class="content-value">
                                    <?php if(array_key_exists('U', $weekData['data'])): ?>
                                        <?php echo number_format($weekData['data']['U'],2).'%'; ?>
                                    <?php else: ?>
                                        0.00%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Week Sales Card -->
    <?php $todayData = $getData['today']; ?>
    <div class="col-lg-4 col-sm-6 col-12 sale-anylatic">
        <div class="card sale-card card-custom gutter-b example example-compact">
            <div class="card-header align-items-center justify-content-between">
                <h3 class="breakdown-header_heading mb-0">Today</h3>
                    <span class="breakdown-header_indicator month_text"><?php echo date('M d') ?></span>
            </div>
            <div class="card-body bg-grey">
                <h4>{{ number_format($todayData['total']) }}</h4>
                <div class="row">
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Seniors</label>
                        <span class="content-value">
                            <?php if(array_key_exists('seniors', $todayData['data'])): ?>
                                <?php echo number_format($todayData['data']['seniors'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Adults</label>
                        <span class="content-value">
                            <?php if(array_key_exists('adults', $todayData['data'])): ?>
                                <?php echo number_format($todayData['data']['adults'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Young Adults</label>
                        <span class="content-value">
                            <?php if(array_key_exists('young_adults', $todayData['data'])): ?>
                                <?php echo number_format($todayData['data']['young_adults'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-3 px-2">
                        <label class="content-label d-block">Unknown</label>
                        <span class="content-value">
                            <?php if(array_key_exists('unknown', $todayData['data'])): ?>
                                <?php echo number_format($todayData['data']['unknown'],2).'%'; ?>
                            <?php else: ?>
                                0.00%
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4 gender-card px-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light mr-2">
                                <span class="symbol-labels">
                                    <img src="{{ asset('public/assets/svg/male.svg') }}" class="h-50 align-self-center">
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <span class="text-dark-75 font-size-h6 mb-0">Male</span>
                                <span class="content-value">
                                    <?php if(array_key_exists('M', $todayData['data'])): ?>
                                        <?php echo number_format($todayData['data']['M'],2).'%'; ?>
                                    <?php else: ?>
                                        0.00%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 gender-card px-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light mr-2">
                                <span class="symbol-labels">
                                    <img src="{{ asset('public/assets/svg/female.svg') }}" class="h-50 align-self-center">
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <span class="text-dark-75 font-size-h6 mb-0">Female</span>
                                <span class="content-value">
                                    <?php if(array_key_exists('F', $todayData['data'])): ?>
                                        <?php echo number_format($todayData['data']['F'],2).'%'; ?>
                                    <?php else: ?>
                                        0.00%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 gender-card px-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light mr-2">
                                <span class="symbol-labels">
                                    <img src="{{ asset('public/assets/svg/unknown.svg') }}" class="h-50 align-self-center" width="28">
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <span class="text-dark-75 font-size-h6 mb-0">Unknown</span>
                                <span class="content-value">
                                    <?php if(array_key_exists('U', $todayData['data'])): ?>
                                        <?php echo number_format($todayData['data']['U'],2).'%'; ?>
                                    <?php else: ?>
                                        0.00%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	

</div>