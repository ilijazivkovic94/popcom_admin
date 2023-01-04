<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Auth;

use App\Mail\CreateSubAccount;
use App\Helpers\CommonHelper;

use App\Models\User;
use App\Models\Account;
use App\Models\AccountSetting;

trait SubAccountTrait {

    public function getAllSubAccount(Request $request){
        if ($request->ajax()) {

            $accountID = Auth::user()->account_id;
            $data = Account::select('accounts.*', DB::raw('GROUP_CONCAT( IFNULL(users.user_fname, ""), IFNULL(users.user_lname, "") ) as full_name'), 'users.email', 'users.user_active_yn')
            ->join('users', 'users.account_id', '=', 'accounts.account_id')
            ->where('accounts.account_id_parent', $accountID)
            ->groupBy('accounts.account_id');

            if($request->search['value'] != ''){
                $searchkey = $request->search['value'];
                $data = $data->whereRaw("(accounts.account_name LIKE '%".$searchkey."%' OR users.user_fname LIKE '%".$searchkey."%' OR users.user_lname LIKE '%".$searchkey."%' OR users.email LIKE '%".$searchkey."%' )");
            }

            $data = $data->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return CommonHelper::DateFormat($row->created_at);
                })
                ->editColumn('user_active_yn', function ($row) {
                    if($row->user_active_yn == 'Y'){
                        $status = "<button title='Active' data-id='$row->account_id' data-type='N' class='btn btn-success btn-sm status' style='cursor: text;'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' data-id='$row->account_id' data-type='Y' class='btn btn-danger btn-sm status' style='cursor: text;'>Inactive</button>";
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $id     = encrypt($row->account_id);
                    $btn    = "<a href='machines/list/$id' class='btn btn-block btn-primary btn-text-primary text-white' title='Manage Machines'>Manage Machines</a>";
                    return $btn;
                })
                ->rawColumns(['action', 'user_active_yn'])
                ->make(true);
        }
    }

    public function addSubAccount(Request $request){
        // dd($request->all());

        try {
            $accountID = Auth::user()->account_id;

            $accountData = Account::where('account_name', $request->account_name)->get();
            if($accountData->isNotEmpty()){
                return 'account_error';
            }

            $emailData = User::where('email', $request->email)->get();
            if($emailData->isNotEmpty()){
                return 'email_error';
            }
            
            $tempAccount = [
                'account_name'          => $request->account_name,
                'account_status'        => 'N',
                'account_id_parent'     => $accountID,
                'account_type'          => 'sub',
                'created_at'            => Config::get('constants.CURRENTEPOCH'),
                'modified_at'           => Config::get('constants.CURRENTEPOCH'),
            ];

            $account = Account::create($tempAccount);
            $newAccountID = $account->account_id;

            User::create([
                'account_id'        => $newAccountID,
                'user_admin_yn'     => 'N',
                'email'             => $request->email,
                'password'          => bcrypt($request->password),
                'created_at'        => Config::get('constants.CURRENTEPOCH'),
                'modified_at'       => Config::get('constants.CURRENTEPOCH'),
                'user_active_yn'    => 'N',
            ]);

            AccountSetting::create([
                'account_id'        => $newAccountID,
            ]);

            $inputData['pre_account_name']  = '';
            $inputData['sub_account_id']    = $newAccountID;
            $inputData['sub_account_name']  = $request->account_name;
            $inputData['sub_account_email'] = $request->email;
            $prtAccount = Account::where('account_id', $accountID)->first();
            if($prtAccount){
                $inputData['pre_account_name'] = $prtAccount->account_name;
            }
            
            $ADMIN_EMAIL = CommonHelper::ADMIN_EMAIL();
            Mail::to($ADMIN_EMAIL)->send(new CreateSubAccount($inputData));
            // \Log::info("Account Creation email failed for ".$input['email']);

            return 'success';
        } catch (\Throwable $th) {
            // dd($th);
            return 'Fail';
        }
    }

    public function deleteProduct(Request $request){

        try {
            $productID  = decrypt($request->productID);
            $accountID = Auth::user()->account_id;
            
            $checkData = Product::where(['product_id' => $productID, 'account_id' => $accountID])->first();
            if($checkData){
                ProductImage::where('product_id', $productID)->delete();
                ProductVariant::where('product_id', $productID)->delete();
                Product::where(['product_id' => $productID, 'account_id' => $accountID])->delete();
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }        
    }

    public function editProduct($id){

        try {
            $productID  = decrypt($id);
            $accountID  = Auth::user()->account_id;
        } catch (\Throwable $th) {
            return 'Fail';
        }

        $productData = Product::where(['product_id' => $productID, 'account_id' => $accountID])->first();
        $variantData = ProductVariant::select('product_variants.*', 'kiosk_product.kiosk__id')->leftJoin('kiosk_product', 'kiosk_product.product_variant_id', '=', 'product_variants.product_variant_id')->where(['product_variants.product_id' => $productID])->get();
        $ImageData   = ProductImage::where(['product_id' => $productID])->get();

        $Detail['productData'] = $productData;
        $Detail['variantData'] = $variantData;
        $Detail['ImageData']   = $ImageData;
        return $Detail;
    }

    public function updateProduct(Request $request){

        try {
            $productID  = decrypt($request->product_id);
            $accountID  = Auth::user()->account_id;

            $tempProduct = [
                'product_name'          => $request->product_name,
                'product_description'   => $request->product_des,
                'modified_at'           => Config::get('constants.CURRENTEPOCH'),
            ];

            Product::where('product_id', $productID)->update($tempProduct);

            foreach ($request->product_identifier as $key => $value) {
                $variantID = (isset($request->variartID[$key]) && $request->variartID[$key] != '' ? $request->variartID[$key] : 0);

                $check = ProductVariant::where(['product_variant_id' => $variantID, 'product_id' => $productID])->first();
                if($check){
                    ProductVariant::where(['product_variant_id' => $variantID, 'product_id' => $productID])->update([
                        'product_id'            => $productID,
                        'product_identifier'    => $request->product_identifier[$key],
                        'variant_sku'           => $request->variant_type[$key],
                        'variant_name'          => $request->variant_name[$key],
                        'variant_value'         => NULL,
                        'variant_price'         => $request->price[$key],
                    ]);
                }else{
                    ProductVariant::create([
                        'product_id'            => $productID,
                        'product_identifier'    => $request->product_identifier[$key],
                        'variant_sku'           => $request->variant_type[$key],
                        'variant_name'          => $request->variant_name[$key],
                        'variant_value'         => NULL,
                        'variant_price'         => $request->price[$key],
                    ]);
                }
            }
                
            return true;
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function checkProductName(Request $request){

        try {
            $productName    = $request->productName;
            $accountID      = Auth::user()->account_id;           
            
            $check = Product::where('product_name', $productName)->where('account_id', $accountID)->first();
            if($check){
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }
}