<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Http\Resources\SchoolResource;
use App\Http\Resources\StudentResource;
use App\Models\SchoolsClass;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function getStudentsInfoForSchool()
{
    try {
        $school = auth()->user();

        if ($school) {
            $teachers = $school->teachers;
           // return $teachers;
            $studentsData = [];

            foreach ($teachers as $teacher) {
                if ($teacher->division) {
                    $students = $teacher->division->students;

                    foreach ($students as $student) {
                        $studentResource = new StudentResource($student);
                        $studentsData[] = $studentResource;
                    }
                }
            }

            return $this->successResponse($studentsData);
        } else {
            return $this->errorResponse('User not authenticated as a school', 401);
        }} catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    public function index()
    {
        try{
       $schools = School::all();
        return $this->successResponse(SchoolResource::collection($schools));
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    
 public function getSchoolClassesDivisions()
    {
        try {
        
             $schoolId = Auth::user()->id;
     $schoolClasses = SchoolsClass::where('school_id', $schoolId)->get();

        $allSchoolData = [];

        foreach ($schoolClasses as $schoolClass) {
            $classId = $schoolClass->id;
            $className = $schoolClass->classsModel->name;
            $divisionsData = [];
            foreach ($schoolClass->divisions as $division) {
                $divisionId = $division->division->id;
                $divisionName = $division->division->name;

                $divisionData = [
                    'division_id' => $divisionId,
                    'division_name' => $divisionName
                ];

                $divisionsData[] = $divisionData;
            }

            $schoolClassData = [
                'class_id' => $classId,
                'class_name' => $className,
                'divisions' => $divisionsData
            ];

            $allSchoolData[] = $schoolClassData;
        }
  return $this->successResponse($allSchoolData);
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    

    /**
     * Display the specified resource.
     */
    public function show(School $school,$id)
    {
        try{
        $school = School::find($id);

        if (!$school) {
            return response()->json(['message' => 'School not found'], 404);
        }

       $data= new SchoolResource($school);
        return $this->successResponse($data);
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    


    public function searchSchoolByName(Request $request)
    {
        try{
        $name = $request->input('name');

        $school = School::where('name', 'like', '%' . $name . '%')->get();

        if ($school->isEmpty()) {
            return response()->json(['message' => 'School not found'], 404);
        }
        return $this->successResponse($school);
    }

        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    
   public function countDivisionsPerClassInSchool($schoolId)
{
    try{
    $school = School::find($schoolId);

    if (!$school) {
        return "School not found";
    }

    $classes = $school->classes()->with('divisions')->get();
    dd($classes);
    $divisionsCount = [];

    foreach ($classes as $class) {
        $divisionsCount[$school->classe->classsModel->name] = $class->schoolsClassesDivisions->count();
    }
    return $this->successResponse($divisionsCount);
}
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        //
    }
}
