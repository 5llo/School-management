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
use Illuminate\Support\Facades\Validator;
class AttendanceController extends Controller
{
    use GeneralTrait;


    public function updateAttendances(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'material' => 'required|string|exists:subjects,name',
            'date' => 'required|date',
            'index' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $dateKey = date('Y-m-d', strtotime($request->date)); // ensure same format

        $students = Auth::user()->division->students()->with('subjects', 'attendance')->get();

        $data = $students->map(function ($student) use ($request, $dateKey) {
            $attendanceStatus = null;

            $attendanceArray = $student->attendance ? $student->attendance->attendance_array : [];

            // نبحث عن السجل المطابق للتاريخ
            foreach ($attendanceArray as $record) {
                if (isset($record[$dateKey])) {
                    $dailyStatus = $record[$dateKey];

                    if (isset($dailyStatus[$request->index])) {
                        $attendanceStatus = (int) $dailyStatus[$request->index];

                    }

                    break;
                }
            }

            return (new StudentTeacherResource($student, [
                'info' => $request->material,
                'attendanceStatus' => $attendanceStatus,
            ]))->toArray(request());
        });


        return $this->successResponse($data);
    }





    public function setattendancesandgrade(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required',
            'materailname' => 'required|string',
            'index' => 'required|integer|min:0',
            'data' => 'required|array',
            'data.*.studentid' => 'required|integer|exists:students,id',
            'data.*.oralgrade' => 'required|integer|min:0',
            'data.*.present' => 'required|integer|in:0,1,2,3,4',
        ]);

        $targetDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        $index = (int) $validated['index'];

        foreach ($validated['data'] as $item) {
            $studentId = $item['studentid'];
            $oralGrade = $item['oralgrade'];
            $present = $item['present'];

            $attendance = Attendance::where('student_id', $studentId)->first();

            if ($attendance) {
                $attendanceArray = $attendance->attendance_array;

                $updated = false;

                foreach ($attendanceArray as &$day) {
                    if (array_key_exists($targetDate, $day)) {
                        if (isset($day[$targetDate][$index])) {
                            $day[$targetDate][$index] = $present;
                            $updated = true;
                        }
                        break;
                    }
                }

                // If no record for this date, we can create it
                if (!$updated) {
                    $attendanceArray[] = [
                        $targetDate => array_fill(0, $index + 1, 0)
                    ];
                    $attendanceArray[count($attendanceArray) - 1][$targetDate][$index] = $present;
                }

                $attendance->attendance_array = $attendanceArray;
                $attendance->save();
            }

            // Update oral grade
            $student = Student::find($studentId);
            $subject = Subject::where('name', $validated['materailname'])->first();

            if ($subject) {
                $student->subjects()->updateExistingPivot($subject->id, [
                    'oral_grade' => $oralGrade,
                ]);
            }
        }

        return $this->successResponse([], 'Attendance and oral grades updated successfully.');
    }

}
