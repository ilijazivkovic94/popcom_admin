<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\CommonTrait;
use App\Traits\PromotionTrait;
use App\Models\Promo;

class PromotionController extends Controller
{
    use PromotionTrait, CommonTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->title = 'Promotion';
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
        return view('apps.promotion.index', compact('page_title'));
    }

     public function list(Request $request){
        return $this->getAllPromotion($request);        
    }

    public function create(){
        $page_title =  $this->title;
        return view('apps.promotion.create', compact('page_title'));
    }

    public function store(Request $request){
        $input = $request->all();
        $data = $this->savePromotion($request);
        if($data['success']){
            toastr()->success('Promotion details saved successfully!'); 
            return redirect('app/promotion');
        }
        else{
            toastr()->error($data['message']); 
            return redirect('app/promotion/create')->withInput();
        }
    }

    public function edit($id){
        /*try{*/
            $id             = decrypt($id);
            $page_title     =  $this->title;
            $promo          = Promo::find($id);
            $asignedMachins = Promo::select('kiosks.kiosk_identifier', 'kiosks.kiosk_id')->join('kiosk_promos', 'kiosk_promos.promo_id', '=', 'promos.promo_id')->join('kiosks' ,'kiosks.kiosk_id', '=', 'kiosk_promos.kiosk_id')->where('promos.promo_id', $id)->get();

            return view('apps.promotion.edit', compact('page_title','promo','asignedMachins'));
        /*}catch(\Exception $e){
            toastr()->error('Something went wrong'); 
            return redirect('app/promotion');
        }*/
    }

    public function update(Request $request){
        $input = $request->all();
        $data = $this->updatePromotion($request);
       
        if($data['success']){
            toastr()->success('Promo details updated successfully!'); 
            return redirect('app/promotion');
        }else{
            toastr()->error($data['message']); 
            return redirect('app/promotion/edit/'.encrypt($input['promo_id']));
        }
    }

    public function updateStatus(Request $request){
        $this->modifyStatus($request, 'Promo', 'promo_status');
    }
}