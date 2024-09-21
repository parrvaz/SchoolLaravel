<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Classroom\ClassroomWithStudentsCollection;
use App\Http\Resources\Course\ContentCollection;
use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Teacher\TeacherCollection;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'sender_id'=> $this->user_id,
            'sender'=> $this->user->name,
            'subject'=> $this->subject,
            'body'=> $this->body,
        ];
    }
}
