<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\CommonTrait;
use App\Traits\AccountMachineTrait;
use App\Traits\SubscriptionTrait;

use App\Models\Kiosk;
use App\Models\KioskModel;
use App\Models\KioskPromo;
use App\Models\User;
use App\Models\Promo;

use Mail;
use App\Mail\ActivateMachine;
use App\Mail\DeactivateMachine;
use App\Mail\RegeneratePIN;
use App\Mail\ActivateMachineParent;
use App\Mail\DeactivateMachineParent;

class MachineController extends Controller
{
    use CommonTrait, AccountMachineTrait, SubscriptionTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->title = 'Manage Machines';
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id)
    {
        $page_title = $this->title;
        $decrypt_id = $id;
        try{
            $id = decrypt($id);
        }catch(\Exception $e){
            toastr()->error('Something went wrong');  
            return redirect('home');
        }
        $models = KioskModel::orderby('model_name')->get();
        $user = User::find($id);

        return view('admin.machine.index', compact('page_title','id','decrypt_id','models','user'));
    }

    public function list(Request $request, $id){
        return $this->getAccountMachine($request, $id);        
    }

    public function create($id){
        $page_title = $this->title;
        $decrypt_id = $id;
        try{
            $id = decrypt($id);
        }catch(\Exception $e){
            toastr()->error('Something went wrong'); 
            return redirect('admin/machine/'.$decrypt_id);
        }
        $user = User::find($id);
        $models = KioskModel::orderby('model_name')->get();
        $promotions = Promo::where('account_id',$user->account_id)->get();
        return view('admin.machine.create', compact('page_title','user','models','decrypt_id','promotions'));
    }

    public function store(Request $request){
        $data = $this->saveMachine($request);
        $decrypt_id = encrypt($request->id);
        if($data['success']){
            toastr()->success('Machine details saved successfully!'); 
            return redirect('admin/machine/'.$decrypt_id);
        }else{
            toastr()->error($data['message']); 
            return redirect('admin/machine/create/'.$decrypt_id)->withInput();
        }
    } 

    public function edit($id){
        $page_title = $this->title;
        
        try{
            $id = decrypt($id);
            $kiosk = Kiosk::find($id);
            $user = User::where('account_id',$kiosk->account_id)->first();
            $models = KioskModel::orderby('model_name')->get();
            $decrypt_id = encrypt($user->id);
            $promotions = Promo::where('account_id',$user->account_id)->get();
            $kiosk_promo = KioskPromo::where('kiosk_id', $id)->get();
            $current_promo_id = KioskPromo::where('kiosk_id', $id)->where('optin_yn', 'Y')->first();
            $kiosk_promo_id = $kiosk_promo->implode('promo_id',',');
            return view('admin.machine.edit', compact('page_title','models','kiosk','user','decrypt_id','promotions','kiosk_promo_id','current_promo_id'));
        }catch(\Exception $e){
            toastr()->error('Something went wrong'); 
            return redirect('admin/machine/'.$decrypt_id);
        }
    }

    public function update(Request $request){
        $kiosk = Kiosk::find($request->kiosk_id);
        $previous_pin = $kiosk->pos_pin;

        $data = $this->updateMachine($request);
        $decrypt_id = encrypt($request->user_id);
        if($data['success']){
            // if pin is change then send mail to account
            if($previous_pin!= $request->pos_pin){
                $accountDetails = $this->getParentAccount($kiosk->account_id);
                try{
                    Mail::to($accountDetails->email)->send(new RegeneratePIN($accountDetails,$kiosk));
                }catch(\Exception $e){
                    \Log::info("machine pin generation email failed for ".$accountDetails->email);
                }
            }

            toastr()->success('Machine details updated successfully!'); 
            return redirect('admin/machine/'.$decrypt_id);
        }else{
            toastr()->error($data['message']); 
            return redirect('admin/machine/'.$decrypt_id);
        }
    }

    public function updateStatus(Request $request){
        $kiosk = Kiosk::find($request->id);
        $userDetails = $this->getParentAccount($kiosk->account_id);
        // send machine activation email to account
        if($request->type == 'Y'){
            // first check if user has subscripbe plan or bypass. If subscription purchase added payment method or not
            $data = $this->checkUserSubscription($userDetails->id);
            if(!empty($data) && $data['status'] == 'success'){
                try{
                    Mail::to($userDetails->email)->send(new ActivateMachine($userDetails,$kiosk));
                }catch(\Exception $e){
                    \Log::info("machine activation email failed for ".$userDetails->email);
                }
                // check if account type is parent
                if($userDetails->accountDetails->account_type == 'sub'){
                    // send email to parent account
                    $parentDetails = $this->getParentAccount($userDetails->accountDetails->account_id_parent);
                    try{
                        Mail::to($parentDetails->email)->send(new ActivateMachineParent($parentDetails, $kiosk, $userDetails));
                    }catch(\Exception $e){
                        \Log::info("machine activation email failed for ".$parentDetails->email);
                    }
                }

                if($data['subscription'] == 'Yes'){
                     // activate subscription with qty 1
                    $this->addSubscriptionQuantity($userDetails->id);
                }
            }else{
                $resultArr['title'] = 'Error';
                $resultArr['message'] = $data['message'];
                echo json_encode($resultArr);
                exit;
            }
           
        }else{ //send machine deactivation email to account

            $data = $this->checkUserSubscription($userDetails->id);
            \Log::info("data ".json_encode($data));
            if(!empty($data) && $data['status'] == 'success'){
                if($data['subscription'] == 'Yes'){
                    $this->removeSubscriptionQuantity($userDetails->id);
                }
            }

            try{
                Mail::to($userDetails->email)->send(new DeactivateMachine($userDetails,$kiosk));
            }catch(\Exception $e){
                \Log::info("machine de-activation email failed for ".$userDetails->email);
            }

             // check if account type is parent
            if($userDetails->accountDetails->account_type == 'sub'){
                // send email to parent account
                $parentDetails = $this->getParentAccount($userDetails->accountDetails->account_id_parent);
                try{
                    Mail::to($parentDetails->email)->send(new DeactivateMachineParent($parentDetails, $kiosk, $userDetails));
                }catch(\Exception $e){
                    \Log::info("machine de-activation email failed for ".$parentDetails->email);
                }
            }
        }
        $this->modifyStatus($request, 'Kiosk', 'kiosk_status');
    }
}