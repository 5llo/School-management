<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            
            'school_name' => $this->school->name,
            'class_name' => $this->classsModel->name,
            //'division_name' => $this->divisions->division->name,


         
        ];
    }
}
