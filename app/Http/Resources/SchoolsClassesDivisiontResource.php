<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolsClassesDivisiontResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
           
            'school_class' => [
                'name' => $this->class->school->name,
                
            ],
            'division' => [
                //'id' => $this->division->id,
                'name' => $this->division->name,
                'students_count' => $this->students->count(),
            ],
            'teachers' => $this->teachers->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                ];
            }),
            'week_schedule'=> json_decode($this->week_schedule, true),
            'exam_schedule'=> json_decode($this->exam_schedule, true),

            'students' => $this->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                ];
            }),
    
        ];
    
    }
}
