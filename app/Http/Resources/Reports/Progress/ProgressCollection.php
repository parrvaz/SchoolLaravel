<?php

namespace App\Http\Resources\Reports\Progress;

use App\Http\Resources\Course\GroupCourseResource;
use App\Http\Resources\Course\ShortCourseCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProgressCollection extends ResourceCollection
{
    use ServiceTrait;
    protected $classExam;

    public function __construct($resource, $classExam)
    {
        parent::__construct($resource);
        $this->classExam = $classExam;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item){
            return[
                'id'=> $item->id ?? null,
                'date'=> self::gToJ( $item->date)?? null,
                'score'=> $item->score?? null,
                'expected'=> (int) $item->expected?? null,
                'average'=> $this->classExam->where("date",$item->date)->first()->score ?? null,
                'courses'=> new GroupCourseResource($item->course_ids),
            ];
        })->toArray();
    }
}
