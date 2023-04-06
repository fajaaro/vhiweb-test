<?php

namespace App\Traits;

trait ResponseTrait
{
    protected function success($data = null, $status = 200)
    {
        return response([
            'success' => true,
            'data' => $data,
            'error' => null,
        ], $status);
    }

    protected function failure($errorMessage, $status = 422)
    {
        return response([
            'success' => false,
            'data' => null,
            'error' => $errorMessage,
        ], $status);
    }
}