<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamScheduleSchoolResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'exam_schedule' => $this->exam_schedule,
            'teacher' => $this->teachers->pluck('name')[0],
            'students' => $this->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'parent_name'=>$student->parent->name,
                    'email'=>$student->parent->email,
                ];
            }),
        ];
    }
}

