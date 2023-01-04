<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AdmQueueData;
use App\Models\Journey;
use App\Models\Kiosk;
use App\Traits\CustomerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrafficController extends Controller
{
    use CustomerTrait;
    //

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title = "Traffic Analytics";
        $getMachine = $this->getMachine();
        $subAccount = Account::where('account_id_parent', Auth::user()->accountDetails->account_id)->get();
        return view('apps.traffic.index', compact('page_title', 'getMachine', 'subAccount'));
    }

    public function getData(Request $request) {
        $kiosk_id = $request->input('kiosk_id');
        $time = $request->input('time');
        $days = $request->input('period_days');
        $start_date = $request->input('start_date');

        $date = Carbon::now();
        $date_period = [];
        if($time == 'picker') {
            $date_period = [$request->input('startdt') . ' 00:00:00', $request->input('enddt') . ' 23:59:59'];
        } elseif ($time == 'today') {
            $today = $date->toDateString();
            $date_period = [$today . ' 00:00:00', $today . ' 23:59:59'];
        } elseif ($time == 'week') {
            $weekStartDate = $date->startOfWeek()->format('Y-m-d');
            $weekEndDate = $date->endOfWeek()->format('Y-m-d');
            $date_period = [$weekStartDate . ' 00:00:00', $weekEndDate . ' 23:59:59'];
        } elseif ($time == 'month') {
            $monthStartDate = $date->startOfMonth()->format('Y-m-d');
            $monthEndDate = $date->endOfMonth()->format('Y-m-d');
            $date_period = [$monthStartDate . ' 00:00:00', $monthEndDate . ' 23:59:59'];
        } elseif ($time == 'year') {
            $yearStartDate = $date->startOfYear()->format('Y-m-d');
            $yearEndDate = $date->endOfYear()->format('Y-m-d');
            $date_period = [$yearStartDate . ' 00:00:00', $yearEndDate . ' 23:59:59'];
        } elseif ($time == 'lastyear') {
            $lastYear = date('Y') * 1 - 1;
            $lastYearStartDate = Carbon::create($lastYear)->startOfYear()->format('Y-m-d');
            $lastYearEndDate = Carbon::create($lastYear)->endOfYear()->format('Y-m-d');
            $date_period = [$lastYearStartDate . ' 00:00:00', $lastYearEndDate . ' 23:59:59'];
        }

        $query = AdmQueueData::whereBetween('timestamp', [$date_period[0], $date_period[1]]);

        $kiosk_ids = [];

        if($kiosk_id) {
            $kiosk = Kiosk::find($kiosk_id);
            $query->where('deviceId', $kiosk->kiosk_facial_license);
            $kiosk_ids[] = $kiosk_id;
        } else {
            $deviceIds = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_facial_license');
            if($deviceIds && count($deviceIds) > 0) {
                $query->whereIn('deviceId', $deviceIds);
            }
            $ids = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_id');
            $kiosk_ids = $ids;
        }
        $data = $query->get();
        $journeyIds = [];
        $journeyQuery = Journey::whereIn('kiosk_id', $kiosk_ids);
        if($date_period) {
            $journeyQuery->whereBetween('created_at', [strtotime($date_period[0]) * 1000, strtotime($date_period[1]) * 1000]);
        }
        $journeyIds = $journeyQuery->get()->pluck('journey_id');

        $visitors = [];
        $lookers = [];
        $passers = [];
        $avgLookTime = 0;
        $maleLookTime = 0;
        $femaleLookTime = 0;
        $males = 0;
        $females = 0;
        if($data) {
            foreach ($data as $row) {
                if(strpos($row->deviceName, '-POS')) {
                    $visitors[] = $row;
                }
                if(strpos($row->deviceName, '-ADS') && $row->isView == 1) {
                    $lookers[] = $row;
                }
                if(strpos($row->deviceName, '-ADS') && $row->isImpression == 1) {
                    $passers[] = $row;
                }
                if(strpos($row->deviceName, '-ADS')) {
                    $avgLookTime += $row->sessionTime;
                    if($row->gender == 'MALE') {
                        $maleLookTime += $row->sessionTime;
                        $males++;
                    } else {
                        $femaleLookTime += $row->sessionTime;
                        $females++;
                    }
                }
            }
            $totalByGender = $this->getTotalByGender($data);
            $totalByAgeGroup = $this->getTotalByAgeGroup($data);
            $totalByEmotions = $this->getTotalByEmotions($data);

//            $lookTimeByTypeAndDate = $this->getDataByTypeAndDate('look_time', $date_period, $kiosk_id);
            $lookersByTypeAndDate = $this->getDataByTypeAndDate('lookers', $date_period, $kiosk_id);
            $lookTimeByTypeAndDate = $this->getDataByTypeWithDate('look_time', $date_period, $kiosk_id);
//            $lookersByTypeAndDate = $this->getDataByTypeWithDate('lookers', $date_period, $kiosk_id);

            return response()->json([
                'visitorCount' => count($journeyIds),
                'visitors' => $visitors,
                'lookers' => $lookers,
                'passers' => $passers,
                'avgLookTime' => $avgLookTime / (($males + $females) == 0 ? 1 : ($males + $females)),
                'maleLookTime' => $maleLookTime / ($males == 0 ? 1 : $males),
                'femaleLookTime' => $femaleLookTime / ($females == 0 ? 1 : $females),
                'totalsByGender' => $totalByGender,
                'totalByAgeGroup' => $totalByAgeGroup,
                'totalByEmotions' => $totalByEmotions,
                'lookTimeByTypeAndDate' => $lookTimeByTypeAndDate,
                'lookersByTypeAndDate' => $lookersByTypeAndDate,
            ]);
        }
        return response()->json([
            'visitorCount' => count($journeyIds),
            'visitors' => $visitors,
            'lookers' => $lookers,
            'passers' => $passers,
            'avgLookTime' => $avgLookTime,
            'maleLookTime' => $maleLookTime,
            'femaleLookTime' => $femaleLookTime,
        ]);
    }

    public function getTotalByGender($data) {
        $male = 0;
        $female = 0;
        if($data) {
            foreach ($data as $row) {
                if($row->gender == 'MALE') {
                    $male++;
                } else {
                    $female++;
                }
            }
        }
        return [$male, $female];
    }

    public function getTotalByAgeGroup($data) {
        $result = [0, 0, 0, 0];
        $age_group = AdmQueueData::$AGE_GROUP;
        if($data) {
            foreach ($data as $row) {
                foreach ($age_group as $key => $age) {
                    if($row->age >= $age['min'] && $row->age <= $age['max']) {
                        $result[$key] = $result[$key] * 1 + 1;
                    }
                }
            }
        }
        return $result;
    }

    public function getTotalByEmotions($data) {
        $result = [0, 0, 0, 0, 0, 0, 0];
        $emotions = AdmQueueData::$EMOTIONS;
        if($data) {
            foreach ($data as $row) {
                foreach ($emotions as $key => $emotion) {
                    if($row->emotion == $emotion) {
                        $result[$key] = $result[$key] * 1 + 1;
                    }
                }
            }
        }
        return $result;
    }

    public function getDataByTypeAndDate($data_field, $period, $kiosk_id) {
        $genderData = [];
        $emotionData = [];
        $ageData = [];
        $genders = ['MALE', 'FEMALE'];
        $emotions = AdmQueueData::$EMOTIONS;
        $ageGroup = AdmQueueData::$AGE_GROUP;
        $select = '';
        if($data_field == 'look_time') {
            $select = 'SUM(dwellTime) as total, DATE(timestamp) as date_val';
        } else {
            $select = 'COUNT(dwellTime) as total, DATE(timestamp) as date_val';
        }
        foreach ($genders as $gender) {
            $query = DB::table('adm_queue_data')->select(DB::raw($select));
            $query = $query->where('gender', $gender);
            $query = $query->whereBetween('timestamp', [$period[0], $period[1]]);
            if($kiosk_id) {
                $kiosk = Kiosk::find($kiosk_id);
                $query->where('deviceId', $kiosk->kiosk_facial_license);
            } else {
                $deviceIds = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_facial_license');
                if($deviceIds && count($deviceIds) > 0) {
                    $query->whereIn('deviceId', $deviceIds);
                }
            }
            $query->where('deviceName', 'like', '%-ADS');
            $data = $query->groupBy('date_val')->get();
            $genderData[] = $data;
        }
        foreach ($emotions as $emotion) {
            $query = DB::table('adm_queue_data')->select(DB::raw($select));
            $query = $query->where('emotion', $emotion);
            $query = $query->whereBetween('timestamp', [$period[0], $period[1]]);
            if($kiosk_id) {
                $kiosk = Kiosk::find($kiosk_id);
                $query->where('deviceId', $kiosk->kiosk_facial_license);
            } else {
                $deviceIds = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_facial_license');
                if($deviceIds && count($deviceIds) > 0) {
                    $query->whereIn('deviceId', $deviceIds);
                }
            }
            $query->where('deviceName', 'like', '%-ADS');
            $data = $query->groupBy('date_val')->get();
            $emotionData[] = $data;
        }
        foreach ($ageGroup as $age) {
            $query = DB::table('adm_queue_data')->select(DB::raw($select));
            $query = $query->whereBetween('age', [$age['min'], $age['max']]);
            $query = $query->whereBetween('timestamp', [$period[0], $period[1]]);
            if($kiosk_id) {
                $kiosk = Kiosk::find($kiosk_id);
                $query->where('deviceId', $kiosk->kiosk_facial_license);
            } else {
                $deviceIds = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_facial_license');
                if($deviceIds && count($deviceIds) > 0) {
                    $query->whereIn('deviceId', $deviceIds);
                }
            }
            $query->where('deviceName', 'like', '%-ADS');
            $data = $query->groupBy('date_val')->get();
            $ageData[] = $data;
        }
        return [
            'gender' => $genderData,
            'emotion' => $emotionData,
            'age' => $ageData,
        ];
    }

    public function getDataByTypeWithDate($data_field, $period, $kiosk_id) {
        $genderData = [];
        $emotionData = [];
        $ageData = [];
        $ageGroupByGender = [];
        $genders = ['MALE', 'FEMALE'];
        $emotions = AdmQueueData::$EMOTIONS;
        $ageGroup = AdmQueueData::$AGE_GROUP;
        $select = '';
        if($data_field == 'look_time') {
            $select = 'SUM(dwellTime) as total, DATE(timestamp) as date_val';
        } else {
            $select = 'COUNT(dwellTime) as total, DATE(timestamp) as date_val';
        }
        $query = DB::table('adm_queue_data')->select(DB::raw($select));
        $query = $query->whereBetween('timestamp', [$period[0], $period[1]]);
        if($kiosk_id) {
            $kiosk = Kiosk::find($kiosk_id);
            $query->where('deviceId', $kiosk->kiosk_facial_license);
        } else {
            $deviceIds = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_facial_license');
            if($deviceIds && count($deviceIds) > 0) {
                $query->whereIn('deviceId', $deviceIds);
            }
        }
        $query->where('deviceName', 'like', '%-ADS');
        $data = $query->groupBy('gender')->get();
        $genderData = $data;

        $query = DB::table('adm_queue_data')->select(DB::raw($select));
        $query = $query->whereBetween('timestamp', [$period[0], $period[1]]);
        if($kiosk_id) {
            $kiosk = Kiosk::find($kiosk_id);
            $query->where('deviceId', $kiosk->kiosk_facial_license);
        } else {
            $deviceIds = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_facial_license');
            if($deviceIds && count($deviceIds) > 0) {
                $query->whereIn('deviceId', $deviceIds);
            }
        }
        $query->where('deviceName', 'like', '%-ADS');
        $data = $query->groupBy('emotion')->get();
        $emotionData = $data;

        foreach ($ageGroup as $age) {
            $query = DB::table('adm_queue_data')->select(DB::raw($select));
            $query = $query->whereBetween('age', [$age['min'], $age['max']]);
            $query = $query->whereBetween('timestamp', [$period[0], $period[1]]);
            if($kiosk_id) {
                $kiosk = Kiosk::find($kiosk_id);
                $query->where('deviceId', $kiosk->kiosk_facial_license);
            } else {
                $deviceIds = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_facial_license');
                if($deviceIds && count($deviceIds) > 0) {
                    $query->whereIn('deviceId', $deviceIds);
                }
            }
            $query->where('deviceName', 'like', '%-ADS');
            $data = $query->first();
            $ageData[] = $data;

            $query = DB::table('adm_queue_data')->select(DB::raw($select));
            $query = $query->whereBetween('age', [$age['min'], $age['max']]);
            $query = $query->whereBetween('timestamp', [$period[0], $period[1]]);
            if($kiosk_id) {
                $kiosk = Kiosk::find($kiosk_id);
                $query->where('deviceId', $kiosk->kiosk_facial_license);
            } else {
                $deviceIds = Kiosk::where('account_id', Auth::user()->accountDetails->account_id)->get()->pluck('kiosk_facial_license');
                if($deviceIds && count($deviceIds) > 0) {
                    $query->whereIn('deviceId', $deviceIds);
                }
            }
            $query->where('deviceName', 'like', '%-ADS');
            $data = $query->groupBy('gender')->get();
            $sum = 0;
            foreach ($data as $row) {
                $sum += $row->total * 1;
            }
            foreach ($data as $row) {
                $row->total = number_format($row->total * 100 / $sum, 2);
            }
            $ageGroupByGender[] = $data;
        }
        return [
            'gender' => $genderData,
            'emotion' => $emotionData,
            'age' => $ageData,
            'ageGroupByGender' => $ageGroupByGender
        ];
    }
}
