<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bell\BellStoreValidation;
use App\Http\Resources\Bell\BellCollection;
use App\Models\Bell;
use Illuminate\Http\Request;

class BellController extends Controller
{
    public function store(Request $request,BellStoreValidation $validation){



        $items=[];
        foreach ($validation->list as $item){
            $items[] = [
                'user_id' =>auth()->user()->id,
                'order' => $item['order'],
                'startTime' =>$item['startTime'],
                'endTime' => $item['endTime'],
            ];
        }

        $bell = Bell::insert($items);
        return $bell;
    }

    public function update(BellStoreValidation $validation,$userGrade,Bell $bell){
       $bell->update([
            "order"=>$validation->order,
            "startTime"=>$validation->startTime,
            "endTime"=>$validation->endTime
        ]);

        return $bell;
    }

    public function show(Request $request){
        return new BellCollection($request->userGrade->user->bells);
    }

    public function delete($userGrade,Bell $bell){
        $bell->delete();
        return $this->successMessage();
    }
}
