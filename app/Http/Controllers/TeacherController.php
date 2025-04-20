<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Resources\TeacherResource;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function index($schoolId)
    {
        try{
        $teachers = Teacher::where('school_id', $schoolId)->get();
        return $this->successResponse(TeacherResource::collection($teachers));
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }


    public function searchTeacherByName(Request $request)
    {
        try{
            $name = $request->input('name');
        $teacher = Teacher::where('name', 'like', '%'.$name.'%')->first();
    
        if($teacher) {
            $teacherData=new TeacherResource($teacher);
            return $this->successResponse($teacherData);
           
        } 
        else {
           
                return  $this->successResponse(['message' => 'Teacher not found']);
             }
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
        $validator = Validator::make($request->all(),[
            'school_id' => 'required|integer|exists:schools,id',
            'schools_classes_division_id' => 'required|integer|exists:schools_classes_division,id',
            'phone' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'email' => 'required|email|unique:teachers',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); 
        }
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $teacher = Teacher::create($data);
        return $this->successResponse($teacher, 'created successfull.');
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
           // dd($teacher);
           $teacherData=new TeacherResource($teacher);
           return $this->successResponse($teacherData);
        }  catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        try {
        $teacherData = Teacher::find($teacher);

        if (!$teacherData) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

       
            $validator = Validator::make($request->all(),[
                //'school_id' => 'required|integer|exists:schools,id',
                'schools_classes_division_id' => 'required|integer|exists:schools_classes_division,id',
                'phone' => 'required|string',
               // 'gender' => 'required|in:Male,Female',
               // 'email' => 'required|email|unique:teachers',
                'password' => 'required|string|min:6',
                'name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422); 
            }
        $data['password'] = bcrypt($data['password']);
            $data = $request->all();
            $teacher->update($data);
            return $this->successResponse($teacher, 'update successfull.');
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
            
        }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
    }
}
