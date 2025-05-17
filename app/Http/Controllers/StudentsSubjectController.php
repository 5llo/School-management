<?php

namespace App\Http\Controllers;

use App\Models\StudentsSubject;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StudentsSubjectResource;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

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
    } catch (\Exception $ex) {
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
    public function updateStudentGrades(Request $request)
{

    try {



        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'materials' => 'required|array',
            'materials.*.subject_id' => 'required|exists:subjects,id',
            'materials.*.oralgrade' => 'required|numeric|between:0,20',
            'materials.*.homeworkgrade' => 'required|numeric|between:0,20',
            'materials.*.examgrade' => 'required|numeric|between:0,50',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $data = $request->only(['student_id', 'materials']);

        foreach ($data['materials'] as $material) {
            $studentSubject = StudentsSubject::where('student_id', $data['student_id'])
                ->where('subject_id', $material['subject_id'])
                ->first();

            if (!$studentSubject) {
                return response()->json(['message' => 'Student subject not found'], 404);
            }

            $studentSubject->update([
                'oral_grade' => $material['oralgrade'],
                'homework_grade' => $material['homeworkgrade'],
                'exam_grade' => $material['examgrade'],
            ]);
        }

        return $this->successResponse([],'successfull.');
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }

    }


    public function updateGradesForDivision(Request $request)
{
   try {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            '*.student_id' => 'required|exists:students,id',
            '*.material' => 'required|array',
            '*.material.*.subject_id' => 'required|exists:subjects,id',
            '*.material.*.oral_grade' => 'required|numeric|between:0,20',
            '*.material.*.homework_grade' => 'required|numeric|between:0,20',
            '*.material.*.exam_grade' => 'required|numeric|between:0,50',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $data = $request->collect(); 

        // جيب جميع الطلاب من شعبة المعلم
        $allowedStudentIds = $user->division->students->pluck('id')->toArray();

        foreach ($data as $studentData) {
            $studentId = $studentData['student_id'];
            $materials = $studentData['material'];

            // التأكد أن الطالب موجود في شعبة المعلم
            if (!in_array($studentId, $allowedStudentIds)) {
                return response()->json([
                    'message' => "Unauthorized to update grades for student ID {$studentId}"
                ], 403);
            }

            foreach ($materials as $material) {
                $studentSubject = StudentsSubject::where('student_id', $studentId)
                    ->where('subject_id', $material['subject_id'])
                    ->first();

                if (!$studentSubject) {
                    return response()->json([
                        'message' => "Student subject not found for student {$studentId} and subject {$material['subject_id']}"
                    ], 404);
                }

                // تحديث العلامات
                $studentSubject->update([
                    'oral_grade' => $material['oral_grade'],
                    'homework_grade' => $material['homework_grade'],
                    'exam_grade' => $material['exam_grade'],
                ]);
            }
        }
      
        return $this->successResponse([],'successfull.');
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
