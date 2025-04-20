<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'post' => $this->post->description,
           'school' => $this->schoolClassesDivision->class->school->name,
           'school_classes' => $this->schoolClassesDivision->class->classsModel->name,
           'school_classes_division' => $this->schoolClassesDivision->division->name,
           

           
        ];
        
    }
}
