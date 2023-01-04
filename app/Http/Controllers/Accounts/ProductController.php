<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;

use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ProductVariant;
use App\Traits\ProductTrait;

class ProductController extends Controller
{
    use ProductTrait;

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title = "Products";
         $accType = Auth::user()->accountDetails->account_type;
        return view('apps.product.index', compact('page_title','accType'));
    }

    //List
    public function list(Request $request){
        return $this->getAllProduct($request);        
    }

    //Add
    public function create(){
        $page_title = "Add Product";
        return view('apps.product.create', compact('page_title'));
    }

    //Save
    public function store(Request $request){
        $data = $this->addProduct($request);  
        if($data == true){
            $msg = Config::get('constants.ProductAddSuccess');
            toastr()->success($msg, 'Product'); 
            return redirect('app/products');
        }else{
            $msg = Config::get('constants.ProductAddError');
            toastr()->error($msg); 
            return redirect('app/product/add')->withInput();
        }
    }

    //Delete
    public function delete(Request $request){
        $data = $this->deleteProduct($request);
        if($data == true){
            return response()->json(['status' => true, 'message' => Config::get('constants.ProductDeleteSuccess') ], 200);
        }else{
            return response()->json(['status' => false, 'message' => Config::get('constants.CommonError') ], 200);
        }
    }

    //Edit
    public function edit($id){
        $page_title     = "Edit Product";
        $productData    = $this->editProduct($id);
        if($productData == 'Fail'){
            toastr()->error('Your account has been disabled by admin'); 
            return redirect('app/products');
        }else{
            return view('apps.product.edit', compact('page_title', 'productData'));
        }
    }

    //Update
    public function update(Request $request){
        $data = $this->updateProduct($request);  
        if($data == true){
            $msg = Config::get('constants.ProductUpdateSuccess');
            toastr()->success($msg, 'Product');            
        }else{
            $msg = Config::get('constants.CommonError');
            toastr()->error($msg);
        }
        return redirect('app/products');
    }

    //Export
    public function export(){
        $fileName = date('m-d-Y').'_inventory_list'.'.xlsx';
        return Excel::download(new ProductExport, $fileName);
    }

    //Check Product Name
    public function checkName(Request $request){
        $data = $this->checkProductName($request);
        if($data == 'error'){
            return response()->json(false);
        }else{
            return response()->json(true);            
        }
    }

    public function checkIdentifier(Request $request){
        $data = $this->checkProductIdenitifer($request);
       // dd($data);
        if(count($data) > 0){
            return response()->json(['status' => false, 'data' => $data]);
        }else{
            return response()->json(['status' => true, 'data' => $data]);            
        }
    }

    //Retire
    public function retire(Request $request){
        $data = $this->retireProduct($request);
        if($data == true){
            return response()->json(['status' => true, 'message' => Config::get('constants.ProductRetireSuccess') ], 200);
        }else{
            return response()->json(['status' => false, 'message' => Config::get('constants.CommonError') ], 200);
        }
    }

    //Delete Image
    public function deleteImage(Request $request){
        $data = $this->deleteImageProduct($request);
        if($data == true){
            return response()->json(['status' => true], 200);
        }else{
            return response()->json(['status' => false, 'message' => Config::get('constants.CommonError')], 200);
        }
    }

    public function deleteVariant(Request $request){
        if($request->ajax()){
            if(!empty($request->productID) && !empty($request->variant_id)){
               $delete = ProductVariant::where(['product_id' => $request->productID, 'product_variant_id' => $request->variant_id])->delete();

                if($delete){
                    return response()->json(['status' => true], 200);
                }else{
                    return response()->json(['status' => false, 'message' => Config::get('constants.CommonError')], 200);
                }
            }
        }
    }

}
