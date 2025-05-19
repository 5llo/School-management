<?php

namespace App\Http\Controllers;

use App\Models\BusDriver;
use App\Http\Resources\BusResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

class BusDriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

     public function getBusDriversBySchool()
     {
        try{
            $schoolId=Auth::user()->id;
         $busDrivers = BusDriver::where('school_id', $schoolId)->get();
         return $this->successResponse(BusResource::collection($busDrivers));
        }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

     }


     public function getBusDriverinfo()
     {
    //     try{
    //      $busDriver = BusDriver::where('id', $driverId)->first();

    //      if (!$busDriver) {
    //          return response()->json(['message' => 'Bus driver not found for the specified school'], 404);
    //      }

    //      $bus= new BusResource($busDriver);
    //      return $this->successResponse($bus);
    //  }
    try {

        $busDriver = Auth::user();
         if (!$busDriver) {
             return $this->successResponse(['message' => 'Bus driver not found'], 404);
         }
         return $this->successResponse(new BusResource($busDriver));

        }
     catch (\Exception $ex) {
         return $this->errorResponse($ex->getMessage(), 500);
     }
     }


     public function getDriverStudents(Request $request)
    {
       //return $request->user();
       try {

       $busDriver = Auth::user();
        if (!$busDriver) {
            return $this->successResponse(['message' => 'Bus driver not found'], 404);
        }

        $driverStudents = $busDriver->students->load('parent');

        $response = [];
        foreach ($driverStudents as $student) {
            $parent = $student->parent;
            if ($parent) {
                $response[] = [
                    'student_name' => $student->name,
                    'parent_phone' => $parent->phone,
                    'latitude' => $parent->latitude,
                    'longitude' => $parent->longitude,
                ];
            }
        }

            return $this->successResponse($response);
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }


    public function index()
    {

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
            'name' => 'required',
            'email' => 'required|email|unique:bus_drivers',
            'password' => 'required',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'phone'=>'required|string',
           // 'bus_number' => 'required|integer|unique:bus_drivers,bus_number,NULL,id,school_id,' . $request->school_id,
            'bus_capacity' => 'required|integer|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
              $data = $request->all();
             $data['school_id'] = Auth::user()->id;
             $data['password'] = $data['password'];
       $BusDriver = BusDriver::create($data);

       return $this->successResponse($BusDriver);
   }
   catch (\Exception $ex) {
       return $this->errorResponse($ex->getMessage(), 500);
   }
    }

    /**
     * Display the specified resource.
     */
    public function show(BusDriver $busDriver)
    {
        return new BusResource($busDriver->load(['school', 'driver', 'students']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusDriver $busDriver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusDriver $busDriver)
    {
        try {
            $BusDriver = BusDriver::find($busDriver);

            if (!$BusDriver) {
                return response()->json(['message' => 'BusDriver not found'], 404);
            }


            $validator = Validator::make($request->all(), [
                'school_id' => 'required|exists:schools,id',
                'name' => 'required',
                'email' => 'required|email|unique:bus_drivers',
                'password' => 'required',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
                'bus_number' => 'required|integer|unique:bus_drivers,bus_number,NULL,id,school_id,' . $request->school_id,
                'bus_capacity' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

                $data = $request->all();
                $BusDriver->update($data);
                return $this->successResponse($BusDriver, 'update successfull.');
            } catch (\Exception $ex) {
                return $this->errorResponse($ex->getMessage(), 500);
            }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusDriver $busDriver)
    {
        //
    }
}
