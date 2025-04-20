<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BusDriver;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthBusdriverController extends Controller
{
    use GeneralTrait;
    public function login(Request $request)
    {
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

                $data['token'] = $token;
                $data['info'] = $busdriver;

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
