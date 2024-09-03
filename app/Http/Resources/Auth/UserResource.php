<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\Score\ContentCollection;
use App\Http\Resources\Score\StudentScoreCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_admin' => $this->id == 7 ?true :false ,
        ];
    }
}
