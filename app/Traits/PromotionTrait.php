<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Auth;

use App\Models\Promo;
use App\Helpers\CommonHelper;

trait PromotionTrait {

    public function getAllPromotion(Request $request){

        if ($request->ajax()) {
            $accountID = Auth::user()->account_id;

            $data = Promo::select('promos.*', DB::raw('( SELECT GROUP_CONCAT(K.kiosk_identifier) FROM kiosk_promos as KP LEFT JOIN kiosks as K ON K.kiosk_id = KP.kiosk_id where KP.promo_id = promos.promo_id ) as machineID') )
            ->where('account_id', $accountID)
            // ->orderBy('promo_id', 'DESC')
            ->get();
 
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('promo_status', function ($row) {
                    if($row->promo_status == 'Y'){
                        $status = "<button title='Active' data-id='$row->promo_id' data-type='N' class='btn btn-success btn-sm status'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' data-id='$row->promo_id' data-type='Y' class='btn btn-danger btn-sm status'>Inactive</button>";
                    }
                    return $status;
                })
                ->editColumn('promo_discount', function ($row) {
                    $dis = $row->promo_discount.'%';
                    return $dis;
                })
                ->editColumn('machineID', function ($row) {
                    if($row->machineID == ''){
                        return 'Unassigned';
                    }else{
                        return $row->machineID;
                    }
                })
                ->addColumn('action', function($row){
                    $id = encrypt($row->promo_id);
                    $btn = "<a href='promotion/edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon mb-2' title='Edit'><i class='fas fa-edit fsize13'></i></a> 
                        ";
                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    return $row->promo_status;
                })
                ->rawColumns(['promo_status', 'action', 'promo_discount'])
                ->make(true);
        }
    }

    // store data into promos table
    public function savePromotion(Request $request){
        try{
            $input = $request->all();
            $data = Promo::where('account_id', Auth::user()->account_id)->where('promo_code', $input['promo_code'])->get();
            if($data->isNotEmpty()){
                $response['message'] = config('message.EXISTS_PROMO');
                $response['success'] = false;
                return $response;
            }
            $input['account_id'] = Auth::user()->account_id;
            $input['created_at'] = round(microtime(true) * 1000);
            // save data into promo table
            $account = Promo::create($input);
            $response['success'] = true;
        }catch(\Exception $e){
            $response['message'] = $e;
            $response['success'] = false;
            return $response;
        }
        return $response;
    }

    public function updatePromotion(Request $request){
        try{
            $input = $request->all();
            $data = Promo::where('account_id',Auth::user()->account_id)->where('promo_code',$input['promo_code'])->where('promo_id','!=',$input['promo_id'])->get();
            if($data->isNotEmpty()){
                $response['message'] = config('message.EXISTS_PROMO');
                $response['success'] = false;
                return $response;
            }

            $searchInput['promo_id'] = $input['promo_id'];

            Promo::updateorCreate($searchInput, $input);    
            $response['success'] = true;
        }catch(\Exception $e){
            $response['message'] = $e;
            $response['success'] = false;
        }
        return $response;
    }
}