<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Question;
use App\Models\ContestsStudent;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ContestResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function index($school_id)
    {
        try{
        $school = School::find($school_id);

    if (!$school) {
        return response()->json(['message' => 'School not found'], 404);
    }

    $contests = Contest::whereHas('teacher', function ($query) use ($school_id) {
        $query->where('school_id', $school_id);
    })->with(['teacher', 'students'])->get();
    
    if ($contests->isEmpty()) {
        return response()->json(['message' => 'No contests found for this school'], 404);
    }
    return $this->successResponse(ContestResource::collection($contests));
} 
catch (\Exception $ex) {
    return $this->errorResponse($ex->getMessage(), 500);
}
    
    }

    public function getFinishedContestsByTeacher($teacherId)
    {
        try {
            $currentDate = Carbon::now();

            $finishedContests = Contest::where('teacher_id', $teacherId)
                                        ->whereDate('date_start', '<', $currentDate)
                                        ->get();
    
                                    if($finishedContests->isEmpty()) {
                                        return response()->json(['message' => 'No finished contests found for the teacher.'], 404);
                                    }
                                    return $this->successResponse(ContestResource::collection($finishedContests));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_start' => 'required|date',
            'duration' => 'required|integer|min:1',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); 
        }
        $contest = Contest::create($request->all());
        return $this->successResponse($contest);
    } 
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }


    }

    /**
     * Display the specified resource.
     */
    public function show(Contest $contest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contest $contest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest)
    {
        //
    }
    public function addQuestionsToContest(Request $request, $contestId)
    {
        try {
           
            $contest = Contest::find($contestId);
            if (!$contest) {
                return response()->json(['error' => 'The contest does not exist.'], 404);
            }

            
            $validatedData = $request->validate([
                'question' => 'required|string',
                'correct_answer' => 'required|string',
                'options' => 'required|array|min:2', 
            ]);
            $question = new Question();
            $question->question = $validatedData['question'];
            $question->correct_answer = $validatedData['correct_answer'];
            $question->options = json_encode($validatedData['options']); 
            $question->contest_id = $contestId;

            $question->save();
            
            return $this->successResponse($question);
        } 
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    
    
    }
    
    
    public function showQuestionsAndOptions($contestId)
    {
        try{
        $questions = Question::where('contest_id', $contestId)->get();
    
        $formattedQuestions = $questions->map(function ($question) {
            $question->options = json_decode($question->options);
            return $question;
        });

        return $this->successResponse($formattedQuestions);
    } 
    catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }

    }



}
