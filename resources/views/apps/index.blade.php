@extends('layout.default')

{{-- Styles Section --}}
@section('styles')

@endsection

@section('content')
	@php
		$accountType = Auth::user()->accountSetting()->first();
		$settingFlag = 0;
		if($accType == 'ent') {
			if($accountType->main_setting_yn == 'Y'){
				$settingFlag = 1;
			}
		} else {
			if($accountType->main_setting_yn == 'Y' && $accountType->receipt_setting_yn == 'Y'){
				$settingFlag = 1;
			}
		}
		// dd(Auth::user()->accountSetting->main_setting_yn);
	@endphp

	@if ($settingFlag == 0)
		<div class="row">
			<div class="col-12 col-xl-9 mx-auto text-center mt-5">
				<h2 class="mb-4">Your profile is almost active.</h2>
				<h6 class="mb-5">There are a few important things you need to complete before you can start using your account. Until then, functions that affect the platform are blocked.</h6>

				<div class="form-group">
					@if($accType == 'ent')
						<a href="{{ url('app/setting') }}"><button type="button" class="btn btn-primary mr-2">Update Settings</button></a>
					@else
						<a href="{{ url('app/setting') }}"><button type="button" class="btn btn-primary mr-2">Update Settings</button></a>
						<a href="{{ url('app/setting/receipt') }}"><button type="button" class="btn btn-primary">Update Receipt Settings</button></a>

					@endif

				</div>
			</div>
		</div>
	@else

	<div class="row">
		<div class="col-xl-12 col-lg-12">
			<div class="row justify-content-between">
				<div class="col-lg-8 col-12">
					<div class="d-flex home-msg align-items-center flex-column flex-lg-row flex-md-row">
						<h2 class="timemsg font-weight-normal mr-3"></h2>
						<h1 class="mb-2 business-msg">How's business {{ Auth::user()->accountDetails->account_name }}?</h1>
					</div>
				</div>
				<div class="col-lg-4 text-right col-12 text-md-center text-lg-right">
					<div class="form-group">
						<label>Here's at quick look for</label>
						<select class="ml-4 select-box" id="data-time">
							<option value="today">Today</option>
							<option value="yesterday">Yesterday</option>
							<option value="week">This Week</option>
							<option value="month">This Month</option>
						</select>
					</div>
				</div>
			</div>


		</div>
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
		<div class="row row-equal-height mb-3">
			<div class="col-xl-8 col-lg-12">
				<div class="row">
					<!-- Sales Card -->
				      <div class="mb-3 col-sm-6 col-12 dashboard-anylatic">
						<div class="card card-custom  example example-compact">
							 <div class="card-header">
					            <h4 class="d-flex justify-content-between align-items-center block-title"><span>Sales</span> <a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="A completed sale"><i class="fas fa-info-circle"></i></a></h4>
					          </div>
							<div class="card-body d-flex flex-column">
								<div class="home-block">
								<div class="row">
									<div class="col-md-6">
				                        <p class="mb-1"><strong>All Machines</strong></p>
				                        <div class="mb-3">
											<span class="price mb-0 mr-3">$<?php echo number_format($salesData['saleData']['sales_total'], 2); ?></span>
											<?php $diffPrice = ($salesData['saleData']['sales_total'] - $salesData['saleData']['sales_prevTotal']);
												$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
											?>
											<span class="prevPrice"><i class="fas <?php echo $caretIcon ?>"></i> $<?php echo number_format($diffPrice,2); ?></span>
										</div>
									</div>
									<div class="col-md-6">
											<p class="mb-1"><strong>Top Machine</strong></p>
											<?php $machinName = (!empty($salesData['saleData']['machine_name'])) ? $salesData['saleData']['machine_name'] : '-';
												$machineSalePrice =$salesData['saleData']['machine_total'];
												$machinePrevSalePrice = $salesData['saleData']['machine_prevtotal'];
											?>
											<p class="machinName mb-1"><strong><?php echo $machinName; ?></strong></p>
											<div class="mb-3">
												<span class="price mb-0 mr-3">$<?php echo number_format($machineSalePrice, 2); ?></span>
												<?php $diffPrice = ($machineSalePrice - $machinePrevSalePrice);
													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
												<span class="prevPrice mb-0"><i class="fas <?php echo $caretIcon ?>"></i> $<?php echo number_format($diffPrice,2); ?></span>
											</div>
										</div>
				                    </div>

								</div> <!-- Home block -->
								<div class="mt-auto ml-auto">
									<a href="{{ url('/app/sales/analytics') }}" class="btn btn-explore"><img src="{{ asset('images/home/icon-nav-sales@1.5x.svg') }}" class="mr-2"/> Explore</a>
								</div>
							</div>
						</div>
					</div>
				    <!-- End Sales Card -->

				    <!-- Sales Conversion Rate Card -->
					<div class="mb-3 col-sm-6 col-12 dashboard-anylatic">
						<div class="card card-custom example example-compact">
							<div class="card-header">
					            <h4 class="d-flex justify-content-between align-items-center block-title"><span>Conversion Rate</span> <a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="#sales/#visitors – indicator of product interest & POS experience"><i class="fas fa-info-circle"></i></a></h4>
					          </div>
							<div class="card-body  d-flex flex-column">
								<div class="home-block">
									<div class="row">
										<div class="col-sm-6">
											<p class="mb-0"><strong>All Machines</strong></p>
											<div class="mb-3">
												<span class="price mb-0 mr-3"><?php echo $salesData['salesConversionData']['sales_rate']; ?>%</span>
												<?php $diffPrice = ($salesData['salesConversionData']['sales_rate'] - $salesData['salesConversionData']['sales_prevRate']);
													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';

													//echo $diffPrice;
												?>
												<span class="prevPrice"><i class="fas <?php echo $caretIcon ?>"></i><?php echo $diffPrice; ?>%</span>
											</div>
										</div>
										<div class="col-sm-6">
											<p class="mb-1"><strong>Top Machine</strong></p>
											<?php $machinName = (!empty($salesData['salesConversionData']['machine_name'])) ? $salesData['salesConversionData']['machine_name'] : '-';
												$machineSalePrice = $salesData['salesConversionData']['machine_rate'];
												$machinePrevSalePrice = $salesData['salesConversionData']['machine_prevrate'];
											?>
											<p class="machinName mb-1"><strong><?php echo $machinName; ?></strong></p>
											<div class="mb-3">
												<span class="price mb-0 mr-3"><?php echo round($machineSalePrice,2); ?>%</span>
												<?php $diffPrice = ($machineSalePrice - $machinePrevSalePrice);
													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
												<span class="prevPrice mb-0"><i class="fas <?php echo $caretIcon ?>"></i> <?php echo round($diffPrice,2); ?>%</span>
											</div>
										</div>
									</div>


								</div> <!-- Home block -->
								<div class="mt-auto ml-auto">
									<a href="{{ url('/app/sales/analytics') }}" class="btn btn-explore"><img src="{{ asset('images/home/icon-nav-sales@1.5x.svg') }}" class="mr-2" /> Explore</a>
								</div>
							</div>
						</div>
					</div>
					<!-- End Sales Conversion Rate Card -->

					<!-- Engagement Rate Card -->
					<div class="mb-3 col-sm-6 col-12 dashboard-anylatic">
						<div class="card card-custom example example-compact">
							<div class="card-header">
								<h4 class="d-flex justify-content-between align-items-center block-title"><span>Engagement Rate</span> <a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="#visitors/#views – indicator of product interest"><i class="fas fa-info-circle"></i></a></h4>
							</div>
							<div class="card-body p-4 d-flex flex-column">
								<div class="home-block">
									<div class="row">
										<div class="col-sm-6">
											<p class="mb-0"><strong>All Machines</strong></p>
											<div class="mb-3">
												<span class="price mb-0 mr-3">{{ number_format($visitorData['count'] * 100 / (count($viewsData['passers']) == 0 ? 1 : count($viewsData['passers']))) }}%</span>
												<?php
													// $diffPrice = ($salesData['salesConversionData']['sales_rate'] - $salesData['salesConversionData']['sales_prevRate']);
													$diffPrice = 0;
													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
{{--												<span class="prevPrice"><i class="fas <?php echo $caretIcon ?>"></i>0%</span>--}}
											</div>
										</div>
										<div class="col-sm-6">
											<p class="mb-1"><strong>Top Machine</strong></p>
											<p class="machinName mb-1"><strong><?php echo '-'; ?></strong></p>
											<div class="mb-3">
												<span class="price mb-0  mr-3">{{ number_format($visitorData['count'] * 100 / (count($viewsData['top_passers']) == 0 ? 1 : count($viewsData['top_passers']))) }}%</span>
												<?php
													// $diffPrice = ($machineSalePrice - $machinePrevSalePrice);
													$diffPrice = 0;
													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
{{--												<span class="prevPrice mb-0"><i class="fas <?php echo $caretIcon ?>"></i> <?php echo $diffPrice; ?>%</span>--}}
											</div>
										</div>
									</div>
								</div>
								<div class="mt-auto ml-auto">
									<a href="{{ url('/app/visitors/analytics') }}" class="btn btn-explore mt-auto"><img src="{{ asset('images/home/icon-nav-visitors@1.5x.svg') }}" class="mr-2" /> Explore</a>
								</div>
							</div>
						</div>
					</div>
					<!-- End Sales Conversion Rate Card -->

					<!-- Attraction Rate Card -->
					<div class="mb-3 col-sm-6 col-12 dashboard-anylatic">
						<div class="card card-custom example example-compact">
							<div class="card-header">
								<h4 class="d-flex justify-content-between align-items-center block-title"><span>Attraction Rate</span> <a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="#views/#impressions – indicator of ad success"><i class="fas fa-info-circle"></i></a></h4>
							</div>
							<div class="card-body p-4 d-flex flex-column">
								<div class="home-block">
									<div class="row">
										<div class="col-sm-6">
											<p class="mb-0"><strong>All Machines</strong></p>
											<div class="mb-3">
												<span class="price mb-0 mr-3">{{ number_format(count($viewsData['lookers']) * 100 / (count($viewsData['passers']) == 0 ? 1 : count($viewsData['passers']))) }}%</span>
												<?php
													// $diffPrice = ($salesData['salesConversionData']['sales_rate'] - $salesData['salesConversionData']['sales_prevRate']);
													$diffPrice = 0;
													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
{{--												<span class="prevPrice"><i class="fas <?php echo $caretIcon ?>"></i>0%</span>--}}
											</div>
										</div>
										<div class="col-sm-6">
											<p class="mb-1"><strong>Top Machine</strong></p>
											<p class="machinName mb-1"><strong><?php echo '-'; ?></strong></p>
											<div class="mb-3">
												<span class="price mb-0 mr-3">{{ number_format(count($viewsData['top_lookers']) * 100 / (count($viewsData['top_passers']) == 0 ? 1 : count($viewsData['top_passers']))) }}%</span>
												<?php
													// $diffPrice = ($machineSalePrice - $machinePrevSalePrice);
													$diffPrice = 0;
													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
{{--												<span class="prevPrice mb-0"><i class="fas <?php echo $caretIcon ?>"></i> <?php echo $diffPrice; ?>%</span>--}}
											</div>
										</div>
									</div>
								</div>
								<div class="mt-auto ml-auto">
								<a href="{{ url('/app/traffic-analytics') }}" class="btn btn-explore"><img src="{{ asset('images/home/icon-nav-trafficanalysis@1.5x.svg') }}" class="mr-2" /> Explore</a>
								</div>
							</div>
						</div>
					</div>
					<!-- End Sales Conversion Rate Card -->

				</div>
			</div>
			<div class="col-xl-4 col-lg-12">
				<div class="row">
				  <div class="col-lg-12">
				   <div class="card card-custom gutter-b example example-compact">
				   	<div class="card-header">
				   		<h4 class="d-flex justify-content-between align-items-center block-title"><span>Interaction Snapshot</span></h4>
				   	</div>
					<div class="card-body p-4">

						<div class="row">
							<div class="col-12">
								<div class="d-flex align-items-center">
									<?php if($accType == 'ent'): ?>
										<div class="form-group mr-3">
											<label class="font-weight-bold">Select Sub Account</label>
											<select class="form-control select-box" id="subaccount">
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
										<div class="form-group">
											<label class="font-weight-bold">Select Machines</label>
											<select class="form-control select-box" id="machines">
												<option value="">Select Machines</option>
												<option value="all">All Machines</option>
											</select>
										</div>
									<?php else: ?>
										<div class="form-group">
											<label class="font-weight-bold">Select Machines</label>
											<select class="form-control select-box" id="machines">
												<option value="all">All Machines</option>
												<?php if($machines->count() > 0): ?>
													<?php $machine = $machines->toArray(); ?>
													<?php
														foreach ($machine as $key => $val) {
															echo "<option value='".$val['kiosk_id']."'>".$val['kiosk_identifier']."</option>";
														}
													?>
												<?php endif; ?>
											</select>
										</div>
									<?php endif; ?>

								</div>
								<div id="countsBlock" class="row">
									<div class="col-lg-6 col-12">
										<div class="d-flex align-items-center">
											<h4 class="mb-0 mr-2">Customers</h4>
											<a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="A visitor who adds products to cart and either completes sale or abandons cart"><i class="fas fa-info-circle"></i></a>
										</div>
										<div class="d-flex align-items-center mb-10 mt-4">
											<div class="symbol symbol-40 symbol-light-primary mr-5">
												<span class="symbol-label">
													<span class="svg-icon svg-icon-xl svg-icon-primary">
														<img src="{{ asset('images/home/icon-customer-circle.png') }}" />
													</span>
												</span>
											</div>
											<div class="d-flex flex-column font-weight-bold">
												<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">

													<?php echo number_format($customerData['count']) ?></a>

												<?php
													$diffPrice = ($customerData['count'] - $customerData['prevcount']);

													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
												<span class="text-muted"><i class="fas {{ $caretIcon }} "></i> <?php echo number_format($diffPrice) ?></span>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-12">
										<div class="d-flex align-items-center">
											<h4 class="mb-0 mr-2">Visitors</h4>
											<a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title='A person who touches the POS – “visits the machine” '><i class="fas fa-info-circle"></i></a>
										</div>
										<div class="d-flex align-items-center mb-10 mt-4">
											<div class="symbol symbol-40 symbol-light-primary mr-5">
												<span class="symbol-label">
													<span class="svg-icon svg-icon-xl svg-icon-primary">
														<img src="{{ asset('images/home/icon-visitors-circle.png') }}" />
													</span>
												</span>
											</div>
											<div class="d-flex flex-column font-weight-bold">
												<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg"><?php echo number_format($visitorData['count']) ?></a>
												<?php
													$diffPrice = ($visitorData['count'] - $visitorData['prevcount']);

													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
												<span class="text-muted"><i class="fas {{ $caretIcon }} "></i> <?php echo number_format($diffPrice) ?></span>
											</div>
										</div>
									</div>


								<div class="col-lg-6 col-12">
										<div class="d-flex align-items-center">
											<h4 class="mb-0 mr-2">Views</h4>
											<a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="A person whose full face is detected"><i class="fas fa-info-circle"></i></a>
										</div>
										<div class="d-flex align-items-center mb-10 mt-4">
											<div class="symbol symbol-40 symbol-light-primary mr-5">
												<span class="symbol-label">
													<span class="svg-icon svg-icon-xl svg-icon-primary">
														<img src="{{ asset('images/home/icon-lookers-circle.png') }}" />
													</span>
												</span>
											</div>
											<div class="d-flex flex-column font-weight-bold">
												<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{ count($viewsData['lookers']) }}</a>
											</div>
										</div>
									</div>


								<div class="col-lg-6 col-12">
										<div class="d-flex">
											<h4 class="mb-0 mr-2">Impressions</h4>
											<a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="A person detected passing the machine within 35 feet"><i class="fas fa-info-circle"></i></a>
										</div>
										<div class="d-flex align-items-center mb-10 mt-4">
											<div class="symbol symbol-40 symbol-light-primary mr-5">
												<span class="symbol-label">
													<span class="svg-icon svg-icon-xl svg-icon-primary">
														<img src="{{ asset('images/home/icon-passers-circle.png') }}" />
													</span>
												</span>
											</div>
											<div class="d-flex flex-column font-weight-bold">
                                                <a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{ count($viewsData['passers']) }}</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

					 </div>
				</div>

			</div>





		</div>

	</div>

	@endif

	<div id="notificationModal" class="modal fade">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Please Note: Inventory Levels are low for one or more products in the following machine(s)</h5>
	                <button type="button" class="close" aria-hidden="true" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
					<table class="table table-bordered low_inventory_tbl">
						<tr>
							<th>Machine Name</th>
							<th>Bin Identification</th>
							<th>Product</th>
							<th>Inventory Level</th>
						</tr>
					</table>
	            </div>
	        </div>
	    </div>
	</div>

@endsection

@section('scripts')
	<script src="{{ asset('js/home.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection

