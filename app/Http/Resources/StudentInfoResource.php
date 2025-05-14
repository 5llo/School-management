<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
         return [
           'student_id'=>$this->id,
            'name' => $this->name,
            'parent_name' => $this->parent->name,  
            'parent_phone' => $this->parent->phone,  
            
        ];
    }
}
