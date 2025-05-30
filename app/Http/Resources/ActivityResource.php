<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $status = $this->date < now() ? 'منتهية' : 'نشطة';
        return [
            'name' => $this->name,
            //'description' => $this->description,
            'price' => $this->price,
            'date' => $this->date,
            'time' => $this->time,
            'start' => $this->start,
            'phone' => $this->phone,
            'status' => $status,
        ];
    }
}
