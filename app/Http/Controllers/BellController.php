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


        if ($request->schoolGrade->school->bells()->count() > 0)
            return $this->error("storeBefore");

        $items=[];
        foreach ($validation->list as $item){
            $items[] = [
                'school_id' =>$request->schoolGrade->school_id,
                'order' => $item['order'],
                'startTime' =>$item['startTime'],
                'endTime' => $item['endTime'],
            ];
        }

        $bell = Bell::insert($items);
        return $this->successMessage();
    }

    public function update(Request $request, BellStoreValidation $validation,$schoolGrade){

        return DB::transaction(function () use($validation,$request,$schoolGrade) {
            $orders = array_column( $validation->list,"order");
            if (count($orders) !== count(array_unique($orders)))
                return $this->error("orderRepeat");

            $diff = array_diff(
                $request->schoolGrade->school->bells->pluck("id")->toArray(),
                array_column( $validation->list,"id"));

            if (count($diff) != 0)
                return $this->error();

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
                        'school_id' => $request->schoolGrade->school_id,
                        'order' => $item['order'],
                        'startTime' => $item['startTime'],
                        'endTime' => $item['endTime'],
                    ]);
                }
            }

            return (new BellCollection($request->schoolGrade->school->bells))
                ->additional(['message' => "با موفقیت تغییر کرد"]);
        });

    }

    public function show(Request $request){
        return new BellCollection($request->schoolGrade->school->bells);
    }

    public function delete(Request $request,$schoolGrade,Bell $bell){

        if ($bell->absents()->count() > 0)
            return $this->error("hasAbsent");

        if ($bell->schedules()->count() > 0)
            return $this->error("hasSchedule");

        $bell->delete();
        return (new BellCollection($request->schoolGrade->school->bells))
            ->additional(['message' => "با موفقیت حذف شد"]);
    }
}
