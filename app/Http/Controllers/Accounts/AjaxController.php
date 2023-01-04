<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;


use App\Traits\CommonTrait;


class AjaxController extends Controller
{	
	use CommonTrait;

	public function getAccountMachines(Request $request){
		if($request->ajax()){

			$machines = $this->getMachines($request->account_id);
			if($machines->count() > 0){
				$machine = $machines->toArray();
			}else{
				$machine = array();
			}
			return response()->json(['machines'=>$machines]);
		}
	}

}