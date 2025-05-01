<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentTeacherResource;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    use GeneralTrait;

    public function updateAttendances(Request $request)
    {
        $request->validate(['material' => 'required|string|exists:subjects,name',]);

        $students = Auth::user()->division->students()->with('subjects')->get();

        $data = $students->map(function ($student) use ($request) {
            return (new StudentTeacherResource($student, ['info' => $request->material]))->toArray(request());
        });

        return $this->successResponse($data);
    }



    public function setattendancesandgrade(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required',
            'materailname' => 'required|string',
            'data' => 'required|array',
            'data.*.studentid' => 'required|integer|exists:students,id',
            'data.*.oralgrade' => 'required|numeric|min:0',
            'data.*.present' => 'required|integer|in:0,1',
        ]);
        $targetDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        foreach ($validated['data'] as $item) {
            $studentId = $item['studentid'];
            $oralGrade = $item['oralgrade'];
            $present = $item['present'];

            $attendance = Attendance::where('student_id', $studentId)->first();

            if ($attendance) {
                $attendanceArray = $attendance->attendance_array;

                foreach ($attendanceArray as &$day) {
                    if (array_key_exists($targetDate, $day)) {
                        $arrayForDate = &$day[$targetDate];
                        foreach ($arrayForDate as $i => $val) {
                            if ($val == 0) {
                                $arrayForDate[$i] = $present;
                                break;
                            }
                        }
                        break;
                    }
                }

                $attendance->attendance_array = $attendanceArray;
                $attendance->save();
            }

            $student = Student::find($studentId);
            $subject = Subject::where('name', $validated['materailname'])->first();

            if ($subject) {
                $student->subjects()->updateExistingPivot($subject->id, [
                    'oral_grade' => $oralGrade,
                ]);
            }
        }

        return response()->json(['message' => 'Attendance and oral grades updated successfully.']);
    }
}
