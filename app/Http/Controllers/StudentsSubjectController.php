<?php

namespace App\Http\Controllers;

use App\Models\StudentsSubject;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StudentsSubjectResource;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
class StudentsSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;
    public function index()
    {
        try {
            $studentsSubjects = StudentsSubject::with('student', 'subject', 'session')->get();
            return $this->successResponse($studentsSubjects);
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
    public function store(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'session_id' => 'required|exists:sessions,id',
            'attendance_array' => 'array',
            'oral_grade' => 'numeric',
            'homework_grade' => 'numeric',
            'exam_grade' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); 
        }  
         $data = $request->all();
        $studentsSubject = StudentsSubject::create($data);
        return $this->successResponse($studentsSubject, 'created successfull.');
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show($studentId)
    {
        try {
            $studentSubjects = StudentsSubject::with('student', 'subject', 'session')
            ->where('student_id', $studentId)
            ->get();

if ($studentSubjects->isEmpty()) {
return response()->json(['message' => 'Student Subjects not found for the specified student ID'], 404);
}
        return $this->successResponse($studentSubjects, 'successfull.');
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
    }


    public function showStudentsSubjectAvareg($id)
{
    try {
    $studentSubject = StudentsSubject::find($id);

    if ($studentSubject) {
        $students=new StudentsSubjectResource($studentSubject);
        return $this->successResponse($students);
    } else {
        return response()->json(['message' => 'Student subject not found'], 404);
    }
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
        }
}


public function showFinallyResult($studentId)
{
    try {
        $studentSubjects = StudentsSubject::with('student', 'subject', 'session')
            ->where('student_id', $studentId)
            ->get();

        if ($studentSubjects->isEmpty()) {
            return response()->json(['message' => 'Student Subjects not found for the specified student ID'], 404);
        }

        // Calculate total marks for each subject
        $studentSubjects->map(function ($studentSubject) {
            $totalMarks = $studentSubject->oral_grade + $studentSubject->homework_grade + $studentSubject->exam_grade;
            $studentSubject->total_marks = $totalMarks;
            return $studentSubject;
        });

        // Calculate final total marks for all subjects
        $finalTotalMarks = $studentSubjects->sum('total_marks');

        return response()->json(['data' => $studentSubjects, 'final_total_marks' => $finalTotalMarks], 200);
    } catch (Exception $ex) {
        return response()->json(['message' => $ex->getMessage()], 500);
    }
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentsSubject $studentsSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentsSubject $studentsSubject)
    {
        try{
        $studentSubject = StudentsSubject::find($studentsSubject);

        if (!$studentSubject) {
            return response()->json(['message' => 'Student subject not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'attendance_array' => 'required|array',
            'oral_grade' => 'required|numeric',
            'homework_grade' => 'required|numeric',
            'exam_grade' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); 
        }
        $data = $request->all();

        $studentsSubject->update($data);
        
        return $this->successResponse($studentsSubject, 'updated successfull.');
        } catch (\Exception $ex) {  
            return $this->errorResponse($ex->getMessage(), 500);    
            }
            
    }

    
    public function ShowFeaturedStudents($id)
    {
        try {
            $students = StudentsSubject::whereHas('student', function ($query) use ($id) {
                $query->whereHas('schoolClassDivision', function ($q) use ($id) {
                    $q->where('school_class_division_id', $id); 
                });
            })->orderBy('exam_grade', 'desc')
              ->with('student', 'subject', 'session')
              ->get();
        return $this->successResponse($students);
    } catch (\Exception $ex) {  
        return $this->errorResponse($ex->getMessage(), 500);    
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentsSubject $studentsSubject)
    {
        //
    }
}
