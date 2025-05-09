<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\BusDriver;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
    
            // Step 2: Fetch the student's attendance record
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
                'attendance_last_six_days' => $lastSixDaysAttendance
            ]);
    
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    



    public function getALLStudentInfo()
{
    try {
        // Step 1: Find all students
        $students = Auth::user()->division->students()->get();

        $allStudentData = [];
        
        foreach ($students as $student) {
            $studentData = new StudentResource($student);

            // Fetch the student's attendance record
            $attendanceData = Attendance::where('student_id', $student->id)->first();

            if ($attendanceData && !empty($attendanceData->attendance_array)) {
                $attendanceArray = is_string($attendanceData->attendance_array)
                    ? json_decode($attendanceData->attendance_array, true)
                    : $attendanceData->attendance_array;

                $start = Carbon::now()->subDays(5)->startOfDay();
                $end = Carbon::now()->endOfDay();
                $lastSixDaysAttendance = [];

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

                $allStudentData[] = [
                    'student_info' => $studentData,
                    'attendance_last_six_days' => $lastSixDaysAttendance
                ];
            }
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
    public function store(Request $request,$schoolId)
    {
        try{
        $validator = Validator::make($request->all(),[
            'parent_id' => 'required|exists:parents,id',
           'bus_driver_id' => 'nullable|exists:bus_drivers,id',
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
        $schoolId = $request->schoolId;
        $data = $request->all();
        $student = Student::create($data);
        $studentdata = new StudentResource($student);
        return $this->successResponse($studentdata, 'created successfull.');
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        
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
