<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParentResource;
use App\Models\SchoolsClass;
use App\Models\SchoolsClassesDivision;
use App\Models\Student;
use App\Models\BusDriver;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

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
