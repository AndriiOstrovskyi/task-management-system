<?php

namespace App\Traits;

trait HttpResponses
{
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Request was successful',
            'data' => $data,
            'message' => $message
        ], $code);
    }

    protected function error($message = null, $code = 400)
    {
        return response()->json([
            'status' => 'Error has occurred',
            'message' => $message
        ], $code);
    }
}
