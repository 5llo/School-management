<?php

namespace App\Http\Controllers;

use App\Models\StudentsFoodMeal;
use App\Models\Student;
use App\Models\FoodMeal;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\studentFoodMealResource;
use App\Http\Resources\FoodMealResource;
use App\Traits\GeneralTrait;

use Illuminate\Http\Request;

class StudentsFoodMealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function studentFoodMeals($studentId)
    {
        try{
        $studentFoodMeals = StudentsFoodMeal::where('student_id', $studentId)->with('foodMeal')->get();

        if ($studentFoodMeals->isEmpty()) {
            return response()->json(['message' => 'No food meals found for the student'], 404);
        }
        return $this->successResponse( studentFoodMealResource::collection($studentFoodMeals));
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
            'student_id' => 'required|exists:students,id',
            'food_meal_id' => 'required|exists:food_meals,id',
            'status' => 'required|in:active,inactive,suspended',
            'process_number' => 'required|numeric|min:0',
            'notes' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); 
        }  
         $data = $request->all();
        $studentsFoodMeal = StudentsFoodMeal::create($data);
        $studentFood= new studentFoodMealResource($studentsFoodMeal);
        return $this->successResponse($studentFood, 'created successfull.');
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }


    
    
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentsFoodMeal $studentsFoodMeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentsFoodMeal $studentsFoodMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentsFoodMeal $studentsFoodMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentsFoodMeal $studentsFoodMeal)
    {
        //
    }
}
