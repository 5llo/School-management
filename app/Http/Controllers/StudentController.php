<?php

namespace App\Http\Controllers;

use App\Models\SchoolsClass;
use App\Models\SchoolsClassesDivision;


use App\Http\Resources\ParentResource;

use App\Http\Resources\StudentInfoResource;
use App\Http\Resources\StudentMarksResource;
use App\Models\Student;
use App\Models\BusDriver;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;

use Illuminate\Support\Facades\Auth;

use App\Models\Attendance;

use App\Models\SchoolsClassesDivision;

use App\Models\StudentsSubject;

use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use Carbon\Carbon;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;


    public function getStudentInfo($id)
    {
        try {
            // Step 1: Find the student
            $student = Student::find($id);


            if (!$student) {
                return response()->json(['message' => 'Student not found'], 404);
            }


            $studentData = new StudentResource($student);
            $marksData=$student->subjects;
     //$marksData = StudentsSubject::where('student_id', $id)->with('subject')->get();
            // Step 2: Fetch the student's attendance record
            // $attendanceData = $student->attendances;
            $attendanceData = Attendance::where('student_id', $id)->first();

            // Step 3: Check if attendance data is available
            if (!$attendanceData || empty($attendanceData->attendance_array)) {
                return $this->successResponse([
                    'student_info' => $studentData,
                    'attendance_last_six_days' => []
                ]);
            }

            // Step 4: Parse attendance_array (handling JSON encoded data if necessary)
            $attendanceArray = is_string($attendanceData->attendance_array)
                ? json_decode($attendanceData->attendance_array, true)
                : $attendanceData->attendance_array;


            // Step 5: Set date range for the last 6 days
            $start = Carbon::now()->subDays(5)->startOfDay();  // Start from 5 days ago
            $end = Carbon::now()->endOfDay();  // End of today

            // Step 6: Initialize an empty array to hold filtered results
            $lastSixDaysAttendance = [];


            // Step 7: Loop through each date entry in the attendance array
            foreach ($attendanceArray as $attendanceItem) {
                // Each item in the array is an associative array with one date as key
                $date = key($attendanceItem);  // Get the date (e.g., "2025-04-29")
                $attendanceValues = reset($attendanceItem);  // Get the attendance values (e.g., [0, 0, 0, 0, 0, 0])

                try {
                    // Parse the date and check if it's within the last 6 days
                    $attendanceDate = Carbon::parse($date)->startOfDay();
                    if ($attendanceDate->between($start, $end)) {
                        // Add to the filtered list if the date is within the range
                        $lastSixDaysAttendance[] = [
                            'date' => $date,
                            'attendance_values' => [$date => $attendanceValues]  // Map date as the key
                        ];
                    }
                } catch (\Exception $e) {
                    // Skip invalid date formats
                    continue;
                }
            }

            // Step 8: Return the result in the expected format
            return $this->successResponse([
                'student_info' => $studentData,
                'attendance_last_six_days' => $lastSixDaysAttendance,
                'marks' => $marksData
            ]);

        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }



public function getALLStudentInfo()
{
    try {
        $students = Auth::user()->division->students;
        $allStudentData = [];

        foreach ($students as $student) {

            $studentInfo = new StudentInfoResource($student);

            $marksData = StudentsSubject::where('student_id', $student->id)
                ->with('subject')
                ->get();

            $attendanceMarkValue = 0;

            $numberOfPresent = 0;
            $numberOfAbsent = 0;
            $numberOfLate = 0;
            $numberOfEarlyLeave = 0;

            $attendanceData = Attendance::where('student_id', $student->id)->first();


            if ($attendanceData && !empty($attendanceData->attendance_array)) {
                $attendanceArray = is_string($attendanceData->attendance_array)
                    ? json_decode($attendanceData->attendance_array, true)
                    : $attendanceData->attendance_array;


                $numerator = 0;
                $denominator = 0;

                $end = Carbon::now()->endOfDay();

                foreach ($attendanceArray as $item) {
                    $date = key($item);
                    $values = reset($item);

                    try {
                        $dateParsed = Carbon::parse($date)->startOfDay();
                        if ($dateParsed <= $end) {

                            foreach ($values as $value) {
                                if (in_array($value, [1, 3, 4])) {
                                    $numerator++;
                                }

                                if ($value != 0) {
                                    $denominator++;
                                }

                                // Count stats
                                if ($value === 1) {
                                    $numberOfPresent++;
                                } elseif ($value === 2) {
                                    $numberOfAbsent++;
                                } elseif ($value === 3) {
                                    $numberOfLate++;
                                } elseif ($value === 4) {
                                    $numberOfEarlyLeave++;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if ($denominator > 0) {
                    $percentage = ($numerator / $denominator);
                    $attendanceMarkValue = ceil($percentage * 10);
                }
            }

            // Attach attendance data to each subject mark
            $marksData->each(function ($mark) use ($attendanceMarkValue, $numberOfPresent, $numberOfAbsent, $numberOfLate, $numberOfEarlyLeave) {
                $mark->attendance_grade = $attendanceMarkValue;
                $mark->number_of_present = $numberOfPresent;
                $mark->number_of_absent = $numberOfAbsent;
                $mark->number_of_late = $numberOfLate;
                $mark->number_of_early_leave = $numberOfEarlyLeave;

            });

            $studentMarks = StudentMarksResource::collection($marksData);


            // Last 6 days data
            $lastSixDaysAttendance = [];

            if ($attendanceData && !empty($attendanceData->attendance_array)) {
                $attendanceArray = is_string($attendanceData->attendance_array)
                    ? json_decode($attendanceData->attendance_array, true)
                    : $attendanceData->attendance_array;


                $start = Carbon::now()->subDays(7)->startOfDay();
                $end = Carbon::now()->endOfDay();


                foreach ($attendanceArray as $attendanceItem) {
                    $date = key($attendanceItem);
                    $attendanceValues = reset($attendanceItem);

                    try {
                        $attendanceDate = Carbon::parse($date)->startOfDay();

                        if ($attendanceDate->between($start, $end)) {
                            $lastSixDaysAttendance[] = [
                                'date' => $date,
                                'attendance_values' => $attendanceValues
                            ];
                        }

                    } catch (\Exception $ex) {
                        continue;
                    }
                }

            }

            $allStudentData[] = [
                $studentInfo,
                $lastSixDaysAttendance,
                $studentMarks,

            ];
        }

        return $this->successResponse($allStudentData);

    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}



    public function index($divisionId)
    {
        try{
            $students = Student::whereHas('schoolClassDivision', function ($query) use ($divisionId) {
                $query->where('id', $divisionId);
            })->get();
     return $this->successResponse(StudentResource::collection($students));
    }
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
    }



    public function getStudentInfoForSchool($studentId, $schoolId)
    {
        try{
        $student = Student::with(['parent', 'schoolClassDivision', 'busDriver'])
        ->where('id', $studentId)
        ->whereHas('schoolClassDivision.class', function($query) use ($schoolId) {
            $query->where('school_id', $schoolId);
        })
        ->first();

    if (!$student) {
        return response()->json(['message' => 'Student not found for the specified school'], 404);
    }
    $students=new StudentResource($student);
      return $this->successResponse($students);
    }
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }

    }

    /**
     * Show the form for creating a new resource.
     */


     public function searchStudentByName(Request $request)
{

    try{
        $name = $request->input('name');
    $student = Student::where('name', 'like', '%'.$name.'%')->first();

    if($student) {
        $students=new StudentResource($student);
        return $this->successResponse($students);
    }
    else {
        return  $this->successResponse(['message' => 'student not found']);
    }
}
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }

}



     public function searchStudentsInBus(Request $request)
  {
    try{
    $busDriverName = $request->input('bus_driver_name');

    $students = Student::whereHas('busDriver', function($query) use ($busDriverName) {
        $query->where('name', 'like', '%'.$busDriverName.'%');
    })->get();
    $studentCount = $students->count();
    if($students->isNotEmpty()) {
        return $this->successResponse( $students,['count' => $studentCount]);}
    else {
        return $this->successResponse(['message' => 'No students found with that bus driver']);
    }
    }
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {
        try {
            $validator = Validator::make($request->all(), [
                'parent_id' => 'required|exists:parents,id',
                'bus_driver_id' => 'nullable|exists:bus_drivers,id',
                'class_id' => 'required|integer|exists:classes,id',
                'division_id' => 'required|integer|exists:schools_classes_division,division_id',
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            $data['school_id'] = Auth::user()->id;

            $schoolClass = SchoolsClass::where('id', $data['class_id'])
                ->where('school_id', $data['school_id'])
                ->first();

            $schoolClassDivision = SchoolsClassesDivision::where('school_class_id', $schoolClass->id)
                ->where('division_id', $data['division_id'])
                ->first();

            $data['school_class_division_id'] = $schoolClassDivision->id;

            $student = Student::create($data);
            $studentResource = new StudentResource($student);

            return $this->successResponse($studentResource);
        }

        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $user = Auth::user();
            $studentId = $request->input('student_id');

            $student = Student::find($studentId);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.'
                ], 404);
            }

{
    try {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|exists:parents,id',
            'bus_driver_id' => 'nullable|exists:bus_drivers,id',
            'class_id' => 'required|integer|exists:classes,id',
            'division_id' => 'required|integer|exists:schools_classes_division,division_id',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['school_id'] = Auth::user()->id;

        $schoolClass = SchoolsClass::where('id', $data['class_id'])
            ->where('school_id', $data['school_id'])
            ->first();

        $schoolClassDivision = SchoolsClassesDivision::where('school_class_id', $schoolClass->id)
            ->where('division_id', $data['division_id'])
            ->first();

        $data['school_class_division_id'] = $schoolClassDivision->id;

        $student = Student::create($data);
        $studentResource = new StudentResource($student);

        return $this->successResponse($studentResource);
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }



}


























     public function show(Request $request)
    {

        try {
             if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = Auth::user();
        $studentId = $request->input('student_id');

        $student = Student::find($studentId);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        $parent = $student->parent;

        $parentResource = new ParentResource($parent);

     return $this->successResponse($parentResource);
    }

    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }

    }


            $parent = $student->parent;

            $parentResource = new ParentResource($parent);

            return $this->successResponse($parentResource);
        }

        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        try {
            $studentData = Student::find($student);

            if (!$studentData) {
                return response()->json(['message' => 'student not found'], 404);
            }


                $validator = Validator::make($request->all(),[
                 'parent_id' => 'required|exists:parents,id',
                 'bus_driver_id' => 'required|exists:bus_drivers,id',
                 'school_class_division_id' => 'required|exists:schools_classes_division,id',
                 'name' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation Error',
                        'errors' => $validator->errors()
                    ], 422);
                }
                $data = $request->all();
                $student->update($data);
                return $this->successResponse($student, 'update successfull.');
            } catch (\Exception $ex) {
                return $this->errorResponse($ex->getMessage(), 500);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
