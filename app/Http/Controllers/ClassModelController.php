<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\SchoolsClass;
use App\Models\SchoolsClassesDivision;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

class ClassModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function index()
    {
        try{
        $ClassModel = ClassModel::all();
        return $this->successResponse($ClassModel);
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
    }


    public function getDivisionIdByClassId(Request $request)
{
    try {
        $classId = $request->input('class_id');
        $schoolId = Auth::user()->id; 

        $schoolClass = SchoolsClass::where('class_id', $classId)
            ->where('school_id', $schoolId)
            ->first();

        if (!$schoolClass) {
            return $this->errorResponse('Class not found for the given school', 404);
        }

        $schoolClassDivision = SchoolsClassesDivision::where('school_class_id', $schoolClass->id)->get();

        $divisionsData = [];
        foreach ($schoolClassDivision as $division) {
            $divisionData = [
                'division_id' => $division->division_id,
                'division_name' => $division->division->name, // اسم الشعبة
            ];
            $divisionsData[] = $divisionData;
        }

        return $this->successResponse( $divisionsData);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassModel $classModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassModel $classModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassModel $classModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassModel $classModel)
    {
        //
    }
}
