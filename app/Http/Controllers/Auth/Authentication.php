<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;
use App\Models\BusDriver;
use App\Models\ParentModel;
use App\Models\School;
class Authentication extends Controller
{
    use GeneralTrait;
    public function login(Request $request)
    {
        if($request->type==0){
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:30',
                'password' => 'required|string|max:8|min:4',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $is_email = $busdriver = BusDriver::where('email', $request->email)->first();

            if ($is_email && $busdriver->password == $request->password) {

                $token = $busdriver->createToken('authToken')->plainTextToken;


                return $this->successResponse($busdriver, 'you have been logged in successfully.',200,$token);
            } else {
                return $this->errorResponse('Invalid email or password', 400);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }}
        elseif($request->type==1){
            try {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|max:30',
                    'password' => 'required|string|max:8|min:4',
                ]);

                if ($validator->fails()) {
                    return $this->errorResponse($validator->errors(), 422);
                }

                $is_email = $Teacher = Teacher::where('email', $request->email)->first();

                if ($is_email && $Teacher->password == $request->password) {

                    $token = $Teacher->createToken('authToken')->plainTextToken;


                    return $this->successResponse($Teacher, 'you have been logged in successfully.',200,$token);
                } else {
                    return $this->errorResponse('Invalid email or password', 400);
                }
            } catch (\Exception $ex) {
                return $this->errorResponse($ex->getMessage(), 500);
            }
        }
        elseif($request->type==2){
            try {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|max:30',
                    'password' => 'required|string|max:8|min:4',
                ]);

                if ($validator->fails()) {
                    return $this->errorResponse($validator->errors(), 422);
                }

                $is_email = $Teacher = ParentModel::where('email', $request->email)->first();

                if ($is_email && $Teacher->password == $request->password) {

                    $token = $Teacher->createToken('authToken')->plainTextToken;


                    return $this->successResponse($Teacher, 'you have been logged in successfully.',200,$token);
                } else {
                    return $this->errorResponse('Invalid email or password', 400);
                }
            } catch (\Exception $ex) {
                return $this->errorResponse($ex->getMessage(), 500);
            }
        }
        elseif($request->type==3){
            try {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|max:30',
                    'password' => 'required|string|max:15|min:4',
                ]);

                if ($validator->fails()) {
                    return $this->errorResponse($validator->errors(), 422);
                }

                $is_email = $Teacher = School::where('email', $request->email)->first();

                if ($is_email && $Teacher->password == $request->password) {

                    $token = $Teacher->createToken('authToken')->plainTextToken;


                    return $this->successResponse($Teacher, 'you have been logged in successfully.',200,$token);
                } else {
                    return $this->errorResponse('Invalid email or password', 400);
                }
            } catch (\Exception $ex) {
                return $this->errorResponse($ex->getMessage(), 500);
            }
        }
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'      => 'required|email|unique:parents,email',
            'phone'      => 'string|max:20|unique:parents,phone',
            'password'   => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // Create user
        $user = ParentModel::create(
$request->all()
        );

         // Create token using Sanctum
    $token = $user->createToken('auth_token')->plainTextToken;


           return $this->successResponse($user, 'User registered successfully', 201, $token);

    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse([], 'You  have been logged out successfully.');
    }
}
