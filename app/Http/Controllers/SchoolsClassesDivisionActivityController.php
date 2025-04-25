<?php

namespace App\Http\Controllers;

use App\Models\SchoolsClassesDivisionActivity;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
class SchoolsClassesDivisionActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */use GeneralTrait;
    public function index()
    {
        try{
            $schoolsClassesDivisionActivities = SchoolsClassesDivisionActivity::with('activity', 'schoolClassesDivision')->get();
            return $this->successResponse($schoolsClassesDivisionActivities);
        } 
        catch (\Exception $ex){
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
                'school_classes_division_id' => 'required|exists:schools_classes_division,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422); 
            }  
             $data = $request->all();
            $SchoolsClassesDivisionActivity = SchoolsClassesDivisionActivity::create($data);
            return $this->successResponse($SchoolsClassesDivisionActivity, 'created successfull.');
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
        $schoolsClassesDivisionActivities = SchoolsClassesDivisionActivity::where('school_classes_division_id', $id)
            ->with('activity')
            ->get();

        if ($schoolsClassesDivisionActivities->isEmpty()) {
            return response()->json(['message' => 'No activities found for this school class division'], 404);
        }

        return $this->successResponse($schoolsClassesDivisionActivities);
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
   }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolsClassesDivisionActivity $schoolsClassesDivisionActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolsClassesDivisionActivity $schoolsClassesDivisionActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolsClassesDivisionActivity $schoolsClassesDivisionActivity)
    {
        //
    }
}
