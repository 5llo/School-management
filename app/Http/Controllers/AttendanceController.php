<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function updateAttendance(Request $request)
{
    $studentId = Auth::user()->division->students->subjects;

    
    $date = $request->input('date');

    
    // التحقق من وجود الطالب
    $attendance = Attendance::where('student_id', $studentId)->firstOrFail();

    // التحقق من وجود التاريخ في مصفوفة الحضور
    if (!isset($attendance->attendance_array[$date])) {
        return response()->json(['message' => 'التاريخ غير موجود في سجل الحضور.'], 404);
    }

    // التحقق من وجود قيمة جديدة للحضور في الطلب
    if (!$request->has('attendance_value') || !in_array($request->input('attendance_value'), [0, 1, 2, 3])) {
        return response()->json(['message' => 'يجب توفير قيمة حضور صحيحة (0, 1, 2, أو 3).'], 400);
    }

    // الحصول على قيمة الحضور الجديدة من الطلب
    $newAttendanceValue = $request->input('attendance_value');

    // الحصول على قيمة الحضور القديمة
    $oldAttendanceValue = $attendance->attendance_array[$date];

    // تحديث قيمة الحضور للتاريخ المحدد
    $attendanceArray = $attendance->attendance_array;
    Arr::set($attendanceArray, $date, $newAttendanceValue);
    $attendance->attendance_array = $attendanceArray;
    $attendance->save();

    // إرجاع القيمة القديمة والجديدة للحضور
    return response()->json([
        'message' => 'تم تحديث سجل الحضور بنجاح.',
        'old_value' => $oldAttendanceValue,
        'new_value' => $newAttendanceValue,
    ]);
}
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
