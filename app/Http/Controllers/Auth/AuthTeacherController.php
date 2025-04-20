<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthTeacherController extends Controller
{
    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:30',
                'password' => 'required|string|max:20|min:8',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $is_email = $Teacher = Teacher::where('email', $request->email)->first();

            if ($is_email && $Teacher->password == $request->password) {

                $token = $Teacher->createToken('authToken')->plainTextToken;

                $data['token'] = $token;
                $data['info'] = $Teacher;

                return $this->successResponse($data, 'you have been logged in successfully.');
            } else {
                return $this->errorResponse('Invalid email or password', 400);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse([], 'You  have been logged out successfully.');
    }
}


