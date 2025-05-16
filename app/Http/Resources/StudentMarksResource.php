<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentMarksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
       return [
          'subject_name'=>$this->subject->name,
          'subject_id'=>$this->subject->id,
           'oral_grade' => $this['oral_grade'],
            'homework_grade' => $this->homework_grade,
            'exam_grade' => $this->exam_grade,
            'attendance_grade' => $this['attendance_grade'] ?? null,
            'number_of_present' => $this['number_of_present'] ?? null,
            'number_of_absent' => $this['number_of_absent'] ?? null,
             'numberofearlyleave' => $this->number_of_late ?? 0,
        'numberoflate' => $this->number_of_early_leave ?? 0,

        ];
    }
}
