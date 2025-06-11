<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\ParentModel; // تأكد من وجود هذا النموذج أو استبدله باسم النموذج الصحيح
use App\Http\Controllers\notification\firebaseController;

class ChatController extends Controller
{
    public function sendFcmToTeacher(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'message' => 'required|string',
        ]);

        $teacher = Teacher::find($request->teacher_id);
        $fcmtoken = $teacher->fcmtoken;

        if (!$fcmtoken) {
            return response()->json(['error' => 'FCM Token غير موجود'], 422);
        }

        firebaseController::sendToUserFCM(
            $fcmtoken,
            "رسالة جديدة من ولي الأمر",
            $request->message
        );

        return response()->json(['status' => 'تم إرسال الإشعار إلى المعلم بنجاح']);
    }

    public function sendFcmToParent(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:parents,id', // تأكد من وجود جدول parents
            'message' => 'required|string',
        ]);

        $parent = ParentModel::find($request->parent_id); // استبدل ParentModel بالاسم الصحيح
        $fcmtoken = $parent->fcmtoken;

        if (!$fcmtoken) {
            return response()->json(['error' => 'FCM Token غير موجود'], 422);
        }

        firebaseController::sendToUserFCM(
            $fcmtoken,
            "رسالة جديدة من المعلم",
            $request->message
        );

        return response()->json(['status' => 'تم إرسال الإشعار إلى ولي الأمر بنجاح']);
    }
}
