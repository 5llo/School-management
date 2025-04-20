<?php

namespace App\Http\Controllers;
use App\Models\ParentModel; 
use App\Http\Resources\ParentResource;
use App\Http\Resources\StudentResource;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Student;

class ParentController extends Controller
{
    use GeneralTrait;

    public function index()
    {
      try{
        $parent = ParentModel::all();
        return ParentResource::collection($parent);
        return $this->successResponse(ParentResource::collection($parent));
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
    

    }


    public function show($id)
    {
        try{
        $ParentModel = ParentModel::find($id);

        if (!$ParentModel) {
            return response()->json(['message' => 'ParentModel not found'], 404);
        }

        $parent= new ParentResource($ParentModel);
        return $this->successResponse($parent, 'successfull.');
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
    
    }
    
    public function store(Request $request)
    {
        try{
         $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:parents,email',
        'password' => 'required|min:6',
        'latitude'=>'nullable',
        'longitude' => 'nullable',
        'phone' => 'nullable|string|max:20',
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

    $parent = ParentModel::create($data);
    return $this->successResponse($parent, 'created successfull.');
} catch (\Exception $ex) {
    return $this->errorResponse($ex->getMessage(), 500);
}
}


public function childrenByParent($parent_id)
{
    try{
    $parent = ParentModel::with('students')->find($parent_id);

    
    if (!$parent) {
        return response()->json(['message' => 'Parent not found'], 404);
    }

    
    if ($parent->students->isEmpty()) {
        return response()->json(['message' => 'No children found for this parent'], 404);
    }

    return $this->successResponse(StudentResource::collection($parent->students));
} catch (\Exception $ex) {
    return $this->errorResponse($ex->getMessage(), 500);
}

}

public function searchParentByEmail(Request $request)
{
    try{
    $email = $request->input('email');
    
    $parent = ParentModel::where('email', $email)->first();

    if($parent) { 
        $parentEmail= new ParentResource($parent);
        return $this->successResponse($parentEmail);
    } else {
        return  $this->successResponse(['message' => 'parent not found']);

     }
   
     } catch (\Exception $ex) {
    return $this->errorResponse($ex->getMessage(), 500);
     }
  }


  public function update(Request $request, $id)
  {
      try {
      $parent = ParentModel::find($id);

      if (!$parent) {
        return  $this->successResponse(['message' => 'parent not found']);

      }

     
         $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
       // 'email' => 'required|email|unique:parents,email',
        'password' => 'required|min:6',
       // 'latitude'=>'nullable',
       // 'longitude' => 'nullable',
        'phone' => 'nullable|string|max:20',
    ]);


    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422); 
    }
    $data = $request->all();
    $parent->update($data);
          return $this->successResponse($parent, 'update successfull.');
      } catch (\Exception $ex) {
          return $this->errorResponse($ex->getMessage(), 500);
      }
          
      }

}
