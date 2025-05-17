<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'bus_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'school_id' => $this->school->name,
            'bus_number'=>$this->bus_number,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phone' => $this->phone,
            'bus_capacity' => $this->bus_capacity,
            'number_of_students' => $this->students()->count(),
        ];
    }
}
