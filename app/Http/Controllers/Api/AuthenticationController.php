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

        $user = User::create($formData);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('passportToken')->accessToken
        ], 200);

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

        return response()->json([
            'error' => 'Unauthorised'
        ], 401);

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

    public function log(){
        throwException();
    }
}
