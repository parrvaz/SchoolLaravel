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

    public function errorFatherPhone(){
        return response()->json([
            'message' => Lang::get('responses.error.fatherPhone'),
            'status' => 'error',
        ], 422);
    }

    public function errorStoreBefor(){
        return response()->json([
            'message' => Lang::get('responses.error.storeBefore'),
            'status' => 'error',
        ], 422);
    }

    public function errorNoTHavePlan(){
        return response()->json([
            'message' => Lang::get('responses.error.haveNotPlan'),
            'status' => 'error',
        ], 422);
    }

    public function errorHasAbsent(){
        return response()->json([
            'message' => Lang::get('responses.error.haveNotPlan'),
            'status' => 'error',
        ], 422);
    }

    public function errorHasSchedule(){
        return response()->json([
            'message' => Lang::get('responses.error.hasSchedule'),
            'status' => 'error',
        ], 422);
    }
}
