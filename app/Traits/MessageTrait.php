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
            'message' => "wrong data",
            'status' => 'error',
        ], 422);
    }
}
