<?php

namespace App\Http\Resources;
use App\Models\ParentModel; 
use App\Models\BusDriver;
use App\Models\SchoolsClassesDivision;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'parent_name' => $this->parent->name,     
            'Driver_bus'=>$this->busDriver->name,
            //'teacher_name' => $this->schoolClassDivision->teachers[0]->name,
             'school_name' => $this->schoolClassDivision->class->school->name,
             'class_name' => $this->schoolClassDivision->class->classsModel->name,
             'school_class_division' =>  $this->schoolClassDivision->division->name,
            
        ];
    }
}
