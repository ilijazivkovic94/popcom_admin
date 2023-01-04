<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Auth;
use App\Models\KioskModel;
use App\Helpers\CommonHelper;

trait MachineModelTrait {

    public function getAllMachineModel(Request $request){
        if ($request->ajax()) {
            $data = KioskModel::orderBy('kiosk_model_id', 'DESC')->get();
 
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('model_avaialble_yn', function ($row) {
                    if($row->model_avaialble_yn == 'Y'){
                        $status = "<button title='Active' data-id='$row->kiosk_model_id' data-type='N' class='btn btn-success btn-sm status'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' data-id='$row->kiosk_model_id' data-type='Y' class='btn btn-danger btn-sm status'>Inactive</button>";
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $id = encrypt($row->kiosk_model_id);
                    $btn = "<a href='machine-model/edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon mb-2' title='Edit Machine Model'><i class='fas fa-edit fsize13'></i></a> 
                        ";
                    return $btn;
                })
                ->editColumn('created_at', function ($row) {
                    if($row->created_at!=''){
                        return CommonHelper::DateFormat($row->created_at);
                    }else{
                        return;
                    }
                })
                ->editColumn('modified_at', function ($row) {
                    if($row->modified_at!=''){
                        return CommonHelper::DateFormat($row->modified_at);
                    }else{
                        return;
                    }
                })
                ->addColumn('status', function ($row) {
                    return $row->model_avaialble_yn;
                })
                ->rawColumns(['model_avaialble_yn','created_at','action'])
                ->make(true);
        }
    }

    // store data into kiosk model table
    public function saveMachineModel(Request $request){
        try{
            $input = $request->all();
            
            $input['created_at'] = round(microtime(true) * 1000);
            // save data into account table
            $account = KioskModel::create($input);
            $response['success'] = true;
        }catch(\Exception $e){
            $response['message'] = $e;
            $response['success'] = false;
            return $response;
        }
        return $response;
    }

    public function updateMachineModel(Request $request){
        try{
            $input = $request->all();
            $input['modified_at'] = round(microtime(true) * 1000);
            $searchInput['kiosk_model_id'] = $input['kiosk_model_id'];

            KioskModel::updateorCreate($searchInput, $input);
            $response['success'] = true;
        }catch(\Exception $e){
            $response['message'] = $e;
            $response['success'] = false;
        }
        return $response;
    }
}