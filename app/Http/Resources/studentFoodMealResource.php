<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class studentFoodMealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'student_name' => $this->student->name,
            'food_meal_name' => $this->foodMeal->name,
            'food_meal_price' => $this->foodMeal->price,
            'status' => $this->status,
            'process_number' => $this->process_number,
            'notes' => $this->notes,
        ];
    }
}
