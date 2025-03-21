<?php

namespace App\Traits;

use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function warningMessage($type="wasExists"){
        return response()->json([
            'message' => Lang::get('responses.warning.'.$type),
            'status' => 'success',
        ], 200);
    }

    public function throwExp($type="permissionForUser",$status=403): \Illuminate\Http\JsonResponse
    {
        abort($status, Lang::get('responses.error.'.$type));
    }

}
