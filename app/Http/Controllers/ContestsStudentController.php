<?php

namespace App\Http\Controllers;
use App\Models\Contest;
use App\Models\ContestsStudent;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\ContestResource;
use App\Http\Resources\ContestStudentResource;
use App\Traits\GeneralTrait;

class ContestsStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     use GeneralTrait;

     public function upcomingContestsByTeacher($teacherId)
    {
        try{
        $upcomingContests = Contest::where('teacher_id', $teacherId)
        ->where('date_start', '>', Carbon::now())
        ->get();
        return $this->successResponse(ContestResource::collection($upcomingContests));
    } 
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);

    }
}

    public function finishedContestsByStudent($student_id)
    {
   try{
        $finishedContests = ContestsStudent::where('student_id', $student_id)
        ->whereHas('contest', function ($query) {
            $query->where('date_start', '<', Carbon::now());
        })
        ->get();
        return $this->successResponse(ContestStudentResource::collection($finishedContests));
    } 
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);

    }
}

    public function orderContestParticipants($contestId)
    {
        try{
        $participants = ContestsStudent::where('contest_id', $contestId)
                        ->orderBy('result', 'desc') 
                        ->with('student')
                        ->get();
     return $this->successResponse(ContestStudentResource::collection($participants));
    } 
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);

    }}

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ContestsStudent $contestsStudent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContestsStudent $contestsStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContestsStudent $contestsStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContestsStudent $contestsStudent)
    {
        //
    }
}
