<?php

namespace App\Http\Controllers;

use App\Http\Resources\Grade\GradeMItemResource;
use App\Http\Resources\Grade\MenuItemCollection;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class MenuItemController extends Controller
{
    public function show(Request $request){
        return new GradeMItemResource(auth()->user());
    }
}
