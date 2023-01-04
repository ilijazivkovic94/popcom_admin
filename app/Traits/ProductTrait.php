<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;

use App\Models\Kiosk;
use App\Models\KioskProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\MyProduct;
use App\Models\Account;

use Mail;
use App\Mail\ProductRetire;

trait ProductTrait {

    public function getAllProduct(Request $request){
        if ($request->ajax()) {
            $order = ['products.product_id','products.product_name'];
            $logAccountID   = Auth::user()->account_id;

            $accountID[]    = Auth::user()->account_id;
            $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
            if($accountType == 'sub'){
                $parentAccountID = Auth::user()->accountDetails()->pluck('account_id_parent')->first();
                array_push($accountID, $parentAccountID);
            }

           // DB::enableQueryLog();
            $data = Product::select('products.*', DB::raw('GROUP_CONCAT(DISTINCT (product_variants.variant_sku),": ",product_variants.variant_name SEPARATOR"<br/>") as product_variant, (SELECT account_name from accounts where account_id=products.account_id) as parent_name'), 'kiosk_product.kiosk__id')
            ->rightJoin('product_variants', 'products.product_id', '=', 'product_variants.product_id')
            ->leftJoin('kiosk_product', 'kiosk_product.product_variant_id', '=', 'product_variants.product_variant_id')
            ->leftJoin('kiosks', 'kiosks.kiosk_id', '=', 'kiosk_product.kiosk__id')
            ->where('products.is_deleted','N')
            ->whereIn('products.account_id', $accountID)
            ->groupBy('products.product_id');


            // if($request->search['value'] != ''){
            //     $searchkey = $request->search['value'];
            //     $data = $data->whereRaw("( products.product_name LIKE '%".$searchkey."%' OR product_variants.product_identifier LIKE '%".$searchkey."%')");
            // }
            
            if($request->order[0]['column'] != ''){
                $data = $data->orderBy($order[$request->order[0]['column']],$request->order[0]['dir']);
            }else{
                $data = $data->orderBy('products.product_id','desc');
            }
         //  $data = $data->get()->toArray();
        //  dd(DB::getQueryLog());   
           
            return Datatables::of($data)
                ->addIndexColumn()
                ->filterColumn('product_name', function($query, $keyword) {
                    $query->whereRaw('( products.product_name LIKE ? OR product_variants.product_identifier LIKE ?)', ["%{$keyword}%","%{$keyword}%"]);
                   
                })
                ->filterColumn('product_variant', function($query, $keyword) {
                    $query->whereRaw('( product_variants.variant_sku LIKE ? OR product_variants.variant_name LIKE ? )', ["%{$keyword}%","%{$keyword}%"]);
                   
                   
                })
                ->editColumn('product_image', function ($row) {
                    return '<div class="symbol symbol-60 symbol-2by3 flex-shrink-0 mr-4"><div class="symbol-label" style="background-image: url('.$row->product_image.')"></div></div>';
                })
                ->editColumn('parent_name',function($row){
                    $parent_name = '';
                    $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
                    if($accountType == 'sub'){
                        $parentAccountID = Auth::user()->accountDetails()->pluck('account_id_parent')->first();
                        if($row->account_id == $parentAccountID){
                            $parent_name = $row->parent_name; 
                        }
                    }
                    return  $parent_name;
                })
                ->addColumn('product_machine', function ($row) {
                    return $row->getKioskName($row->account_id, $row->product_id);
                })
                ->editColumn('product_status', function ($row) {
                    if($row->product_status == 'Y'){
                        $status = "<button title='Active' data-id='$row->product_id' data-type='N' class='btn btn-success btn-sm status'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' data-id='$row->product_id' data-type='Y' class='btn btn-danger btn-sm status'>Inactive</button>";
                    }
                    return $status;
                })
                ->addColumn('action', function($row) use ($accountType, $logAccountID){
                    $count  = (isset($row->kiosk__id) && $row->kiosk__id != '' ? 1 : 0);                    
                    $id     = encrypt($row->product_id);
                    
                    $dataLink = '';
                    if($accountType == 'ent'){
                        $dataLink = "<a href='javascript:void(0);' class='btn btn-sm btn-primary btn-text-primary btn-icon ml-1 retProduct' title='Retire Product' data-id='$id' data-value='$row->product_retired_dt'><i class='far fa-clock fsize13'></i></a>";
                    }      
                    
                    if($logAccountID == $row->account_id){
                        $btn = "<a href='product/edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon' title='Edit'><i class='fas fa-edit fsize13'></i></a> ".$dataLink."<a href='javascript:void(0);' class='btn btn-sm btn-primary btn-text-primary btn-icon ml-1 delProduct' title='Delete' data-id='$id' data-count='$count'><i class='fas fa-trash-alt fsize13'></i></a>";
                    }else{
                        $btn = "<a href='product/edit/$id' class='btn btn-sm btn-primary btn-text-primary btn-icon' title='Edit'><i class='fas fa-edit fsize13'></i></a> ".$dataLink;
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'product_image', 'product_status', 'product_variant','parent_name'])
                ->make(true);
        }
    }

    public function addProduct(Request $request){
        try {
            
            $accountID = Auth::user()->account_id;

            $tempProduct = [
                'account_id'            => $accountID,
                'product_name'          => $request->product_name,
                'product_image'         => NULL,
                'product_description'   => $request->product_des,
                'product_status'        => 'Y',
                'is_deleted'            => 'N',
                'created_at'            => Config::get('constants.CURRENTEPOCH'),
                'modified_at'           => Config::get('constants.CURRENTEPOCH'),
            ];

            if($request->file != ''){
                
                $name               = $request->filename; 
                $s3DestinationFrom  = $accountID.'/temp/'.$name;
                $s3DestinationTo    = $accountID.'/product/'.$name;

                Storage::disk('s3')->move($s3DestinationFrom, $s3DestinationTo);
                $tempProduct['product_image'] = Storage::disk('s3')->url($s3DestinationTo);
            }

            $productData = Product::create($tempProduct);

            if($productData){
                foreach ($request->variant_type as $key => $value) {
                    $product_identifier = array_key_exists($key, $request->product_identifier) ? $request->product_identifier[$key] : '';
                    ProductVariant::create([
                        'product_id'            => $productData->product_id,
                        'product_identifier'    => $product_identifier,
                        'variant_sku'           => $request->variant_type[$key],
                        'variant_name'          => $request->variant_name[$key],
                        'variant_value'         => NULL,
                        'variant_price'         => $request->price[$key],
                    ]);
                }

                // if($request->hasFile('files')) {
                //     $files = $request->file('files');
                //     foreach($files as $key => $file){
                //         $file = $request->file('files');
                //         $name = time().$file[$key]->getClientOriginalName();
                //         $filePath = $accountID.'/products/'.$productData->product_id.'/'.$name;
                //         Storage::disk('s3')->put($filePath, file_get_contents($file[$key]), 'public');   

                //         ProductImage::create(['product_id' => $productData->product_id, 'image_url' => Storage::disk('s3')->url($filePath)]);
                //     }
                // }

                if($request->new_filename_mult != ''){
                    $image = explode(",", $request->new_filename_mult);
                    foreach ($image as $key => $value) {
                        $s3DestinationFrom  = $accountID.'/temp/'.$value;
                        $s3DestinationTo    = $accountID.'/product/'.$value;

                        Storage::disk('s3')->move($s3DestinationFrom, $s3DestinationTo);
                        ProductImage::create(['product_id' => $productData->product_id, 'image_url' => Storage::disk('s3')->url($s3DestinationTo)]);
                    }
                }
            }
            
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function deleteProduct(Request $request){

        try {
            $productID  = decrypt($request->productID);
            $accountID = Auth::user()->account_id;
            
            $checkData = Product::where(['product_id' => $productID, 'account_id' => $accountID])->first();
            if($checkData){
                //ProductImage::where('product_id', $productID)->delete();
                //ProductVariant::where('product_id', $productID)->delete();
               // MyProduct::where('product_id', $productID)->delete();
                Product::where(['product_id' => $productID, 'account_id' => $accountID])->update(['is_deleted'=>'Y']);
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
            $accountType = Auth::user()->accountDetails()->pluck('account_type')->first();
        } catch (\Throwable $th) {
            return 'Fail';
        }

        $productData = Product::where('product_id', $productID)->first();
        if($accountType == 'sub'){
            $variantData = MyProduct::select('myproducts.price AS variant_price','product_variants.product_identifier','product_variants.variant_sku','product_variants.variant_name','product_variants.product_variant_id')
                                    ->join(with(new ProductVariant())->getTable(), 'myproducts.variant_id', '=', 'product_variants.product_variant_id')
                                    ->where(['myproducts.product_id' => $productID])
                                    ->where('myproducts.account_id' , $accountID)->get();
            if($variantData->count() == 0){
                 // $variantData = ProductVariant::select('product_variants.*')->where(['product_variants.product_id' => $productID])->get();
                $variantData = ProductVariant::select('product_variants.*', 'kiosk_product.kiosk__id')->leftJoin('kiosk_product', 'kiosk_product.product_variant_id', '=', 'product_variants.product_variant_id')->where(['product_variants.product_id' => $productID])
                    ->groupBy('product_variants.product_variant_id')
                    ->get();
            }
        }else{
            // $variantData = ProductVariant::select('product_variants.*')->where(['product_variants.product_id' => $productID])->get();
            $variantData = ProductVariant::select('product_variants.*', 'kiosk_product.kiosk__id')->leftJoin('kiosk_product', 'kiosk_product.product_variant_id', '=', 'product_variants.product_variant_id')->where(['product_variants.product_id' => $productID])->groupBy('product_variants.product_variant_id')->get();
        }
       

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
            $accountType = Auth::user()->accountDetails()->pluck('account_type')->first();
            $tempProduct = [
                'product_name'          => $request->product_name,
                'product_description'   => $request->product_des,
                'modified_at'           => Config::get('constants.CURRENTEPOCH'),
            ];

           // dd($request->all());
            if($request->file != ''){
                
                $name               = $request->filename; 
                $s3DestinationFrom  = $accountID.'/temp/'.$name;
                $s3DestinationTo    = $accountID.'/product/'.$name;

                Storage::disk('s3')->move($s3DestinationFrom, $s3DestinationTo);
                $tempProduct['product_image'] = Storage::disk('s3')->url($s3DestinationTo);
            }

            Product::where('product_id', $productID)->update($tempProduct);
            if($accountType == 'sub' && $request->product_account_id != $accountID){
                $this->save_subaccount_product($request,$productID);
            }else{
                 foreach ($request->variant_type as $key => $value) {
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
            }
            
            // foreach ($request->variant_type as $key => $value) {
            //     $variantID = (isset($request->variartID[$key]) && $request->variartID[$key] != '' ? $request->variartID[$key] : 0);

            //     $check = ProductVariant::where(['product_variant_id' => $variantID, 'product_id' => $productID])->first();
            //     if($check){
            //         ProductVariant::where(['product_variant_id' => $variantID, 'product_id' => $productID])->update([
            //             'product_id'            => $productID,
            //             'product_identifier'    => $request->product_identifier[$key],
            //             'variant_sku'           => $request->variant_type[$key],
            //             'variant_name'          => $request->variant_name[$key],
            //             'variant_value'         => NULL,
            //             'variant_price'         => $request->price[$key],
            //         ]);
            //     }else{
            //         ProductVariant::create([
            //             'product_id'            => $productID,
            //             'product_identifier'    => $request->product_identifier[$key],
            //             'variant_sku'           => $request->variant_type[$key],
            //             'variant_name'          => $request->variant_name[$key],
            //             'variant_value'         => NULL,
            //             'variant_price'         => $request->price[$key],
            //         ]);
            //     }
            // }
            //dd($request);
             if($request->new_file_mult != ''){
                $image = explode(",", $request->new_file_mult);
                foreach ($image as $key => $value) {
                    // $s3DestinationFrom  = $accountID.'/temp/'.$value;
                    // $s3DestinationTo    = $accountID.'/product/'.$value;

                    // Storage::disk('s3')->move($s3DestinationFrom, $s3DestinationTo);
                    ProductImage::create(['product_id' => $productID, 'image_url' => $value]);
                }
            }
                
            return true;
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function checkProductName(Request $request){

        try {
            $productName    = $request->product_name;
            $accountID      = Auth::user()->account_id;           
            
            $check = Product::where('product_name', $productName)->where('account_id', $accountID)->first();
            if($check){
                return 'error';
            }else{
                return '';
            }
        } catch (\Throwable $th) {
            return 'Fail';
        }
    }

    public function retireProduct(Request $request){

        try {
            $productID  = decrypt($request->productID);
            $accountID = Auth::user()->account_id;
            $orgName = Account::where('account_id', $accountID)->first();
            $checkData = Product::where(['product_id' => $productID, 'account_id' => $accountID])->first();
            if($checkData){
               Product::where(['product_id' => $productID, 'account_id' => $accountID])->update(['product_retired_dt' => $request->retDate]);

             $users = Account::select('users.email', 'accounts.account_name')->join('users', 'users.account_id', '=' , 'accounts.account_id')->where('account_id_parent', $accountID)->where('account_status', 'Y')->get();
             $variantData = ProductVariant::select('product_variants.*')->where(['product_variants.product_id' => $productID])->get();
             if($users->isNotEmpty()) {
                foreach ($users as $key => $user) {
                    try {
                        Mail::to($user->email)->send(new ProductRetire($user,$checkData,$variantData,$orgName));
                    }catch(\Exception $e){
                        \Log::info("machine pin generation email failed for ".$user->email);
                    }
                }
             }
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }        
    }

    public function deleteImageProduct(Request $request){

        try {

            if($request->imageID != ''){
                $productID = decrypt($request->productID);
                $checkData = ProductImage::where(['product_image_id' => $request->imageID, 'product_id' => $productID])->first();
                if($checkData){
                    $replaced = Str::replace('https://popcom-saas.s3.us-east-2.amazonaws.com/', '', $checkData->image_url);
                    \Storage::disk('s3')->delete($replaced);

                    ProductImage::where(['product_image_id' => $request->imageID, 'product_id' => $productID])->delete();
                    return true;
                }else{
                    return false;
                }
            }else{
                
                $productID = decrypt($request->productID);
                $accountID = Auth::user()->account_id;
                
                $checkData = Product::where('product_id', $productID)->where('account_id', $accountID)->first();
                if($checkData){
                    $replaced = Str::replace('https://popcom-saas.s3.us-east-2.amazonaws.com/', '', $checkData->product_image);
                    \Storage::disk('s3')->delete($replaced);

                    Product::where(['product_id' => $productID, 'account_id' => $accountID])->update(['product_image' => NULL]);
                    return true;
                }else{
                    return false;
                }
            }
        } catch (\Throwable $th) {
            return false;
        }        
    }

     public function checkProductIdenitifer(Request $request){

        try {
            $productIdentifier = $request->product_identifier;           
           
           // DB::enableQueryLog();
            $check = ProductVariant::select('product_variants.product_variant_id')
            ->join(with(new Product())->getTable(), 'products.product_id', '=', 'product_variants.product_id')
            ->where('product_variants.product_identifier', $productIdentifier);

            if(Auth::user()->accountDetails->account_type == 'ent'){
                $act_id         = Auth::user()->accountDetails->account_id;
                $account_ids    = Account::select('account_id')->where('account_id_parent', $act_id)->get()->toArray();

                $accountID      = array_column($account_ids, 'account_id');
                array_push($accountID, $act_id);    

                $check = $check->whereIn('products.account_id', $accountID);        
            }elseif(Auth::user()->accountDetails->account_type == 'sub'){
                $act_id         = Auth::user()->accountDetails->account_id_parent;
                $account_ids    = Account::select('account_id')->where('account_id_parent', $act_id)->get()->toArray();
                
                $accountID      = array_column($account_ids, 'account_id');
                array_push($accountID, $act_id);

                $check = $check->whereIn('products.account_id', $accountID);
            }
            
            $check = $check->get();

            if($check->count() > 0){
                if(!empty($request->pid) && !empty($request->variantId)){                  
                    $checkIdentifer = $check->toArray();
                    $getIdenfiefer  = array_column($checkIdentifer, 'product_variant_id');
                    if(in_array($request->variantId, $getIdenfiefer)){
                        return array();
                    }else{
                       return  $checkIdentifer;
                    }
                }else{
                   return $check->toArray();
                }                 
            }else{
                return array();
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    private function save_subaccount_product($request,$productID){
        $accountID = Auth::user()->account_id;
        $v=0;
        foreach ($request->variant_type as $key => $value) {
            $variantID = (isset($request->variartID[$key]) && $request->variartID[$key] != '' ? $request->variartID[$key] : 0);
            $variantdata = array(
                    'account_id'=>$accountID, 
                    'product_id'=>$productID,
                    'name'=>$request->product_name, 
                    'variant_id'=>$variantID, 
                    'price'=>$request->price[$key]
                  );

            $variantExist = MyProduct::select('myproduct_id')
                                        ->where('product_id',$productID)
                                        ->where('account_id',$accountID)
                                        ->where('variant_id',$variantID)
                                        ->get();
            if($variantExist->count() > 0){
               $productVariant = MyProduct::where('product_id',$productID)
                           ->where('account_id',$accountID)
                           ->where('variant_id',$variantID)
                           ->update($variantdata);
                if($productVariant){
                    $v++;
                }
            }else{
               $productVariant = MyProduct::insert($variantdata);
               if($productVariant){
                    $v++;
                }
            }

        }

        if($v == count($request->variant_type)){
            return true;
        }else{
            return false;
        }
    }   
}