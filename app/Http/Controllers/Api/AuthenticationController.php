<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginValidation;
use App\Http\Requests\User\UserRegisterValidation;
use App\Http\Resources\Auth\UserResource;
use http\Exception\BadMethodCallException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\throwException;

class AuthenticationController extends Controller
{
    public function register(Request $request,UserRegisterValidation $registerValidation)
    {

        $formData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        $formData['password'] = bcrypt($request->password);

        return DB::transaction(function () use($request,$formData) {
            $user = User::create($formData);
            $user->modelHasRole()->create([
                "model_id"=>$user->id,
                "role_id"=> config("constant.roles.manager"),
                "model_type"=>"App\Models\User",
                "idInRole"=>$user->id
            ]);

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('passportToken')->accessToken
            ], 200);

        });

    }

    public function login(Request $request,UserLoginValidation $validation)
    {
        $credentials = [
            'phone'    => $request->phone,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials))
        {
            $token = Auth::user()->createToken('passportToken')->accessToken;

            return response()->json([
                'user' => new UserResource (Auth::user()),
                'token' => $token
            ], 200);
        }

        return $this->errorUnauthorised();


    }

    public function logout(Request $request){
        // دریافت توکن کاربر
        $token = $request->user()->token();
        // باطل کردن توکن
        $token->revoke();
        return $this->successMessage();
    }

    public function user(Request $request){
        return response()->json([
            'data' =>new UserResource( auth()->user()),
        ], 200);

    }

    public function update(){
        $user = auth()->user();



//        $user->update([
//            "name"=>
//        ]);
    }

    public function log(){
        throwException();
    }
}
