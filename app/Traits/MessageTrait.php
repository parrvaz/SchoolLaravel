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

    public function error(){
        return response()->json([
            'message' => Lang::get('responses.response.error'),
            'status' => 'error',
        ], 422);
    }

    public function errorStoreBefor(){
        return response()->json([
            'message' => Lang::get('responses.error.storeBefore'),
            'status' => 'error',
        ], 422);
    }
}
