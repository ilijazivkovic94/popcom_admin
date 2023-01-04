<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;

use App\Traits\SubAccountTrait;

class SubAccountController extends Controller
{
    use SubAccountTrait;

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title = "Manage Sub-Account";
        return view('apps.sub-accounts.index', compact('page_title'));
    }

    //List
    public function list(Request $request){
        return $this->getAllSubAccount($request);        
    }

    //Add
    public function create(){
        $page_title = "Add Sub-Account";
        return view('apps.sub-accounts.create', compact('page_title'));
    }

    //Save
    public function store(Request $request){
        $data = $this->addSubAccount($request);  
        if ($data == 'email_error') {
            $msg = Config::get('constants.EXISTS_EMAIL');
            toastr()->error($msg); 
            return redirect('app/accounts/add');
        } else if($data == 'account_error'){
            $msg = Config::get('constants.EXISTS_ACCOUNT_NAME');
            toastr()->error($msg); 
            return redirect('app/accounts/add');     
        } else if($data == 'success'){
            $msg = Config::get('constants.SubAccountAddSuccess');
            toastr()->success($msg, 'Sub Account'); 
            return redirect('app/accounts')->with('popFlag', '1');
        }else{
            $msg = Config::get('constants.CommonError');
            toastr()->error($msg); 
            return redirect('app/accounts');
        }       
    }
}
