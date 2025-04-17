<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContestResource;

class ContestStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'result' => $this->result,
            'student_name'=>$this->student->name,
            'contest' => new ContestResource($this->contest),
            
        ];
    }
}
