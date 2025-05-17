<?php

namespace App\Http\Controllers;

use App\Models\FoodMeal;
use App\Models\School;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FoodMealResource;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

class FoodMealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function index()
    {
        try{
          $schoolId  = Auth::user()->id;
        $foodMeals = FoodMeal::where('school_id', $schoolId)->get();
        return $this->successResponse(FoodMealResource::collection($foodMeals));
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
            //'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'contents' => 'nullable|string',
            'entrees' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'day' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday', 
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); 
        }  
         $data = $request->all();
        $data['school_id'] = Auth::user()->id;
        $foodMeal = FoodMeal::create($data);

        $foodmeal= new FoodMealResource($foodMeal);
        return $this->successResponse($foodmeal);
    } 
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }

    }
    

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
{
    try {
        $id = $request->get('id');

        $foodMeal = FoodMeal::find($id);

        if (!$foodMeal) {
            return response()->json(['message' => 'Food Meal not found'], 404);
        }

        $foodmeal = new FoodMealResource($foodMeal);
        return $this->successResponse($foodmeal);
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}
    

    public function getStudentCountForFoodMeal(Request $request)
{
    try {
        $foodMealId = $request->get('foodMealId');
        
        $foodMeal = FoodMeal::findOrFail($foodMealId);
        $foodMealDay = $foodMeal->day;
        $studentCount = $foodMeal->students->count();

        return $this->successResponse($foodMealDay, $studentCount);
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodMeal $foodMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodMeal $foodMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodMeal $foodMeal)
    {
        //
    }


    
}
