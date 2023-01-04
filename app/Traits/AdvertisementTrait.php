<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;

use App\Models\Ad;
use App\Models\MyAd;
use App\Models\Account;

use App\Models\Kiosk;
use App\Models\KioskProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;

trait AdvertisementTrait {

    public function getAllAdvertisement(Request $request){
        if ($request->ajax()) {
            $logAccountID   = Auth::user()->account_id;
            $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();

            if($accountType == 'ent' || $accountType == 'std'){
                $accountID = Auth::user()->account_id;  

                if($request->sub_account_id != ''){
                    $data = Ad::select('ads.ad_id', 'ads.ad_title', 'ads.ad_type', 'ads.ad_data', 'myadvertisement.gender as ad_gender', 'myadvertisement.age as ad_age_group', 'myadvertisement.status as ad_status')
                    ->rightJoin('myadvertisement', 'ads.ad_id', '=', 'myadvertisement.advertisement_id')
                    ->where('ads.account_id', $accountID)
                    ->where('myadvertisement.account_id', $request->sub_account_id);

                    if($request->ad_status != ''){
                        $data = $data->where('myadvertisement.status', $request->ad_status);
                    }
        
                    if($request->ad_gender != ''){
                        $data = $data->where('myadvertisement.gender', $request->ad_gender);
                    }
        
                    if($request->ad_age_group != ''){
                        $data = $data->where('myadvertisement.age', $request->ad_age_group);
                    }

                }else{
                    $data = Ad::where('account_id', $accountID);

                    if($request->ad_status != ''){
                        $data = $data->where('ad_status', $request->ad_status);
                    }
        
                    if($request->ad_gender != ''){
                        $data = $data->where('ad_gender', $request->ad_gender);
                    }
        
                    if($request->ad_age_group != ''){
                        $data = $data->where('ad_age_group', $request->ad_age_group);
                    }
                }                
            }else{

                $accountID[]        = Auth::user()->account_id;
                $parentAccountID    = Auth::user()->accountDetails()->pluck('account_id_parent')->first();
                array_push($accountID, $parentAccountID);

                $data = Ad::select('ads.ad_id', 'ads.ad_title', 'ads.ad_type', 'ads.account_id', 'ads.ad_data',
                    DB::raw('IF(myadvertisement.advertisement_id = ads.ad_id, myadvertisement.status, ads.ad_status) 
                    AS ad_status'),
                    DB::raw('IF(myadvertisement.advertisement_id = ads.ad_id, myadvertisement.age, ads.ad_age_group) 
                    AS ad_age_group'),
                    DB::raw('IF(myadvertisement.advertisement_id = ads.ad_id, myadvertisement.gender, ads.ad_gender) 
                    AS ad_gender')
                )
                ->leftJoin('myadvertisement', 'ads.ad_id', '=', 'myadvertisement.advertisement_id')
                ->whereIn('ads.account_id', $accountID);

                if($request->ad_status != ''){
                    $data = $data->where('ads.ad_status', $request->ad_status);//->orWhere('myadvertisement.status', $request->ad_status);
                }
    
                if($request->ad_gender != ''){
                    $data = $data->where('ads.ad_gender', $request->ad_gender);//->orWhere('myadvertisement.gender', $request->ad_gender);
                }
    
                if($request->ad_age_group != ''){
                    $data = $data->where('ads.ad_age_group', $request->ad_age_group);//->orWhere('myadvertisement.age', $request->ad_age_group);
                }
            }

            if($request->ad_type != ''){
                $data = $data->where('ad_type', $request->ad_type);
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('ad_data', function ($row) {
                    if($row->ad_type == 'image'){
                        return '<div class="symbol symbol-60 symbol-2by3 flex-shrink-0"><div class="symbol-label adv_image" style="background-image: url('.$row->ad_data.')"></div></div>';
                    }else{
                        return '<div class="symbol symbol-60 symbol-2by3 flex-shrink-0"> <svg class="symbol-label adv_image" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M9.776 2l11.396 11.395-7.78 7.777-11.392-11.391v-7.781h7.776zm.829-2h-10.605v10.609l13.391 13.391 10.609-10.604-13.395-13.396zm-3.191 7.414c-.781.782-2.046.782-2.829.001-.781-.783-.781-2.048 0-2.829.782-.782 2.048-.781 2.829-.001.782.783.781 2.047 0 2.829zm-.728 3.515l4.243-4.243.707.707-4.243 4.243-.707-.707zm1.414 1.414l4.243-4.243.354.354-4.243 4.243-.354-.354zm2.829 2.829l4.243-4.243.354.354-4.243 4.243-.354-.354zm-1.768-1.768l4.243-4.243 1.061 1.061-4.243 4.243-1.061-1.061zm2.828 2.828l4.243-4.243.707.707-4.243 4.243-.707-.707zm1.415 1.414l4.242-4.242.354.354-4.242 4.242-.354-.354z"></path></svg> </div>';
                    }                    
                })
                ->editColumn('ad_type', function ($row) {
                    return \ucfirst($row->ad_type);
                })
                ->editColumn('ad_age_group', function ($row) {
                    if($row->ad_age_group != ''){
                        return \ucfirst($row->ad_age_group == 'young' ? 'Young Adult' : ($row->ad_age_group == 'child' ? 'Youth' : $row->ad_age_group) );
                    }else{
                        return 'Not Set';
                    }
                })
                ->editColumn('ad_gender', function ($row) {
                    if($row->ad_gender != ''){
                        return $row->ad_gender == 'M' ? 'Male' : 'Female';
                    }else{
                        return 'Not Set';
                    }
                })
                ->editColumn('ad_status', function ($row) {
                    if($row->ad_status == 'Y'){
                        $status = "<button title='Active' data-id='$row->ad_id' data-type='N' class='btn btn-success btn-sm status' style='cursor: text;'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' data-id='$row->ad_id' data-type='Y' class='btn btn-danger btn-sm status' style='cursor: text;'>Inactive</button>";
                    }
                    return $status;
                })
                ->addColumn('action', function($row) use ($logAccountID, $accountType){
                    $id = encrypt($row->ad_id);

                    if($accountType == 'ent'){
                        $btn = "<a href='advertisement/edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon' title='Edit Advertisement'><i class='fas fa-edit fsize13'></i></a>";
                    }else{
                        if($logAccountID == $row->account_id){
                            $btn = "<a href='advertisement/edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon' title='Edit Advertisement'><i class='fas fa-edit fsize13'></i></a> <a href='javascript:void(0);' class='btn btn-sm btn-primary btn-text-primary btn-icon ml-1 delProduct' title='Delete Advertisement' data-id='$id'><i class='fas fa-trash-alt fsize13'></i></a>";
                        }else{
                            $btn = "<a href='advertisement/edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon' title='Edit Advertisement'><i class='fas fa-edit fsize13'></i></a>";
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'ad_data', 'ad_status'])
                ->make(true);
        }
    }

    public function addAdvertisement(Request $request){
        try {
            $accountID = Auth::user()->account_id;
            
            $tempAds = [
                'account_id'            => $accountID,
                'ad_title'              => $request->ad_title,
                'ad_type'               => $request->ad_type,
                'ad_age_group'          => $request->ad_age_group,
                'ad_status'             => $request->ad_status,
                'ad_gender'             => $request->ad_gender,
                'created_at'            => Config::get('constants.CURRENTEPOCH'),
                'modified_at'           => Config::get('constants.CURRENTEPOCH'),
            ];

            if($request->file != '') {
                
                $name = $request->filename; 
                $s3DestinationFrom  = $accountID.'/temp/'.$name;
                $s3DestinationTo    = $accountID.'/advertisement/'.$name;

                Storage::disk('s3')->move($s3DestinationFrom, $s3DestinationTo);
                $tempAds['ad_data'] = Storage::disk('s3')->url($s3DestinationTo);
            }

            Ad::create($tempAds);

            return true;
        } catch (\Throwable $th) {
            //dd($th);
            return false;
        }
    }

    public function deleteAdvertisement(Request $request){

        try {
            $adsID  = decrypt($request->adsID);
            $accountID = Auth::user()->account_id;
            
            $checkData = Ad::where(['ad_id' => $adsID, 'account_id' => $accountID])->first();
            if($checkData){
                Ad::where(['ad_id' => $adsID, 'account_id' => $accountID])->delete();
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }        
    }

    public function editAdvertisement($id){

        try {
            $ad_id          = decrypt($id);
            $accountID      = Auth::user()->account_id;
            $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
        } catch (\Throwable $th) {
            return 'Fail';
        }

        if($accountType == 'sub'){

            return Ad::select('ads.ad_id', 'ads.ad_title', 'ads.ad_type', 'ads.account_id', 'ads.created_at', 'ads.ad_data',
                DB::raw('IF(myadvertisement.advertisement_id = ads.ad_id, myadvertisement.status, ads.ad_status) 
                AS ad_status'),
                DB::raw('IF(myadvertisement.advertisement_id = ads.ad_id, myadvertisement.age, ads.ad_age_group) 
                AS ad_age_group'),
                DB::raw('IF(myadvertisement.advertisement_id = ads.ad_id, myadvertisement.gender, ads.ad_gender) 
                AS ad_gender')
            )
            ->leftJoin('myadvertisement', 'ads.ad_id', '=', 'myadvertisement.advertisement_id')
            ->where('ads.ad_id', $ad_id)->first();            
        }else{
            return Ad::where(['ad_id' => $ad_id])->first();
        }
    }

    public function updateAdvertisement(Request $request){
        
        try {
            $ad_id      = decrypt($request->ad_id);
            $accountID  = Auth::user()->account_id;

            if($accountID == $request->account_id){
                $tempAds = [
                    'ad_title'              => $request->ad_title,
                    'ad_type'               => $request->ad_type,
                    'ad_age_group'          => $request->ad_age_group,
                    'ad_status'             => $request->ad_status,
                    'ad_gender'             => $request->ad_gender,
                    'modified_at'           => Config::get('constants.CURRENTEPOCH'),
                ];

                if($request->file != ''){
                
                    $name               = $request->filename; 
                    $s3DestinationFrom  = $accountID.'/temp/'.$name;
                    $s3DestinationTo    = $accountID.'/advertisement/'.$name;
    
                    Storage::disk('s3')->move($s3DestinationFrom, $s3DestinationTo);
                    $tempAds['ad_data'] = Storage::disk('s3')->url($s3DestinationTo);
                }

                Ad::where('ad_id', $ad_id)->update($tempAds);

            }else{
                $addata = array(
                    'advertisement_id'  => $ad_id,
                    'account_id'        => $accountID
                );
                
                if(isset($request->ad_gender) && $request->ad_gender != ''){
                    $addata['gender'] = $request->ad_gender;
                }

                if(isset($request->ad_age_group) && $request->ad_age_group != ''){
                    $addata['age'] = $request->ad_age_group;
                }

                if(isset($request->ad_status) && $request->ad_status != ''){
                    $addata['status'] = $request->ad_status;
                }

                $check = MyAd::where(['advertisement_id' => $ad_id, 'account_id' => $accountID])->first();
                if($check){
                    MyAd::where('myadvertisement_id', $check->myadvertisement_id)->update($addata);
                }else{
                    MyAd::create($addata);
                }
            }

            return true;
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function checkAdvertisementName(Request $request){

        try {
            $ad_title    = $request->ad_title;
            $accountID   = Auth::user()->account_id;           
            
            $check = Ad::where('ad_title', $ad_title)->where('account_id', $accountID)->first();
            if($check){
                return 'error';
            }else{
                return '';
            }
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function getAccountDetails($id){

        try {
            $ad_id          = decrypt($id);
            $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
            if($accountType == 'ent'){
                return MyAd::select('accounts.account_id', 'accounts.account_name', 'myadvertisement.gender', 'myadvertisement.age', 'myadvertisement.status')
                ->leftJoin('accounts', 'accounts.account_id', '=', 'myadvertisement.account_id')
                ->where('myadvertisement.advertisement_id', $ad_id)->get();
            }else{
                return [];
            }
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function deleteImageAdvertisement(Request $request){

        try {
            $adsID  = decrypt($request->adsID);
            $accountID = Auth::user()->account_id;
            
            $checkData = Ad::where(['ad_id' => $adsID, 'account_id' => $accountID])->first();
            if($checkData){
                $replaced = Str::replace('https://popcom-saas.s3.us-east-2.amazonaws.com/', '', $checkData->ad_data);
                \Storage::disk('s3')->delete($replaced);

                Ad::where(['ad_id' => $adsID, 'account_id' => $accountID])->update(['ad_data' => NULL]);
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }        
    }
   
}