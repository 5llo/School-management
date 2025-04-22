<?php

namespace App\Http\Controllers;

use App\Models\SchoolsClassesDivisionPost;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;


class SchoolsClassesDivisionPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

    public function index()
    {
        try{
            $schoolsClassesDivisionPosts = SchoolsClassesDivisionPost::with('post', 'schoolClassesDivision')->get();
            return $this->successResponse( PostResource::collection($schoolsClassesDivisionPosts));
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
               'post_id' => 'required|exists:posts,id',
            'school_classes_division_id' => 'required|exists:schools_classes_division,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422); 
            }  
             $data = $request->all();
            $SchoolsClassesDivisionPost = SchoolsClassesDivisionPost::create($data);
            return $this->successResponse($SchoolsClassesDivisionPost, 'created successfull.');
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $schoolsClassesDivisionPosts = SchoolsClassesDivisionPost::with('post', 'schoolClassesDivision')->where('school_classes_division_id', $id)->get();

            if ($schoolsClassesDivisionPosts->isEmpty()) {
                return $this->successResponse(['message' => 'No posts found for this school classes division']);
            }
    
            return $this->successResponse( PostResource::collection($schoolsClassesDivisionPosts));
        
    
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolsClassesDivisionPost $schoolsClassesDivisionPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolsClassesDivisionPost $schoolsClassesDivisionPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolsClassesDivisionPost $schoolsClassesDivisionPost)
    {
        //
    }
}
