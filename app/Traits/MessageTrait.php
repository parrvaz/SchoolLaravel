<?php

namespace App\Traits;

use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

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

    public function errorUnauthorised(){
        return response()->json([
            'message' => Lang::get('responses.error.unauthorised'),
            'status' => 'error',
        ], 422);
    }

    public function errorDontExist(){
        return response()->json([
            'message' => Lang::get('responses.response.dontExist'),
            'status' => 'error',
        ], 401);
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
            'message' => Lang::get('responses.error.hasAbsent'),
            'status' => 'error',
        ], 422);
    }

    public function errorHasStudent(){
        return response()->json([
            'message' => Lang::get('responses.error.hasStudent'),
            'status' => 'error',
        ], 422);
    }

    public function errorHasSchedule(){
        return response()->json([
            'message' => Lang::get('responses.error.hasSchedule'),
            'status' => 'error',
        ], 422);
    }

    public function permissionDeniedForUser()
    {
        return response()->json([
            'message' => Lang::get('responses.error.permissionForUser'),
            'status' => 'error',
        ], 403);
    }


    /**
     * @throws ValidationException
     */
    public function permissionDenied()
    {
        $error = \Illuminate\Validation\ValidationException::withMessages([
            'user' => [Lang::get('responses.error.permission')],
        ]);
        throw $error;
//        return response()->json([
//            'message' => Lang::get('responses.error.permission'),
//            'status' => 'error',
//        ],403);
    }
}
