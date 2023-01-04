@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <style>
        .symbol.symbol-35 > img {
            width: 100%;
            max-width: 25px;
            height: 25px;
            margin-right: 5px;
            display: inline-block;
        }
        .mask-panel{
            width: 100%;
            height: 100%;
            left: 0px;
            top: 0px;
            border-radius: 0.42rem;
            display: none;
            justify-content: center;
            align-items: center;
            background-color: rgba(0,0,0,0.4);
            color: white;
            font-size: 20px;
            font-weight: bold;
            transition: all 0.5s;
            -webkit-transition: all 0.5s;
        }
        .loading .mask-panel{
            display: flex!important;
        }
        .card-body{
            position: relative;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 d-flex flex-column justify-content-center">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <p class="font-size-h4">Who's passing by, who's looking, who's visiting?</p>
            <h4>Dive into your Traffic Analytics.</h4>
        </div>
        <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <select name="kiosk_id" id="kiosk_id" class="form-control" required="">
                <option value="">All Machines</option>
                @foreach ($getMachine as $item)
                    <option value="{{$item->kiosk_id}}">{{$item->kiosk_identifier}}</option>
                @endforeach
            </select>
        </div>
        <div class="row align-items-center mt-5">
            <div class="col-6">
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
        <div class="card p-2">
            <div class="card-body p-3 row">
                <div class="col-lg-2 col-12 border-right border-right-light-dark border-1">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-0 mr-2">Visitors</h4>
                        <a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="A visitor who adds products to cart and either completes sale or abandons cart"><i class="fas fa-info-circle"></i></a>
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
                            <a href="#" class="text-dark text-hover-primary mb-1 font-size-h4" id="visitors_number"><?php echo number_format(0) ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-12 border-right border-right-light-dark border-1">
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
                            <a href="#" class="text-dark text-hover-primary mb-1 font-size-h4" id="lookers_number"><?php echo number_format(0) ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-12 border-right border-right-light-dark border-1">
                    <div class="d-flex align-items-center">
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
                            <a href="#" class="text-dark text-hover-primary mb-1 font-size-h4" id="passers_number"><?php echo number_format(0) ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-12 border-right border-right-light-dark border-1">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-0 mr-2">Gaze Thru Rate</h4>
                        <a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="#views/#impressions – indicator of ad success"><i class="fas fa-info-circle"></i></a>
                    </div>
                    <div class="d-flex align-items-center mb-5 mt-1">
                        <div class="d-flex flex-column font-weight-bold">
                            <a href="#" class="text-light-dark text-hover-primary font-size-lg">Views/Impressions</a>
                            <a href="#" class="text-dark text-hover-primary mb-1 font-size-h4" id="attraction_number"><?php echo '0%' ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-0 mr-2">Engagement Rate</h4>
                        <a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="#visitors/#views – indicator of product interest"><i class="fas fa-info-circle"></i></a>
                    </div>
                    <div class="d-flex align-items-center mb-5 mt-1">
                        <div class="d-flex flex-column font-weight-bold">
                            <a href="#" class="text-light-dark text-hover-primary font-size-lg">Visitors/Views</a>
                            <a href="#" class="text-dark text-hover-primary mb-1 font-size-h4" id="engagement_number"><?php echo '0%' ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center mt-5 d-flex">
            <div class="col-4">
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-body" style="position: relative">
                        <h4>Gaze Thru Rate</h4>
                        <div class="row">
                            <div class="col-12 p-0" style="height: 34.8vh;">
                                <canvas id="analyticsChart" style="width: 100%; height: 100%;"></canvas>
                            </div>
                        </div>
                        <div class="position-absolute mask-panel">Loading..</div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 p-0" style="height: 34.8vh;">
                                <canvas id="analyticsLineChart" style="width: 100%; height: 100%;"></canvas>
                            </div>
                        </div>
                        <div class="position-absolute mask-panel">Loading..</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card p-2">
            <div class="card-body">
                <div class="row">
                    <div class="card card-custom bg-gray-200 mr-1 ml-1">
                        <div class="card-body d-flex flex-column align-items-center">
                            <p class="font-size-h4 font-weight-bold">Average Attention Time</p>
                            <p class="font-size-h3 font-weight-boldest" id="avg_time">0 mins</p>
                        </div>
                    </div>
                    <div class="card card-custom bg-gray-200 mr-1 ml-1">
                        <div class="card-body d-flex flex-column align-items-center">
                            <p class="font-size-h4 font-weight-bold">Average Female Attention Time</p>
                            <p class="font-size-h3 font-weight-boldest" id="avg_female_time">0 mins</p>
                        </div>
                    </div>
                    <div class="card card-custom bg-gray-200 mr-1 ml-1">
                        <div class="card-body d-flex flex-column align-items-center">
                            <p class="font-size-h4 font-weight-bold">Average Male Attention Time</p>
                            <p class="font-size-h3 font-weight-boldest" id="avg_male_time">0 mins</p>
                        </div>
                    </div>
                </div>
                <div class="row mt-10 mb-2">
                    <div class="col-7">
                        <div class="row">
                            <div class="col-6 p-0 chart-body" style="height: 34.8vh;">
                                <canvas id="analyticsGenderChart" style="width: 100%; height: 100%;"></canvas>
                                <div class="position-absolute mask-panel">Loading..</div>
                            </div>
                            <div class="col-6 p-0 chart-body" style="height: 34.8vh;">
                                <canvas id="analyticsAgeChart" style="width: 100%; height: 100%;"></canvas>
                                <div class="position-absolute mask-panel">Loading..</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="row">
                            <div class="col-12 p-0 chart-body" style="height: 34.8vh;">
                                <canvas id="analyticsEmotionChart" style="width: 100%; height: 100%;"></canvas>
                                <div class="position-absolute mask-panel">Loading..</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-10 mb-2">
                    <div class="col-6">
                        <div class="card p-2">
                            <div class="card-title">
                                <h4>Attention Time By Gender</h4>
                            </div>
                            <div class="card-body" style="height: 34.8vh;">
                                <canvas id="analyticsLookTimeChart" style="width: 100%; height: 100%;"></canvas>
                                <div class="position-absolute mask-panel">Loading..</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-2">
                            <div class="card-title">
                                <h4>Views By Gender</h4>
                            </div>
                            <div class="card-body" style="height: 34.8vh;">
                                <canvas id="analyticsLookersChart" style="width: 100%; height: 100%;"></canvas>
                                <div class="position-absolute mask-panel">Loading..</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-10 mb-2">
                    <div class="col-6">
                        <div class="card p-2">
                            <div class="card-title">
                                <h4>Attention time by Age Groups</h4>
                            </div>
                            <div class="card-body" style="height: 34.8vh;">
                                <canvas id="analyticsAgeByGenderChart" style="width: 100%; height: 100%;"></canvas>
                                <div class="position-absolute mask-panel">Loading..</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-2">
                            <div class="card-title">
                                <h4>Views By Age Group</h4>
                            </div>
                            <div class="card-body" style="height: 34.8vh;">
                                <canvas id="analyticsLookersByAgeChart" style="width: 100%; height: 100%;"></canvas>
                                <div class="position-absolute mask-panel">Loading..</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-10 mb-2">
                    <div class="col-6">
                        <div class="card p-2">
                            <div class="card-title">
                                <h4>Age Group By Gender</h4>
                            </div>
                            <div class="card-body" style="height: 34.8vh;">
                                <canvas id="analyticsEmotionsChart" style="width: 100%; height: 100%;"></canvas>
                                <div class="position-absolute mask-panel">Loading..</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-2">
                            <div class="card-title">
                                <h4>Views By Emotions</h4>
                            </div>
                            <div class="card-body" style="height: 34.8vh;">
                                <canvas id="analyticsLookersByEmotionChart" style="width: 100%; height: 100%;"></canvas>
                                <div class="position-absolute mask-panel">Loading..</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{ Config::get('constants.ProductDelete') }}" id="productDeleteMsg" />
@endsection

@section('scripts')
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datepicker/moment.min.js') }}" ></script>
    <script src="{{ asset('plugins/custom/datepicker/moment-timezone-with-data.js') }}" ></script>
    <script src="{{ asset('plugins/custom/datepicker/daterangepicker.js') }}" ></script>
    <script src="{{ asset('plugins/custom/chartjs/chart.min.js') }}" ></script>
    <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js" ></script>

    <script src="{{ asset('js/account/traffic.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
@endsection

