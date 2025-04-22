<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Http\Resources\SchoolResource;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;

use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function index()
    {
        try{
        $schools = School::all();
        return $this->successResponse(SchoolResource::collection($schools));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bus_price' => 'nullable|numeric',
            'email' => 'required|email|unique:schools,email',
            'password' => 'required|min:6',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',

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
    
        
        $School = School::create($data);
    
        return $this->successResponse($School);
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school,$id)
    {
        try{
        $school = School::find($id);

        if (!$school) {
            return response()->json(['message' => 'School not found'], 404);
        }

       $data= new SchoolResource($school);
        return $this->successResponse($data);
    }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    


    public function searchSchoolByName(Request $request)
    {
        try{
        $name = $request->input('name');

        $school = School::where('name', 'like', '%' . $name . '%')->get();

        if ($school->isEmpty()) {
            return response()->json(['message' => 'School not found'], 404);
        }
        return $this->successResponse($school);
    }

        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    
   public function countDivisionsPerClassInSchool($schoolId)
{
    try{
    $school = School::find($schoolId);

    if (!$school) {
        return "School not found";
    }

    $classes = $school->classes()->with('divisions')->get();
    dd($classes);
    $divisionsCount = [];

    foreach ($classes as $class) {
        $divisionsCount[$school->classe->classsModel->name] = $class->schoolsClassesDivisions->count();
    }
    return $this->successResponse($divisionsCount);
}
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        //
    }
}
