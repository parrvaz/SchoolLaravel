<?php

namespace App\Http\Controllers;

use App\Http\Resources\Classroom\FieldCollection;
use App\Http\Resources\Classroom\FieldResource;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function show(){
        return new FieldCollection( Field::all());
    }
}
