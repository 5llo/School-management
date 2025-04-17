<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentsSubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalGrades = $this->oral_grade + $this->homework_grade + $this->exam_grade;
        $totalSubjects = 5;
        $averageGrade = ($totalGrades / $totalSubjects) * 100;

        $totalAttendance = count($this->attendance_array);
        $totalOral = 100; 
        $totalHomework = 100; 
        $totalExam = 100; 

        $attendanceGrade = array_sum($this->attendance_array) / $totalAttendance;
        $oralGrade = $this->oral_grade / $totalOral;
        $homeworkGrade = $this->homework_grade / $totalHomework;
        $examGrade = $this->exam_grade / $totalExam;

        return [
            'student_name' => $this->student->name,
            'attendance_grade' => $attendanceGrade,
            'oral_grade' => $oralGrade,
            'homework_grade' => $homeworkGrade,
            'exam_grade' => $examGrade,
            'average_grade' => $averageGrade,
        ];
    }
}
