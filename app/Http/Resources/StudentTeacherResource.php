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

    public function toArray(Request $request)
    {
        $attendanceStatus = (int) ($this->extraData['attendanceStatus'] ?? 0);

        switch ($attendanceStatus) {
            case 1:
                $attendanceStatus = 'present';
                break;
            case 2:
                $attendanceStatus = 'absent';
                break;
            case 3:
                $attendanceStatus = 'excused';
                break;
            case 4:
                $attendanceStatus = 'late';
                break;
            case 0:
            default:
                $attendanceStatus = 'Not Specified';
                break;
        }

        return [
            'studentName' => $this->name,
            'studentId' => $this->id,
            'oralGrade' => $this->subjects->where("name", $this->extraData['info'])->first()->pivot->oral_grade,
            'attendanceStatus' => $attendanceStatus,
        ];
    }

}
