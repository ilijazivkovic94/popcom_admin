<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomResponse
{
    public function sendResponse($result = [], $message){
        $response = [
            'status' => true,
        ];
        
        if($message!=''){
            $response['message'] = $message;
        }

        if(!empty($result)){
            $response['data'] = $result;
        }
        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404){
        $response = [
            'status'    => false,
            'message'   => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
