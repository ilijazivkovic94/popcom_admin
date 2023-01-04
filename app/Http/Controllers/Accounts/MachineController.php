<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;

use App\Models\User;
use App\Traits\MachineTrait;

class MachineController extends Controller
{
    use MachineTrait;

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //Start Machine Inventory
    public function indexInventory(Request $request){
        $page_title = "Manage Machine Inventory";
        return view('apps.machines-inventory.index', compact('page_title'));
    }

    //List
    public function listInventory(Request $request){
        return $this->getAllMachine($request);        
    }

    //View
    public function viewInventory($id){
        $page_title     = "Products Map";
        $productData    = $this->getProductMap($id);
        if($productData == 'Fail'){
            toastr()->error('Your account has been disabled by admin'); 
            return redirect('app/machines-inventory');
        }else{
            return view('apps.machines-inventory.view', compact('page_title', 'productData'));
        }
    }

    //Edit
    public function editInventory($id){
        $page_title     = "Edit Machine Inventory";
        $productData    = $this->editMachine($id);
        if($productData == 'Fail'){
            toastr()->error('Your account has been disabled by admin'); 
            return redirect('app/machines-inventory');
        }else{
            return view('apps.machines-inventory.edit', compact('page_title', 'productData'));
        }
    }

    //Update
    public function updateInventory(Request $request){
        $data = $this->updateMachine($request);
        if($data == true){
            $msg = Config::get('constants.MachineUpdateSuccess');
            toastr()->success($msg, 'Machine');
        }else{
            $msg = Config::get('constants.CommonError');
            toastr()->error($msg);
        }
        return redirect('app/machines-inventory');
    }
    //End Machine Inventory

    //Start Machine
    public function index(Request $request){
        $decrypt_id = '';
        $email      = '';
        $page_title = "Manage Machines";
        return view('apps.machines.index', compact('page_title', 'decrypt_id', 'email'));
    }

    //List
    public function list(Request $request){
        return $this->getMachineList($request);        
    }

    //Edit
    public function edit($id){
        $page_title     = "Edit Machine";
        $productData    = $this->editMachines($id);
        $decrypt_id     = $id;
        if($productData == 'Fail'){
            toastr()->error('Your account has been disabled by admin'); 
            return redirect('app/machines');
        }else{
            $kiosk      = $productData['kiosk'];
            $user       = $productData['user'];
            $models     = $productData['models'];
            $promo      = $productData['promo'];
            $selpromo   = $productData['selpromo'];
            $c_selpromo   = $productData['currunt_selpromo'];
            $AuthFlag   = $productData['accountFlag'];
            return view('apps.machines.edit', compact('page_title', 'models', 'kiosk', 'user', 'promo', 'selpromo', 'decrypt_id', 'AuthFlag', 'c_selpromo'));
        }
    }

    public function update(Request $request){
        $data = $this->updateMachines($request);
        if($data['success']){
            toastr()->success(Config::get('constants.MachinesUpdateSuccess'), 'Machines');  
            if($data['accountFlag'] == 1){
                return redirect('app/machines/list/'.encrypt($request->account_id) );
            }else{                
                return redirect('app/machines');
            }            
        }else{
            if($data['accountFlag'] == 1){
                toastr()->error(Config::get('constants.CommonError')); 
                return redirect('app/machines/list/'.encrypt($request->account_id) );
            }else{
                toastr()->error($data['message']); 
                return redirect('app/machines');
            }
            
        }
    }

    public function listByID(Request $request){
        try {
            $accountID = decrypt($request->id);
        } catch (\Throwable $th) {
            toastr()->error(Config::get('constants.SubAccountIDError')); 
            return redirect('app/accounts');
        }

        $decrypt_id = $request->id;
        $email      = User::userData($decrypt_id, 'email');
        $page_title = "Manage Machines";
        return view('apps.machines.index', compact('page_title', 'decrypt_id', 'email'));
    }

    //Add Machine
    public function create(Request $request){
        try {
            $accountID = decrypt($request->id);
        } catch (\Throwable $th) {
            toastr()->error(Config::get('constants.SubAccountIDError')); 
            return redirect('app/accounts');
        }

        $page_title     = "Add Machine";
        $decrypt_id     = $request->id;
        return view('apps.machines.add', compact('page_title', 'decrypt_id'));
    }

    //Save Machine
    public function store(Request $request){
        try {
            $accountID = decrypt($request->account_id);
        } catch (\Throwable $th) {
            toastr()->error(Config::get('constants.SubAccountIDError')); 
            return redirect('app/accounts');
        }

        $data = $this->addMachines($request);
        if($data == 'Success'){
            toastr()->success(Config::get('constants.MachineAddSuccess'), 'Machines'); 
            return redirect('app/machines/list/'.$request->account_id)->with('successFlag', '');  
        }else if($data == 'Error'){
            toastr()->error(Config::get('constants.MachineNameError')); 
            return redirect('app/machines/add/'.$request->account_id)->withInput();
        }else{
            toastr()->error(Config::get('constants.CommonError')); 
            return redirect('app/machines/list/'.$request->account_id);
        }
    }

    //End Machine

    //Get Varint
    public function getVariant(Request $request){
        $data = $this->getVariantData($request);
        if($data){
            return response()->json(['status' => true, 'variantData' => $data], 200);
        }else{
            return response()->json(['status' => false, 'variantData' => '', 'message' => Config::get('constants.MachineAddError') ], 200);
        }
    }

    //Get Varint Name
    public function getVarName(Request $request){
        $data = $this->getVariantNameData($request);
        if($data){
            return response()->json(['status' => true, 'variantData' => $data], 200);
        }else{
            return response()->json(['status' => false, 'variantData' => '', 'message' => Config::get('constants.MachineAddError') ], 200);
        }
    }

    //Get Varint Price
    public function getVarPrice(Request $request){
        $data = $this->getVariantPriceData($request);
        if($data){
            return response()->json(['status' => true, 'variantData' => $data], 200);
        }else{
            return response()->json(['status' => false, 'variantData' => '', 'message' => Config::get('constants.MachineAddError') ], 200);
        }
    }
}
