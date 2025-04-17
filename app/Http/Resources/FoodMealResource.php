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
            'Food_name' => $this->name,
            'contents' => $this->contents,
            'entrees' => $this->entrees,
            'price' => $this->price,
            
        ];
    }
}
