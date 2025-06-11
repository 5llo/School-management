<?php

namespace App\Http\Controllers;

use App\Http\Controllers\notification\firebaseController;
use Illuminate\Support\Facades\Storage;
use App\Models\Homework;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\SchoolsClassesDivision;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

class HomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;


     public function index()
     {
        try{
            $teacherId= Auth::user()->id;
        $homeworks = Homework::where('teacher_id', $teacherId)
                     ->orderBy('created_at', 'desc')
                     ->get();

         return $this->successResponse($homeworks);
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
            'description' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,zip,rar,txt,jpg,png|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // تخزين الملف
        $file = $request->file('file');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('public/homework', $fileName);

        // البيانات
        $data = $request->only('description');
        $data['file'] = 'storage/homework/' . $fileName;
        $data['teacher_id'] = Auth::user()->id;

        // إنشاء الواجب
        $homework = Homework::create($data);

        // جلب المعلم مع القسم المرتبط
        $teacher = Auth::user();

        // جلب القسم المرتبط بالمعلم
        $division = $teacher->division;

        if (!$division) {
            return response()->json(['message' => 'Division not found for teacher'], 404);
        }

        // جلب كل الطلاب في القسم
        $students = $division->students()->with('parent')->get();

        // ارسال اشعار لكل ولي أمر لديه FCM token
        foreach ($students as $student) {
            $parent = $student->parent;

            if ($parent && !empty($parent->fcmtoken)) {
                \App\Http\Controllers\notification\firebaseController::sendToUserFCM(
                    $parent->fcmtoken,
                    "واجب جديد من المعلم " . $teacher->name,
                    "تم إضافة واجب جديد: " . $request->description
                );
            }
        }

        return $this->successResponse($homework);

    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}


    /**
     * Display the specified resource.
     */


     public function getHomeWorkWForDivision($divisionId)
{
    try {

        $division = SchoolsClassesDivision::findOrFail($divisionId);

        $jobs = $division->teachers->flatMap(function ($teacher) {
            return $teacher->homeworks->map(function ($job) use ($teacher) {
                return [
                    'teacher' => $teacher->name,
                    'description' => $job->description,
                    'file' => $job->file,
                ];
            });
        })->all();

      return $this->successResponse($jobs);

    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

}
    public function show(Homework $homework)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Homework $homework)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Homework $homework)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Homework $homework)
    {
        //
    }
}
