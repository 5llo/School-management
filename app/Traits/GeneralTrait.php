<?php

namespace App\Traits;

trait GeneralTrait
{

    protected function successResponse($data, $message = null, $code = 200, $token = null)
    {
        $response = [
            'status' => 'success',
           
           'data' => $data,        ];

        if ($message) {
            $response['message'] = $message;
        }
        if ($token) {
            $response['token'] = $token; 
        }

        return response()->json($response, $code);
    }


    protected function errorResponse($message = null, $code)
    {
        return response()->json([
         'status'=>'failure',
            'message' => $message,
            'data' => null
        ], $code);
    }
}
