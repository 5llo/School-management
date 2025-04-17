<?php

namespace App\Http\Controllers;

use App\Models\SchoolsClass;
use App\Http\Resources\SchoolClassResource;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;


class SchoolsClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function index($id)
    {
        try{
        $SchoolsClass = SchoolsClass::with('school')->where('school_id', $id)->get();
        return $this->successResponse(SchoolClassResource::collection($SchoolsClass));
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
    public function show(SchoolsClass $schoolsClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolsClass $schoolsClass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolsClass $schoolsClass)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolsClass $schoolsClass)
    {
        //
    }
}
