<?php

namespace App\Http\Controllers;

use App\Http\Resources\Grade\MenuItemCollection;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function show(Request $request){
        return  new MenuItemCollection(MenuItem::where("parent_id",null)->get());
    }
}
