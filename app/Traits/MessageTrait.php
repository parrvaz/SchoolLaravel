<?php

namespace App\Traits;

use Illuminate\Support\Facades\Lang;

trait MessageTrait
{
    public function successMessage(){
        return response()->json([
            'message' => Lang::get('responses.success'),
            'status' => 'success',
        ], 200);
    }
    public function error($type="data",$status=422): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => Lang::get('responses.error.'.$type),
            'status' => 'error',
        ], $status);
    }

}
