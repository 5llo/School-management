<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            //'student_name' => $this->student->name,
            'status' => $this->status,
            'process_number' => $this->process_number,
            'activity_name' => $this->activity->name,
            
        ];
    }
}
