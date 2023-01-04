<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\CommonTrait;
use App\Traits\MachineModelTrait;
use App\Models\KioskModel;

class MachineModelController extends Controller
{
    use CommonTrait, MachineModelTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->title = 'Machine Model';
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $page_title = $this->title;
        return view('admin.machine_model.index', compact('page_title'));
    }

    public function list(Request $request){
        return $this->getAllMachineModel($request);        
    }

    public function create(){
        $page_title = $this->title;
        return view('admin.machine_model.create', compact('page_title'));
    }

    public function store(Request $request){
        $data = $this->saveMachineModel($request);

        if($data['success']){
            toastr()->success('Machine Model details saved successfully!'); 
            return redirect('admin/machine-model');
        }
        else{
            toastr()->error($data['message']); 
            return redirect('admin/machine-model/create')->withInput();
        }
    }

    public function edit($id){
        try{
            $id = decrypt($id);
            $page_title = $this->title;

            $model = KioskModel::find($id);
            return view('admin.machine_model.edit', compact('page_title','model'));
        }catch(\Exception $e){
            toastr()->error('Something went wrong'); 
            return redirect('admin/machine-model');
        }
    }

    public function update(Request $request){
        $data = $this->updateMachineModel($request);
        if($data['success']){
            toastr()->success('Machine model details updated successfully!'); 
            return redirect('admin/machine-model');
        }else{
            toastr()->error($data['message']); 
            return redirect('admin/machine-model');
        }
    }

    public function updateStatus(Request $request){
        $this->modifyStatus($request, 'KioskModel', 'model_avaialble_yn');
    }
}