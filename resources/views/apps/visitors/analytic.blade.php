@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/custom/datepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/custom/chartjs/Chart.min.css') }}" />
@endsection

@section('content')
<h3 class="card-title mb-3">Visitor Analytics</h3>
<div class="row ">
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
               <option value="">All Machines</option>
            </select>
        </div>

    <?php else: ?>
        <div class="col-6 text-center">
            <div class="form-group">
                <select name="kiosk_id" id="kiosk_id" class="form-control select-box" >
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
<h2 class="main-content_header mt-5 mb-3 pb-2">Engagement</h2>
<div class="row row-equal-height mb-3">
	<!-- Sales Card -->
    <?php $monthData = $getData['month'];
        //echo "<pre>"; print_r($monthData);echo "</pre>"; 
     ?>
	<div class="col-lg-4 col-sm-6 col-12 sale-anylatic">
		<div class="card sale-card card-custom gutter-b example example-compact">
			<div class="d-flex card-header align-items-center justify-content-between">
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
            <div class="d-flex card-header align-items-center justify-content-between">
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
            <div class="d-flex card-header align-items-center justify-content-between">
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
</div>
<div class="row align-items-center mt-5">
    <div class="col-5">
         <div class="form-group">
            <span class="d-block mb-3">Select a date range </span>
            <div class="col-12 p-0">
             <input readonly="" type="text" class="form-control text-center" autocomplete="off" name="daterange" value="" placeholder="Select Dates" />
            </div>
        </div>      
    </div>
    <div class="col-6">
        <div class="form-group">
            <span class="d-block mb-3">Select a period </span>
            <div class="has-select form-group">
                <select class="select-box form-control" id="datePeriod">
                    <option selected value="today">Today</option>
                    <option value="week">This week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                    <option value="lastyear">Last Year</option>
                </select>
            </div>
        </div>      
    </div>
</div>
<div class="row mt-4">
    <div class="col-12">
         <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                         <ul class="nav nav-tabs">
                            <li class="nav-item">
                              <a class="nav-link active" data-toggle="tab" href="#gender">Gender / Time</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#age">Age Group / Time</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#emotion">Emotion / Time</a>
                            </li>
                          </ul>
                          <div class="tab-content">
                            <div id="gender" class="container tab-pane active">
                                <div class="d-block mt-4">
                                    <canvas id="genderChart"></canvas>
                                </div>
                            </div>
                            <div id="age" class="container tab-pane fade">
                                <div class="d-block mt-4">
                                    <canvas id="ageChart"></canvas>
                                </div>
                            </div>
                            <div id="emotion" class="container tab-pane fade">
                                <div class="d-block mt-4">
                                    <canvas id="emotionChart"></canvas>
                                </div>
                            </div>
                          </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <h4 class="chartLable">Today</h4>
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

    <script src="{{ asset('js/account/visitorChart.js') }}?v={{ config('constants.WEB_VERSION') }}" ></script>
@endsection

