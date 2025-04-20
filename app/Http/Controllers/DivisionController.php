<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Http\Resources\DivisionResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function index()
    {
        $divisions = Division::all();
        return DivisionResource::collection($divisions);
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'exam_schedule' => 'nullable|string',
            'week_schedule' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return $this->requiredField($validator->errors()->first());
        }
        $data = $request->all();

    $division = Division::create($data);

   
    return response()->json([
        'success' => true,
        'message' => 'تمت إضافة بنجاح!',
        'data' => $division
    ], 201); 


    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division,$id)
    {
        $Division = Division::find($id);

        if (!$Division) {
            return response()->json(['message' => 'Division not found'], 404);
        }

        return new DivisionResource($Division);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Division $division)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        //
    }
}
