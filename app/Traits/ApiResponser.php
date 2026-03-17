<?php

namespace App\Traits;

trait ApiResponser
{
    private function success($data, $code = 200)
    {
        return response()->json($data, $code);
    }
}