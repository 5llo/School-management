<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\notification\firebaseController;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;
use App\Models\BusDriver;
use App\Models\ParentModel;
use App\Models\School;
use Google\Client;
use Illuminate\Support\Str;

class Authentication extends Controller
{
    use GeneralTrait;

    public function login(Request $request)
    {
        if ($request->type == 0) {
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
                    if ($request->filled('fcmtoken')) {
                        $busdriver->fcmtoken = $request->fcmtoken;
                        $busdriver->save();
                    }

                     firebaseController::sendToUserFCM(
        $request->fcmtoken,
        "مرحبًا " . $busdriver->name,
        "تم تسجيل دخولك بنجاح 🎉"
    );
                    $token = $busdriver->createToken('authToken')->plainTextToken;


                    return $this->successResponse($busdriver, 'you have been logged in successfully.', 200, $token);
                } else {
                    return $this->errorResponse('Invalid email or password', 400);
                }
            } catch (\Exception $ex) {
                return $this->errorResponse($ex->getMessage(), 500);
            }
        } elseif ($request->type == 1) {
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
  if ($request->filled('fcmtoken')) {
        $Teacher->fcmtoken = $request->fcmtoken;
        $Teacher->save();
    }

    firebaseController::sendToUserFCM(
        $request->fcmtoken,
        "مرحبًا " . $Teacher->name,
        "تم تسجيل دخولك بنجاح 🎉"
    );
                    $token = $Teacher->createToken('authToken')->plainTextToken;


                    return $this->successResponse($Teacher, 'you have been logged in successfully.', 200, $token);
                } else {
                    return $this->errorResponse('Invalid email or password', 400);
                }
            } catch (\Exception $ex) {
                return $this->errorResponse($ex->getMessage(), 500);
            }
        } elseif ($request->type == 2) {
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
  if ($request->filled('fcmtoken')) {
        $Teacher->fcmtoken = $request->fcmtoken;
        $Teacher->save();
    }

     firebaseController::sendToUserFCM(
        $request->fcmtoken,
        "مرحبًا " . $Teacher->name,
        "تم تسجيل دخولك بنجاح 🎉"
    );
                    $token = $Teacher->createToken('authToken')->plainTextToken;


                    return $this->successResponse($Teacher, 'you have been logged in successfully.', 200, $token);
                } else {
                    return $this->errorResponse('Invalid email or password', 400);
                }
            } catch (\Exception $ex) {
                return $this->errorResponse($ex->getMessage(), 500);
            }
        } elseif ($request->type == 3) {
            try {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|max:30',
                    'password' => 'required|string|max:16|min:4',

                ]);

                if ($validator->fails()) {
                    return $this->errorResponse($validator->errors(), 422);
                }

                $is_email = $Teacher = School::where('email', $request->email)->first();

                if ($is_email && $Teacher->password == $request->password) {

                    $token = $Teacher->createToken('authToken')->plainTextToken;


                    return $this->successResponse($Teacher, 'you have been logged in successfully.', 200, $token);
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
       $user = ParentModel::create($request->except('fcmtoken'));
       if ($request->filled('fcmtoken')) {
    $user->fcmtoken = $request->fcmtoken;
    $user->save();
}

 firebaseController::sendToUserFCM(
        $request->fcmtoken,
        "مرحبًا " . $user->name,
        "تم تسجيل دخولك بنجاح 🎉"
    );


        // Create token using Sanctum
        $token = $user->createToken('authToken')->plainTextToken;


        return $this->successResponse($user, 'User registered successfully', 201, $token);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse([], 'You  have been logged out successfully.');
    }







public function googleLogin(Request $request)
{
    try {
        $request->validate([
            'token' => 'required',
            'type'  => 'required|in:0,1,2,3',
            'fcmtoken' => 'nullable|string',
        ]);
// \Log::info("Request payload: " . json_encode($request->all()));

$googleClient = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
    $payload = $googleClient->verifyIdToken($request->token);
if (!$payload) {
            return $this->errorResponse('توكن جوجل غير صالح.', 401);
        }

        $email = $payload['email'];
        $name = $payload['name'];

        // 🧠 نحدد الجدول حسب النوع
        $user = null;
        if ($request->type == 0) {
            $user = BusDriver::where('email', $email)->first();
        } elseif ($request->type == 1) {
            $user = Teacher::where('email', $email)->first();
        } elseif ($request->type == 2) {
            $user = ParentModel::where('email', $email)->first();
             if (!$user) {
        $user = ParentModel::create([
            'name' => $name,
            'email' => $email,
            'phone' => Str::random(10),  // عدل حسب الأعمدة المطلوبة
             'password' => bcrypt(Str::random(16)),
        ]);
    }
        } elseif ($request->type == 3) {
            $user = School::where('email', $email)->first();
        }

        if (!$user) {
            return $this->errorResponse('لا يوجد مستخدم مرتبط بهذا الإيميل.', 404);
        }

        // ✉️ حفظ FCM إذا موجود
        if ($request->filled('fcmtoken')) {
            $user->fcmtoken = $request->fcmtoken;
            $user->save();

            firebaseController::sendToUserFCM(
                $request->fcmtoken,
                "مرحبًا " . $user->name,
                "تم تسجيل دخولك باستخدام Google 🎉"
            );
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->successResponse($user, 'تم تسجيل الدخول بنجاح باستخدام Google', 200, $token);
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }












}}
