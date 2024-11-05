<?php

namespace App\Http\Resources\Homework;

use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use function Laravel\Prompts\select;

class FileCollection extends ResourceCollection
{
    use ServiceTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item){
            return[
                'file' => $item->file ? url('storage/' . $item->file) : null,
            ];
        })->toArray();
    }
}
