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
            
        ];
    }
}
