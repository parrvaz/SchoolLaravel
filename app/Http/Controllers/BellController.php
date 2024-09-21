<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bell\BellStoreValidation;
use App\Http\Resources\Bell\BellCollection;
use App\Models\Bell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BellController extends Controller
{
    public function store(Request $request,BellStoreValidation $validation){


        if (auth()->user()->bells()->count() > 0)
            return $this->errorStoreBefor();

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
        return $this->successMessage();
    }

    public function update(Request $request, BellStoreValidation $validation,$userGrade){

        return DB::transaction(function () use($validation,$request) {

            $items = [];
            foreach ($validation->list as $item) {

                if ( $item['id'] ?? 0) {
                    Bell::find( $item['id'])->update([
                        'order' => $item['order'],
                        'startTime' => $item['startTime'],
                        'endTime' => $item['endTime'],
                    ]);
                } else {
                    Bell::create([
                        'user_id' => auth()->user()->id,
                        'order' => $item['order'],
                        'startTime' => $item['startTime'],
                        'endTime' => $item['endTime'],
                    ]);
                }
            }

            return (new BellCollection($request->userGrade->user->bells))
                ->additional(['message' => "با موفقیت تغییر کرد"]);
        });

    }

    public function show(Request $request){
        return new BellCollection($request->userGrade->user->bells);
    }

    public function delete(Request $request,$userGrade,Bell $bell){

        if ($bell->absents()->count() > 0)
            return $this->errorHasAbsent();

        if ($bell->schedules()->count() > 0)
            return $this->errorHasSchedule();

        $bell->delete();
        return (new BellCollection($request->userGrade->user->bells))
            ->additional(['message' => "با موفقیت حذف شد"]);
    }
}
