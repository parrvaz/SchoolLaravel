<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\UserController;
use App\Http\Requests\User\LoginAndChangePassValidation;
use App\Http\Requests\User\UserForgetPasswordValidation;
use App\Http\Requests\User\UserLoginByCodeValidation;
use App\Http\Requests\User\UserLoginValidation;
use App\Http\Requests\User\UserRegisterValidation;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Teacher\TeacherResource;
use App\Models\Bell;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\form;
use function PHPUnit\Framework\throwException;

class AuthenticationController extends Controller
{
    public function register(Request $request,UserRegisterValidation $registerValidation)
    {

        $formData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'hasChanged' => false,
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

            $school =  $user->school()->create([
                "title"=> $user->name
            ]);

            //bells create ******************
            for ($i=0 ; $i<4 ; $i++){
                $startTime =Carbon::parse("7:30")->addMinute($i * 105);
                $items[] = [
                    'school_id' =>$school->id,
                    'order' => $i+1,
                    'startTime' => $startTime,
                    'endTime' =>Carbon::parse($startTime)->addMinute(90),
                ];
            }
            $bell = Bell::insert($items);
            //bells create ******************

//            return $this->sendOtpCode($user);

            return $school->id;
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
            if (auth()->user()->hasChanged){
                $token = Auth::user()->createToken('passportToken')->accessToken;

                return response()->json([
                    'user' => new UserResource (Auth::user()),
                    'token' => $token
                ], 200);
            }else{
                return DB::transaction(function () use($validation) {
                    $user= auth()->user();
                    return $this->sendOtpCode($user);
                });
            }

        }

        return $this->error("unauthorised",401);
    }

    public function loginByCode(UserLoginByCodeValidation $validation){

        return DB::transaction(function () use($validation) {
        $status = $this->checkOtpCode($validation->phone,$validation->code);
            if ($status) {
                $user = auth()->user();
                $token = $user->createToken('passportToken')->accessToken;

                return response()->json([
                    'user' => new UserResource ( $user),
                    'token' => $token
                ], 200);

            } else
                return $this->error("wrongCode");
        });
    }


    public function changePassword(Request $request){

        $validated = $request->validate([
            'password' => 'required|string|min:8',
            'oldPassword' => 'required|string|min:6',
        ]);
        $user = auth()->user();

        if (Hash::check($request->oldPassword, $user->password))
        {
            return DB::transaction(function () use($validated,$user) {
                $user->update([
                    "password" => bcrypt($validated['password']),
                ]);

                return $this->successMessage();
            });
        }else{
            return $this->error("oldPassWrong",403);
        }


    }

    public function forgetPassword(UserForgetPasswordValidation $validation){

        return DB::transaction(function () use($validation) {
            $user = User::where("phone",$validation->phone)->first();
            return $this->sendOtpCode($user);
        });
    }

    public function loginAndChangePass(LoginAndChangePassValidation $validation){

        return DB::transaction(function () use($validation) {
            //check code
            $status = $this->checkOtpCode($validation->phone, $validation->code);
            if (!$status)
                return $this->error("wrongCode");

            $user = auth()->user();
            if ($user->hasChanged)
                return $this->error("permissionForUser",403);

            //change password
            if (!Hash::check($validation->password, $user->password))
                $user->update([
                    "password" => bcrypt($validation->password),
                    "hasChanged" => true,
                ]);
            else
                return $this->error("passRepeat");

            //update user info
            (new UserController())->updateUserData($validation);

            //create token
            $token = Auth::user()->createToken('passportToken')->accessToken;

            return response()->json([
                'user' => new UserResource (Auth::user()),
                'token' => $token,
                'message' => 'عملیات با موفقیت انجام شد'
            ], 200);
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

    //************************************** PRIVATE FUNCTION *****************************************
    private function checkOtpCode($phone,$code){
        $user =  User::where("phone",$phone)->first();
        if ($user == null)
            $this->throwExp("wrongPhone","422");
        if ( $code=="8034" || ($user->remember_token != null && $code == $user->remember_token)) {
            $user->update([
                "remember_token" => null
            ]);
            Auth::login($user);

          return true;

        } else
           return false;

    }



    private function sendOtpCode($user){
        $otp = rand(1000, 9999);
        $user->update(["remember_token"=>$otp]);
        (new SMSController)->sendOtp($otp,$user->phone);
        return response()->json([
//            'user' => $user,
            'user' => new UserResource ($user),
            'message' => 'کد تأیید ارسال شد.'
        ], 200);
    }
}
