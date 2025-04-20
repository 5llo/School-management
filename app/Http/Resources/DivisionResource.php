<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DivisionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'exam_schedule' => $this->exam_schedule,
            'week_schedule' => $this->week_schedule,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
