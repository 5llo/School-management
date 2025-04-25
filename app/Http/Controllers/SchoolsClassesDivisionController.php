<?php

namespace App\Http\Controllers;

use App\Models\SchoolsClassesDivision;
use App\Models\Division;
use App\Models\SchoolsClass;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Student;
use App\Http\Resources\SchoolsClassesDivisiontResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;


class SchoolsClassesDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function getWeek_Schedule(Request $request)
{
    try {
       
        $teacher = $request->user();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }
        $division = $teacher->division;

        if (!$division) {
            return response()->json(['message' => 'Teacher is not assigned to any division'], 403);
        }

        $weekSchedule=new SchoolsClassesDivisiontResource($division);
     
        return $this->successResponse(['week_schedule' => $weekSchedule]);
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}


    public function  getSchoolDivisionsDetails($schoolClassId,$divisionId)
    {
        try{
            $divisions = SchoolsClassesDivision::with(['class', 'division', 'teachers'])
            ->where('school_class_id', $schoolClassId)
            ->where('division_id', $divisionId)
            ->get();
        return $this->successResponse( SchoolsClassesDivisiontResource::collection($divisions));
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */

     public function searchStudentByNameInDivision(Request $request,$divisionId)
{
    try{
    $name = $request->input('name');
    $students = Student::where('name', 'like', '%'.$name.'%')
    ->where('school_class_division_id', $divisionId)
    ->get();

    if($students->isNotEmpty()) {
        return $this->successResponse($students);

    } 
    else {
        return $this->successResponse(['message' => 'No students found in the division with that name']);
    }
     }
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}


     public function index()
    {
       
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
        try{
        $validator = Validator::make($request->all(), [
            'school_class_id' => 'required|exists:schools_classes,id',
            'division_id' => 'required|exists:divisions,id',
            'exam_schedule' => 'nullable',
            'week_schedule' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); 
        }  
         $data = $request->all();
        $schoolsClassesDivision = SchoolsClassesDivision::create($data);
        return $this->successResponse($schoolsClassesDivision);
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */

    public function show(SchoolsClassesDivision $schoolsClassesDivision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolsClassesDivision $schoolsClassesDivision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolsClassesDivision $schoolsClassesDivision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolsClassesDivision $schoolsClassesDivision)
    {
        //
    }
}
