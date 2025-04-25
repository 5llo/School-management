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
    public function toArray($request)
    {
        return [
            'schoolname' => $this->class->school->name,
            'division_name' => $this->division->name,
            'class_name' => $this->class->classsModel->name,
            'students_count' => $this->students->count(),
            'week_schedule' => json_decode($this->week_schedule, true),
            'exam_schedule' => json_decode($this->exam_schedule, true),
        ];
    }
}
