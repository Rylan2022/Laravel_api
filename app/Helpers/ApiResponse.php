<?php

namespace App\Helpers;

class ApiResponse 
{
    public static function success($data = [], $message = 'Success', $code = 200, $meta = [])
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'meta'    => $meta,
        ], $code);
    }

    public static function error($message = 'Error', $code = 500, $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
