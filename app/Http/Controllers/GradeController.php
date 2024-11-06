<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{

    public function operation(Request $request){

        $credentials = [
            'phone'    => "09124190719",
            'password' => "0441740111"
        ];

        return Auth::attempt($credentials);
    }


}
