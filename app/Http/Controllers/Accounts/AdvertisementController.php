<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;

use App\Traits\AdvertisementTrait;

class AdvertisementController extends Controller
{
    use AdvertisementTrait;

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title = "Manage Advertisements";
        return view('apps.advertisement.index', compact('page_title'));
    }

    //List
    public function list(Request $request){
        return $this->getAllAdvertisement($request);        
    }

    //Add
    public function create(){
        $page_title = "Add Advertisement";
        return view('apps.advertisement.create', compact('page_title'));
    }

    //Save
    public function store(Request $request){
        $data = $this->addAdvertisement($request);  
        if($data == true){
            $msg = Config::get('constants.AdvertAddSuccess');
            toastr()->success($msg, 'Advertisement'); 
            return redirect('app/advertisement');
        }else{
            $msg = Config::get('constants.CommonError');
            toastr()->error($msg); 
            return redirect('app/advertisement/add')->withInput();
        }
    }
    
    //Delete
    public function delete(Request $request){
        $data = $this->deleteAdvertisement($request);
        if($data == true){
            return response()->json(['status' => true, 'message' => Config::get('constants.AdvertDeleteSuccess') ], 200);
        }else{
            return response()->json(['status' => false, 'message' => Config::get('constants.CommonError') ], 200);
        }
    }

    //Check Advertisement Name
    public function checkName(Request $request){
        $data = $this->checkAdvertisementName($request);
        if($data == 'error'){
            return response()->json(false);
        }else{
            return response()->json(true);
            
        }
    }

    //Edit
    public function edit($id){
        $page_title     = "Edit Advertisement";
        $productData    = $this->editAdvertisement($id);
        $accountData    = $this->getAccountDetails($id);
        if($productData == 'Fail'){
            toastr()->error('Your account has been disabled by admin');
            return redirect('app/advertisement');
        }else{
            return view('apps.advertisement.edit', compact('page_title', 'productData', 'accountData'));
        }
    }

    //Update
    public function update(Request $request){
        $data = $this->updateAdvertisement($request);  
        if($data == true){
            $msg = Config::get('constants.AdvertUpdateSuccess');
            toastr()->success($msg, 'Advertisement');            
        }else{
            $msg = Config::get('constants.CommonError');
            toastr()->error($msg);
        }
        return redirect('app/advertisement');
    }

    //Delete Image
    public function deleteImage(Request $request){
        $data = $this->deleteImageAdvertisement($request);
        if($data == true){
            return response()->json(['status' => true], 200);
        }else{
            return response()->json(['status' => false, 'message' => Config::get('constants.CommonError')], 200);
        }
    }
}
