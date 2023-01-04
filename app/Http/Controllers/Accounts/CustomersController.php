<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;

use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Account;

use App\Traits\CustomerTrait;

class CustomersController extends Controller
{
    use CustomerTrait;

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title = "Manage Customers";
        $getMachine = $this->getMachine();
        $subAccount = Account::where('account_id_parent', Auth::user()->accountDetails->account_id)->get();
        $getCount       = $this->getNullEmailCustomersCount();
        $uniqueCount    = $getCount['uniqueCount'];
        $remainingCount = $getCount['remainingCount'];
        return view('apps.customer.index', compact('page_title', 'getMachine', 'uniqueCount', 'remainingCount', 'subAccount'));
    }

    //List
    public function list(Request $request){
        return $this->getAllCustomer($request);        
    }

    //Export
    public function export(){
        $fileName = 'customer_'.Config::get('constants.CURRENTEPOCH').'.xlsx';
        return Excel::download(new CustomersExport, $fileName);
    }
}
