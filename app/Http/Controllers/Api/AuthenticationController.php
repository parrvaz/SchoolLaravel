<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SMSController;
use App\Http\Requests\User\UserLoginValidation;
use App\Http\Requests\User\UserRegisterValidation;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\throwException;

class AuthenticationController extends Controller
{
    public function register(Request $request,UserRegisterValidation $registerValidation)
    {

        $formData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'hasChanged' => true,
        ];

        $formData['password'] = bcrypt($request->password);

        //otp create
        $otp = rand(1000, 9999);
        $formData['remember_token']= $otp;

        return DB::transaction(function () use($request,$formData,$otp) {
            $user = User::create($formData);

            $user->modelHasRole()->create([
                "model_id"=>$user->id,
                "role_id"=> config("constant.roles.manager"),
                "model_type"=>"App\Models\User",
                "idInRole"=>$user->id
            ]);

            $user->school()->create([
                "title"=> "مدرسه " . $user->name
            ]);

            (new SMSController)->sendOtp($otp,$request->phone);

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('passportToken')->accessToken,
                'message' => 'کد تأیید ارسال شد.'
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

        return $this->error("unauthorised",401);
    }

    public function changePassword(Request $request){

        $validated = $request->validate([
            'password' => 'required|string|min:8',
        ]);
        $user = auth()->user();
        if ($user->hasChanged)
            return $this->error("permissionForUser",403);
        return DB::transaction(function () use($validated,$user) {

            $user->update([
                "password" => bcrypt($validated['password']),
                "hasChanged"=>true,
            ]);

            return $this->successMessage();
        });
    }

    public function logout(Request $request){
        // دریافت توکن کاربر
        $token = $request->user()->token();
        // باطل کردن توکن
        $token->revoke();
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
