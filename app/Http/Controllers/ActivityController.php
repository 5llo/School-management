<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Http\Resources\ActivityResource;
use Illuminate\Support\Facades\Validator;
class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function index()
    {
        try{
        $activity = Activity::all();
        return $this->successResponse(ActivityResource::collection($activity));
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
    }


    public function searchActivityByName(Request $request)
{
    try{
    $name = $request->input('name');
    
    $activity = Activity::where('name', 'like', '%'.$name.'%')->first();

    if($activity) {
        $activityName= new ActivityResource($activity);
        return $this->successResponse($activityName);
     }
    //  else {
    //     return response()->json(['message' => 'Activity not found']);
    // }    
    } catch (\Exception $ex) {
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
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date',
            'time' => 'required',
            'start' => 'required',
            'phone' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422); 
            }  
             $data = $request->all();
             $activity = Activity::create($data);
            return $this->successResponse($activity, 'created successfull.');
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity,$id)
    {
        try{
        $activity = Activity::findOrFail($id);
        $activityData= new ActivityResource($activity);
        return $this->successResponse($activityData);

    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity,$id)
    {
        try {
            $activityData = Activity::find($id);
           
            if (!$activityData) {
                return $this->successResponse(['message' => 'Activity not found']);
            }
           // dd($activityData);
            $activityData->delete();
            return $this->successResponse(['message' => 'Activity deleted']);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }


    public function getActiveActivities()
{
    try{
        $activeActivities = Activity::whereDate('start', '>=', now()->toDateString())->get();
    
    $activities = [];

    foreach ($activeActivities as $activity) {
        $activity = [
            'name' => $activity->name,
            'price' => $activity->price,
            'date' => $activity->date,
            'time' => $activity->time,
            'start' => $activity->start,
            'phone' => $activity->phone,
            'status' => 'active',
        ];

        $activities[] = $activity;
    }

    return $this->successResponse($activities);
} catch (\Exception $ex) {
    return $this->errorResponse($ex->getMessage(), 500);
}

}
}
