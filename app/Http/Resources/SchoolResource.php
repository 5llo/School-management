<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $teachersCount = $this->teachers()->count();
       // $studentsCount = $this->teachers()->division()->students()->count();
      
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'bus_price' => $this->bus_price,
            'email' => $this->email,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'teachers_count' => $teachersCount,
           //'students_count' => $studentsCount,
            
        ];
    }
}
