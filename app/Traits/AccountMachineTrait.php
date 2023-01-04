<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Auth;
use App\Helpers\CommonHelper;
use App\Models\Kiosk;
use App\Models\KioskModel;
use App\Models\KioskPromo;
use App\Models\User;
use Illuminate\Support\Facades\Config;

trait AccountMachineTrait {

    public function getAccountMachine(Request $request,$id){
        if ($request->ajax()) {
            $user = User::find($id);
            $data = Kiosk::select('kiosks.*', 'kiosk_model.model_name')->leftjoin('kiosk_model','kiosk_model.kiosk_model_id', '=', 'kiosks.model_id')->where('kiosks.account_id',$user->account_id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('kiosk_status', function ($row) {
                    if($row->kiosk_status == 'Y'){
                        $status = "<button title='Active' data-id='$row->kiosk_id' data-type='N' class='btn btn-success btn-sm status'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' data-id='$row->kiosk_id' data-type='Y' class='btn btn-danger btn-sm status'>Inactive</button>";
                    }
                    return $status;
                })->editColumn('model_name', function ($row) {
                    if(empty($row->model_name)){
                        $model_name = 'NA';
                    }else{
                        $model_name = $row->model_name;
                    }
                    return $model_name;
                })
                ->addColumn('action', function($row){
                    $id = encrypt($row->kiosk_id);
                    $btn = "<a href='edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon mb-2' title='Edit Machine'><i class='fas fa-edit fsize13'></i></a> 
                        ";
                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    return $row->kiosk_status;
                })
                ->rawColumns(['kiosk_status','created_at','action'])
                ->make(true);
        }
    }

    // store data into kiosk model table
    public function saveMachine(Request $request){
        // dd($request->all());
        try{
            $input = $request->all();
            $data = Kiosk::where("kiosk_identifier",$input['kiosk_identifier'])->get();
            if($data->isNotEmpty()){
                $response['message'] = config('message.EXISTS_MACHINE_NAME');
                $response['success'] = false;
                return $response;
            }

            if(!isset($input['pos_min_age'])){
                $input['pos_min_age'] = 0;
            }
            
            $input['kiosk_status']  = 'N';
            $input['created_at']    = round(microtime(true) * 1000);
            $input['modified_at']   = round(microtime(true) * 1000);
            
            $tempBin = '{"TemplateId":"'.$input['kiosk_identifier'].'","seqNum":6,"data":[{"BinId":"BIN 1","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10},{"BinId":"BIN 2","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10},{"BinId":"BIN 3","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10}]},{"BinId":"BIN 4","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10}]},{"BinId":"BIN 5","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10}]},{"BinId":"BIN 6","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10}]}';
            $input['template_name']         = $request->kiosk_identifier.' template';
            $input['template_description']  = $request->kiosk_identifier.' default template';
            $input['template_created_dt']   = Config::get('constants.CURRENTEPOCH');
            $input['template_bin_count']    = 6;
            $input['template_bin_identity'] = 'BIN 1,BIN 2,BIN 3,BIN 4,BIN 5,BIN 6';
            $input['template_json']         = json_encode($tempBin);
            $input['template_status']       = 'N';
            // save data into account table
            $account = Kiosk::create($input);

            //save promo
            if (isset($input['promotions']) && count($input['promotions']) > 0) {
                foreach ($input['promotions'] as $value) {
                    $data2['kiosk_id'] = $account->kiosk_id;
                    $data2['promo_id'] = $value;
                    if($input['optin_promo_id'] == $value) {
                        $data2['optin_yn'] = 'Y';
                    } else {
                        $data2['optin_yn'] = 'N';
                    }
                    if(!empty($data2)){
                        KioskPromo::create($data2);
                    }
                }
            }
            $response['success'] = true;
        }catch(\Exception $e){
            $response['message'] = $e;
            $response['success'] = false;
            return $response;
        }
        return $response;
    }

    public function updateMachine(Request $request) {
        try {
            $input = $request->all();
            $searchInput['kiosk_id'] = $input['kiosk_id'];
            Kiosk::updateorCreate($searchInput, $input);

            if (isset($input['promotions']) && count($input['promotions']) > 0) {
                KioskPromo::where('kiosk_id', $input['kiosk_id'])->delete();
                foreach ($input['promotions'] as $key => $value) {
                    $search['kiosk_id'] = $input['kiosk_id'];
                    $search['promo_id'] = $value;

                    $data['kiosk_id'] = $input['kiosk_id'];
                    $data['promo_id'] = $value;
                    if($input['optin_promo_id'] == $value) {
                        $data['optin_yn'] = 'Y';
                    } else {
                        $data['optin_yn'] = 'N';
                    }

                   KioskPromo::updateorCreate($search, $data);
                }
            } else {
                KioskPromo::where('kiosk_id', $input['kiosk_id'])->delete();
            }
            $response['success'] = true;
        }catch(\Exception $e){
            $response['message'] = $e;
            $response['success'] = false;
        }
        return $response;
    }
}