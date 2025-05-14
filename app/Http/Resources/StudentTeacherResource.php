<?php

namespace App\Http\Resources;
use App\Models\ParentModel;
use App\Models\BusDriver;
use App\Models\SchoolsClassesDivision;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentTeacherResource extends JsonResource
{

    protected $extraData;

    public function __construct($resource, $extraData = [])
    {
        parent::__construct($resource);
        $this->extraData = $extraData;
    }

    public function toArray(Request $request )
    {
        return [
          //  'req'=>  $this->selectedmaterial
           'name' => $this->name,
           'student_id' => $this->id,
           'oralGrade' => $this->subjects->where("name",$this->extraData['info'])->first()->pivot->oral_grade,


        ];
    }
}
