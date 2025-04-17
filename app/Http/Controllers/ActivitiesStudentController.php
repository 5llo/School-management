<?php

namespace App\Http\Controllers;

use App\Models\ActivitiesStudent;
use App\Http\Resources\ActivityStudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;

class ActivitiesStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;
    public function index()
    {
        try{
        $activitiesStudents = ActivitiesStudent::with('activity', 'student')->get();
        return $this->successResponse($activitiesStudents);
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
             'activity_id' => 'required|exists:activities,id',
            'student_id' => 'required|exists:students,id',
            'process_number' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422); 
            }  
             $data = $request->all();
            $ActivitiesStudent = ActivitiesStudent::create($data);
            return $this->successResponse($ActivitiesStudent, 'created successfull.');
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $activitiesStudent = ActivitiesStudent::with('activity')->where('student_id', $id)->get();

        if (!$activitiesStudent) {
            return response()->json(['message' => 'Activities Student not found'], 404);
        }
        return $this->successResponse($activitiesStudent);
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */

     public function showStudentsForActivity($activityId)
    {
        try {
            $studentsForActivity = ActivitiesStudent::with('student')->where('activity_id', $activityId)->get();

            if ($studentsForActivity->isEmpty()) {
                return response()->json(['message' => 'No students found for this activity'], 404);
            }

            return $this->successResponse($studentsForActivity);

        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }


    public function getSuspendedRecords()
    {
        try {
            $suspendedRecords = ActivitiesStudent::where('status', 'suspended')->get();
            return $this->successResponse(ActivityStudentResource::collection($suspendedRecords));
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function getActiveRecords()
    {
        try {
            $activeRecords = ActivitiesStudent::where('status', 'active')->get();
            return $this->successResponse(ActivityStudentResource::collection($activeRecords));
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function edit(ActivitiesStudent $activitiesStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivitiesStudent $activitiesStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivitiesStudent $activitiesStudent)
    {
        //
    }
}
