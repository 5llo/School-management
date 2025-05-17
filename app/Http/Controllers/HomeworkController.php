<?php

namespace App\Http\Controllers;

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
         $homeworks = Homework::where('teacher_id', $teacherId)->get();
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
        try{
        $validator = Validator::make($request->all(), [
           //'teacher_id' => 'required|exists:teachers,id',
            'description' => 'required',
            //'file' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $data = $request->all();
        $data['teacher_id'] = Auth::user()->id;
        $fileNmae = time().'.'.$request->file->getClientOriginalExtension();
        $file_path = base_path('./');
        $filee =  $request->file->move('public/homework', $fileNmae);
         $myfile='homework/'. $fileNmae;
         $data['file']=$myfile;
        $homework = Homework::create($data);
        return $this->successResponse($homework);
    } 
    catch (\Exception $ex) {
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
