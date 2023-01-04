<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Auth;

use App\Models\Account;
use App\Models\Kiosk;
use App\Models\KioskModel;
use App\Models\KioskProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Promo;
use App\Models\KioskPromo;
use App\Models\MyProduct;

use App\Mail\CreateMachine;
use App\Helpers\CommonHelper;

trait MachineTrait {

    // Machine Inventory
    public function getAllMachine(Request $request){
        if ($request->ajax()) {

            $accountID      = Auth::user()->account_id;
            $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
            if($accountType == 'ent'){
                $data = Kiosk::selectRaw('(select sum(quantity) as products_count FROM kiosk_product where kiosk__id = kiosks.kiosk_id) products_count, kiosks.*')->leftJoin('accounts', 'accounts.account_id', '=', 'kiosks.account_id')->where('accounts.account_id_parent', $accountID); //where('kiosks.kiosk_status', 'Y')
            }else{                
                $data = Kiosk::selectRaw('(select sum(quantity) as products_count FROM kiosk_product where kiosk__id = kiosks.kiosk_id) products_count, kiosks.*')->where('account_id', $accountID);
            }
    
            
            // selectRaw('(select sum(quantity) as products_count FROM kiosk_product where kiosk__id = kiosks.kiosk_id) products_count, kiosks.*')

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('kiosk_low_inv_threshold', function ($row) {
                    return $row->getKioskAlert($row->kiosk_id, $row->kiosk_low_inv_threshold);
                })
                ->editColumn('products_count', function ($row) {
                    return ($row->products_count == 0 ? 0 : $row->products_count);
                    // return $row->getKioskProductCount($row->kiosk_id);
                })
                ->addColumn('assigned_products', function ($row) {
                    $id     = encrypt($row->kiosk_id);
                    $btn    = "<div class='text-center'><a href='machines-inventory/manage-mapping/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon' title='Product Map'><i class='fab fa-product-hunt fsize13'></i></a></div>";
                    return $btn;
                })
                ->addColumn('action', function($row) use ($accountType) {
                    if($accountType != 'ent'){
                        $id     = encrypt($row->kiosk_id);
                        $btn    = "<a href='machines-inventory/edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon' title='Edit'><i class='fas fa-edit fsize13'></i></a>";
                        return $btn;
                    }
                })
                ->rawColumns(['action', 'assigned_products'])
                ->make(true);
        }
    }

    public function getProductMap($id){

        try {
            $kioskID    = decrypt($id);
            $accountID  = Auth::user()->account_id;
        } catch (\Throwable $th) {
            return 'Fail';
        }

        $productData = [];
        $kioskData   = [];

        $data = Kiosk::where(['kiosk_id' => $kioskID])->first();
        if($data){
            $kioskData['machineName']   = $data->kiosk_identifier;
            $kioskData['machineCity']   = $data->kiosk_city;
            $kioskData['machineState']  = $data->kiosks_state;
            $kioskData['machineAlert']  = $data->kiosk_low_inv_threshold; 

            $binsArray  = explode(",", $data->template_bin_identity);
            foreach ($binsArray as $key => $value) {
                $product['binIndex']        = $value;
                $product['productID']       = '';
                $product['productName']     = 'No product assigned to this bin';
                $product['productSize']     = '-';
                $product['productImage']    = '';
                $product['productQty']      = '0';
                $product['productVariantName'] = '';
                
                $binIndex        = array_search($value, $binsArray, true);
                $bin_no          = $binIndex + 1;
                
                // get product variant details
                $productVariant = ProductVariant::select('product_variants.*', 'products.product_name',  'products.product_image', 'kiosk_product.bay_no', 'kiosk_product.price', 'kiosk_product.quantity')
                ->join('products', 'products.product_id', '=', 'product_variants.product_id')
                ->join('kiosk_product', 'kiosk_product.product_variant_id', '=', 'product_variants.product_variant_id')
                ->where('products.is_deleted','N')
                ->where('kiosk_product.kiosk__id', $kioskID)
                ->where('kiosk_product.bay_no', $bin_no)
                ->first();                
                
                if($productVariant){
                    $product['productID']       = $productVariant->product_id;
                    $product['productName']     = $productVariant->product_name;
                    $product['productSize']     = $productVariant->variant_sku;
                    $product['productImage']    = $productVariant->product_image;
                    $product['productQty']      = $productVariant->quantity;
                    $product['productVariantName'] = $productVariant->variant_name;
                }

                array_push($productData, $product);
            }            
        }

        $Detail['productData'] = $productData;
        $Detail['kioskData']   = $kioskData;
        return $Detail;
    }

    public function editMachine($id){

        try {
            $kioskID    = decrypt($id);
            $accountID  = Auth::user()->account_id;
        } catch (\Throwable $th) {
            return 'Fail';
        }

        $productData = [];
        $kioskData   = [];

        $data = Kiosk::where(['account_id' => $accountID, 'kiosk_id' => $kioskID])->first();
        if($data){
            $kioskData['machineID']     = $data->kiosk_id;
            $kioskData['machineName']   = $data->kiosk_identifier;
            $kioskData['machineCity']   = $data->kiosk_city;
            $kioskData['machineState']  = $data->kiosks_state;
            $kioskData['machineAlert']  = $data->kiosk_low_inv_threshold; 

            $binsArray  = explode(",", $data->template_bin_identity);
            foreach ($binsArray as $key => $value) {
                $product['binIndex']        = $value;
                $product['kioskProductID']  = '';
                $product['productID']       = '';
                $product['productVarintID'] = '';    
                $product['productQty']      = '';
                $product['productPrice']    = '';
                $product['VariantTypeList'] = [];
                
                $binIndex        = array_search($value, $binsArray, true);
                $bin_no          = $binIndex + 1;
                $product['bin_no'] = $bin_no;

                // get product variant details
                $productVariant = ProductVariant::select('product_variants.*', 'kiosk_product.bay_no', 'kiosk_product.price', 'kiosk_product.quantity', 'kiosk_product.kiosk_product_id')
                ->join('products', 'products.product_id', '=', 'product_variants.product_id')
                ->join('kiosk_product', 'kiosk_product.product_variant_id', '=', 'product_variants.product_variant_id')
                ->where('products.is_deleted','N')
                ->where('kiosk_product.kiosk__id', $kioskID)
                ->where('kiosk_product.bay_no', $bin_no)
                ->first();                
                
                if($productVariant){
                    $product['productID']       = $productVariant->product_id;
                    $product['productVarintID'] = $productVariant->product_variant_id;
                    $product['productQty']      = $productVariant->quantity;
                    $product['productPrice']    = $productVariant->price;
                    $product['kioskProductID']  = $productVariant->kiosk_product_id;
                    
                    $product['VariantTypeList'] = ProductVariant::where('product_id', $productVariant->product_id)->get();
                }

                array_push($productData, $product);
            }            
        }

        $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
        if($accountType == 'sub'){
            $act_id = Auth::user()->accountDetails->account_id_parent;
            $account_ids = Account::select('account_id')
                                   ->where('account_id_parent',$act_id)
                                   ->get()
                                   ->toArray();
            $account_id = array_column($account_ids, 'account_id');
            array_push($account_id,$act_id);           
            $getAllProduct = Product::whereIn('account_id', $account_id)->where('products.is_deleted','N')->get();
        }else{
            $getAllProduct = Product::where('account_id', $accountID)->where('products.is_deleted','N')->get();
        }

        
        
        $Detail['machiceData'] = $productData;
        $Detail['productData'] = $getAllProduct;
        $Detail['kioskData']   = $kioskData;
        return $Detail;
    }

    public function updateMachine(Request $request){

        try {
            $kioskID  = decrypt($request->kioskID);
            $accountID  = Auth::user()->account_id;

            foreach ($request->productID as $key => $value) {

                if($value != ''){
                    $check = KioskProduct::where([ 'kiosk__id' => $kioskID, 'kiosk_product_id' => $request->kioskProductID[$key] ])->first();
                    if($check){
                        KioskProduct::where([ 'kiosk__id' => $kioskID, 'kiosk_product_id' => $request->kioskProductID[$key] ])->update([
                            'product_variant_id'    => $request->productVarintName[$key],
                            'bay_no'                => $request->binNo[$key],
                            'quantity'              => $request->productQty[$key],
                            'price'                 => $request->productPrice[$key],
                        ]);
                    }else{
                        KioskProduct::create([
                            'kiosk__id'             => $kioskID,
                            'product_variant_id'    => $request->productVarintName[$key],
                            'bay_no'                => $request->binNo[$key],
                            'quantity'              => $request->productQty[$key],
                            'price'                 => $request->productPrice[$key],
                        ]);
                    }
                }else{
                    $check = KioskProduct::where([ 'kiosk__id' => $kioskID, 'kiosk_product_id' => $request->kioskProductID[$key] ])->first();
                    if($check){
                        KioskProduct::where([ 'kiosk__id' => $kioskID, 'kiosk_product_id' => $request->kioskProductID[$key] ])->delete();
                    }
                }
            }
                
            return true;
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function getVariantData(Request $request){
        try {
            $productID  = $request->productID;
            $accountID  = Auth::user()->account_id;
             $accountType = Auth::user()->accountDetails()->pluck('account_type')->first();
            if($accountType == 'sub'){
                $act_id = Auth::user()->accountDetails->account_id_parent;
                $account_ids = Account::select('account_id')
                                       ->where('account_id_parent',$act_id)
                                       ->get()
                                       ->toArray();
                $account_id = array_column($account_ids, 'account_id');
                array_push($account_id,$act_id);       
                $checkData = Product::where(['product_id' => $productID])
                                      ->whereIn('account_id', $account_id)->count();
              
            }else{
                $checkData = Product::where(['product_id' => $productID, 'account_id' => $accountID])->count();
            }
           //dd($checkData);
            if($checkData > 0){
                if($accountType == 'sub'){
                    $data = MyProduct::select('myproducts.price AS variant_price','product_variants.product_identifier','product_variants.variant_sku','product_variants.variant_name','product_variants.product_variant_id')
                                            ->join(with(new ProductVariant())->getTable(), 'myproducts.variant_id', '=', 'product_variants.product_variant_id')
                                            ->where(['myproducts.product_id' => $productID])
                                            ->where('myproducts.account_id' , $accountID);
                                     
                    if($data->count() == 0){
                          $data = ProductVariant::where('product_id', $productID);
                    }
                }else{
                    $data = ProductVariant::where('product_id', $productID);
                }
                if(!empty($request->variant_type)){
                    $data->whereRaw('UPPER(product_variants.variant_sku) = "'.strtoupper($request->variant_name).'"');
                }
                $data = $data->get();
                return $data;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }        
    }

    public function getVariantNameData(Request $request){
        try {
            $productID  = $request->productID;
            $accountID  = Auth::user()->account_id;
            
            $checkData = Product::where(['product_id' => $productID, 'account_id' => $accountID])->first();
            if($checkData){
                $data = ProductVariant::where(['product_id' => $productID, 'variant_sku' => $request->variant_type])->get();
                return $data;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }        
    }

    public function getVariantPriceData(Request $request){
        try {
            $productID  = $request->productID;
            $accountID  = Auth::user()->account_id;
            $accountType = Auth::user()->accountDetails()->pluck('account_type')->first();
            if($accountType == 'sub'){
                $act_id = Auth::user()->accountDetails->account_id_parent;
                $account_ids = Account::select('account_id')
                                       ->where('account_id_parent',$act_id)
                                       ->get()
                                       ->toArray();
                $account_id = array_column($account_ids, 'account_id');
                array_push($account_id,$act_id);       
                $checkData = Product::where(['product_id' => $productID])
                                      ->whereIn('account_id', $account_id)->count();
              
            }else{
                $checkData = Product::where(['product_id' => $productID, 'account_id' => $accountID])->count();
            }
            // $checkData = Product::where(['product_id' => $productID, 'account_id' => $accountID])->first();
            if($checkData > 0){
                if($accountType == 'sub'){
                    $data = MyProduct::select('myproducts.price AS variant_price','product_variants.product_identifier','product_variants.variant_sku','product_variants.variant_name','product_variants.product_variant_id')
                                            ->join(with(new ProductVariant())->getTable(), 'myproducts.variant_id', '=', 'product_variants.product_variant_id')
                                            ->where(['myproducts.product_id' => $productID])
                                            ->where('variant_id',$request->variant_id)
                                            ->where('myproducts.account_id' , $accountID)->get();
                    if($data->count() == 0){
                          $data = ProductVariant::where(['product_id' => $productID, 'product_variant_id' => $request->variant_id])->first()->toArray();
                    }else{
                        $data = $data->first()->toArray();
                    }
                }else{
                    $data = ProductVariant::where(['product_id' => $productID, 'product_variant_id' => $request->variant_id])->first()->toArray();
                }
                
                return $data['variant_price'];
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }        
    }

    //Machine
    public function getMachineList(Request $request){
        if ($request->ajax()) {
            
            if($request->account_id != ''){
                $accountID = decrypt($request->account_id);
            }else{
                $accountID = Auth::user()->account_id;          
            }

            $data = Kiosk::select('kiosks.*', 'kiosk_model.model_name')->leftjoin('kiosk_model', 'kiosk_model.kiosk_model_id', '=', 'kiosks.model_id')->where('kiosks.account_id', $accountID);

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('model_name', function ($row) {
                    /*$modal = KioskModel::where('kiosk_model_id', $row->model_id)->first();
                    if($modal){*/
                        return !empty($row->model_name)?$row->model_name:'NA';
                   /* }*/
                })
                ->editColumn('pos_min_age', function ($row) {
                    if($row->pos_min_age == ''){
                        return 0;
                    }
                })
                ->editColumn('kiosk_status', function ($row) {
                    if($row->kiosk_status == 'Y'){
                        $status = "<button title='Active' class='btn btn-success btn-sm'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' class='btn btn-danger btn-sm'>Inactive</button>";
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $id     = encrypt($row->kiosk_id);
                    $btn    = "<a href='".url('app/machines/edit/'.$id)."' class='btn btn-sm btn-primary btn-text-primary btn-icon' title='Edit'><i class='fas fa-edit fsize13'></i></a>";
                    return $btn;
                })
                ->rawColumns(['action', 'kiosk_status'])
                ->make(true);
        }
    }

    public function editMachines($id){

        try {
            $kioskID    = decrypt($id);
            $accountID  = Auth::user()->account_id;
        } catch (\Throwable $th) {
            return 'Fail';
        }

        $Detail['kiosk']    = Kiosk::find($kioskID);
        $Detail['user']     = User::where('account_id', $accountID)->first();
        $Detail['models']   = KioskModel::orderby('model_name')->get();
        $Detail['promo']    = Promo::where(['account_id' => $accountID, 'promo_status' => 'Y'])->get();
        //$Detail['selpromo'] = KioskPromo::where('kiosk_id', $kioskID)->get();
        $selpromo = KioskPromo::where('kiosk_id', $kioskID)->get();
        $Detail['currunt_selpromo'] = KioskPromo::where('kiosk_id', $kioskID)->where('optin_yn', 'Y')->first();
        $Detail['selpromo'] = $selpromo->implode('promo_id', ',');

        $Detail['accountFlag'] = 0;
        if($Detail['kiosk']->account_id != $accountID){
            $Detail['accountFlag'] = 1;
        }
        return $Detail;
    }

    public function updateMachines(Request $request){
        $accountID  = Auth::user()->account_id;
        $response['accountFlag'] = 0;
        if($request->account_id != $accountID){
            $response['accountFlag'] = 1;
        }
        
        try{
            $input = $request->all();           
            $input['modified_at']       = round(microtime(true) * 1000);
            $searchInput['kiosk_id']    = $input['kiosk_id'];
            Kiosk::updateorCreate($searchInput, $input);

            if (isset($input['promotions']) && !empty($input['promotions'][0])) {
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

            /*if(isset($input['kiosk_promo_id']) && count($input['kiosk_promo_id']) > 0){
                KioskPromo::where('kiosk_id', $searchInput['kiosk_id'])->delete();

                foreach ($input['kiosk_promo_id'] as $key => $value) {
                    KioskPromo::create([
                        'kiosk_id' => $searchInput['kiosk_id'],
                        'promo_id' => $value,
                    ]);
                }
            }*/

            $response['success'] = true;
        }catch(\Exception $e){
            $response['message'] = $e;
            $response['success'] = false;
        }
        return $response;
    }

    public function addMachines(Request $request){

        try {
            $accountID = decrypt($request->account_id);

            $check = Kiosk::where('kiosk_identifier', $request->kiosk_identifier)->first();
            if($check){
                return 'Error';
            }

            $tempBin = '{"TemplateId":"'.$request->kiosk_identifier.'","seqNum":6,"data":[{"BinId":"BIN 1","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10},{"BinId":"BIN 2","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10},{"BinId":"BIN 3","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10}]},{"BinId":"BIN 4","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10}]},{"BinId":"BIN 5","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10}]},{"BinId":"BIN 6","x":5,"y":10,"z_offset":10,"product_height":10,"max_stock":10}]}';
            
            Kiosk::create([
                'account_id'                => $accountID,
                'kiosk_identifier'          => $request->kiosk_identifier,
                'kiosk_status'              => 'N',
                'kiosk_street'              => $request->kiosk_street,
                'kiosk_city'                => $request->kiosk_city,
                'kiosks_state'              => $request->kiosks_state,
                'kiosk_country'             => $request->kiosk_country,
                'kiosk_zip'                 => $request->kiosk_zip,
                'kiosk_timezone'            => $request->kiosk_timezone,
                'kiosk_low_inv_threshold'   => 0,
                'kiosk_tax_rate'            => 0,
                'pos_min_age'               => 0,
                'created_at'                => Config::get('constants.CURRENTEPOCH'),
                'modified_at'               => Config::get('constants.CURRENTEPOCH'),
                'template_name'             => $request->kiosk_identifier.' template',
                'template_description'      => $request->kiosk_identifier.' default template',
                'template_created_dt'       => Config::get('constants.CURRENTEPOCH'),
                'template_bin_count'        => 6,
                'template_bin_identity'     => 'BIN 1,BIN 2,BIN 3,BIN 4,BIN 5,BIN 6',
                'template_json'             => json_encode($tempBin),
                'template_status'           => 'N',
                'language'                  => 'English',
                'currency'                  => 'USD',
                'alert_email_yn'            => 'Y',
            ]);

            $inputData['pre_account_name']  = '';
            $inputData['sub_account_id']    = $accountID;
            $inputData['sub_account_name']  = '';
            $inputData['sub_account_email'] = '';
            $inputData['machine_name']      = $request->kiosk_identifier;
            $subAccount = Account::where('account_id', $accountID)->with(['user'])->first();
            if($subAccount){
                $inputData['sub_account_name']  = $subAccount->account_name;
                $inputData['sub_account_email'] = $subAccount->user->email;

                $prtAccount = Account::where('account_id', $subAccount->account_id_parent)->first();
                if($prtAccount){
                    $inputData['pre_account_name'] = $prtAccount->account_name;
                }
            }

            $ADMIN_EMAIL = CommonHelper::ADMIN_EMAIL();
            Mail::to($ADMIN_EMAIL)->send(new CreateMachine($inputData));
            // \Log::info("Account Creation email failed for ".$input['email']);            

            return 'Success';
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }
}