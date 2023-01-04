<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Auth;

class CommonController extends Controller
{
    public function fileUpload(Request $request){
        $accountID = Auth::user()->account_id;

        if($request->hasFile('qqfile')) {
            $files      = $request->file('qqfile');
            $name       = time().$files->getClientOriginalName();
            $filePath   = $accountID.'/temp/'.$name;
            Storage::disk('s3')->put($filePath, file_get_contents($files), 'public');   

            $resultArr['success']      = true;
            $resultArr['type']         = 'success';
            $resultArr['title']        = 'S3 Uploading';
            $resultArr['message']      = 'file uploaded successfully';
            $resultArr['location']     = Storage::disk('s3')->url($filePath);
            $resultArr['locationName'] = $name;

            echo json_encode($resultArr, JSON_NUMERIC_CHECK);     
        }
    }

    public function deleteUpload(Request $request){

        $accountID = Auth::user()->account_id;
        $filePath   = $accountID.'/temp/'.$request->filename;
        Storage::disk('s3')->delete($filePath); 

        $resultArr['success']      = true;
        $resultArr['type']         = 'success';
        echo json_encode($resultArr, JSON_NUMERIC_CHECK); 
    }

}
