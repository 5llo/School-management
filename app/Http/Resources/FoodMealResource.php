<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodMealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           'school_name' => $this->school->name,
           'day_name' => $this->name,
            'Food_name' => $this->Food,
            'id'=>$this->id,
            'contents' => $this->contents,
            'entrees' => $this->entrees,
            'price' => $this->price,
            'date'=>$this->day,
        ];
    }
}
