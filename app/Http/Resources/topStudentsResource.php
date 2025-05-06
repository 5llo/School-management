<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class topStudentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'student_id' => $this->student_id,
            'session_id' => $this->session_id,
            'name' => $this->student->name,
            'oral_grade' => $this->oral_grade,
        ];
    }
}
