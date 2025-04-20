<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
       return [
        'name' => $this->name,
        'phone' => $this->phone,     
        'gender'=>$this->gender,
        'email'=>$this->email,
        'password'=>$this->password,
         'school_name' => $this->school->name,
         'class_name' => $this->division->class->classsModel->name,
         'school_class_division' =>  $this->division->division->name,
        
    ];
    }
}
