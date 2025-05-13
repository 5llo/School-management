<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\BusDriverController;
use App\Http\Controllers\FoodMealController;
use App\Http\Controllers\StudentsFoodMealController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ClassModelController;
use App\Http\Controllers\SchoolsClassController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\ContestsStudentController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SchoolsClassesDivisionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\StudentsSubjectController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SchoolsClassesDivisionPostController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivitiesStudentController;
use App\Http\Controllers\SchoolsClassesDivisionActivityController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\Authentication;

















/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //rgeteturn $request->user();
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getDriverStudents', [BusDriverController::class, 'getDriverStudents']);
    Route::get('/getBusDriverinfo', [BusDriverController::class, 'getBusDriverinfo']);
    Route::get('/getWeek_Schedule', [SchoolsClassesDivisionController::class, 'getWeek_Schedule']);
    Route::get('/showInfoTeacher', [TeacherController::class, 'show']);
    Route::get('/getStudentsByDivision', [TeacherController::class, 'getStudentsByDivision']);
    Route::post('/getstudent-andoralgrade', [AttendanceController::class, 'updateAttendances']);//this make with us
    Route::post('/setstudentsattendancesandoralgrade', [AttendanceController::class, 'setattendancesandgrade']);//this make with us
    Route::get('/showAllTeacherForSchool', [TeacherController::class, 'index']); //mahmoud
    Route::get('/getTopFeaturedStudents', [SchoolsClassesDivisionController::class, 'getTopFeaturedStudents']);//khalil
    Route::get('/getALLStudentInfo', [StudentController::class, 'getALLStudentInfo']);//khalil
    Route::get('/getStudentsInfoForSchool', [SchoolController::class, 'getStudentsInfoForSchool']);//mahmoud
    Route::post('/teachers/store', [TeacherController::class, 'store']);
    Route::post('/SchoolsClassesDivision/store', [SchoolsClassesDivisionController::class, 'store']);


   



});

Route::post('/login', [Authentication::class, 'login']);
Route::post('/register', [Authentication::class, 'register']);
Route::get('/getStudentInfo/{id}', [StudentController::class, 'getStudentInfo']);//khalil
Route::post('/updateStudentGrades', [StudentsSubjectController::class, 'updateStudentGrades']);//khalil







//school
Route::prefix('schools')->group(function () {
    Route::get('/index', [SchoolController::class, 'index']);
    Route::post('/store', [SchoolController::class, 'store']);
    Route::get('/show/{id}', [SchoolController::class, 'show']);
    Route::post('/Search', [SchoolController::class, 'searchSchoolByName']);
    Route::get('/countDivisionsPerClassInSchool/{schoolId}', [SchoolController::class, 'countDivisionsPerClassInSchool']);
});

//Parent
Route::get('/parent', [ParentController::class, 'index']);
Route::get('/childrenByParent/{parent_id}', [ParentController::class, 'childrenByParent']);
Route::get('/parent/show/{id}', [ParentController::class, 'show']);
Route::post('/parent/store', [ParentController::class, 'store']);
Route::post('/parent/update/{id}', [ParentController::class, 'update']);
Route::post('/searchParentByEmail', [ParentController::class, 'searchParentByEmail']);



//foodMeal
Route::prefix('food-meals')->group(function () {
    Route::get('/{schoolId}', [FoodMealController::class, 'index']);
    Route::post('/store', [FoodMealController::class, 'store']);
    Route::get('/show/{id}', [FoodMealController::class, 'show']);
    Route::get('/getStudentCountForFoodMeal/{foodMealId}', [FoodMealController::class, 'getStudentCountForFoodMeal']);

});

//StudentsFoodMeal
Route::get('/StudentsFoodMeal/{studentId}', [StudentsFoodMealController::class, 'studentFoodMeals']);
Route::post('/StudentsFoodMeal/store', [StudentsFoodMealController::class, 'store']);


//teacher
Route::prefix('teachers')->group(function () {
    Route::get('/{schoolId}', [TeacherController::class, 'index']);
    //Route::post('/store', [TeacherController::class, 'store']);
    Route::post('/searchTeacherByName', [TeacherController::class, 'searchTeacherByName']);
    //Route::get('/show/{id}', [TeacherController::class, 'show']);
    Route::post('/update/{teacher}', [TeacherController::class, 'update']);

    });


//Student
Route::prefix('students')->group(function () {
    Route::get('/{divisionId}', [StudentController::class, 'index']);
    Route::post('/store/{schoolId}', [StudentController::class, 'store']);
    Route::post('/update/{student}', [StudentController::class, 'update']);
    //Route::get('/getStudentInfoForSchool/{studentId}/{schoolId}', [StudentController::class, 'getStudentInfoForSchool']);
    Route::post('/searchStudentByName', [StudentController::class, 'searchStudentByName']);
    Route::post('/searchStudentsInBus', [StudentController::class, 'searchStudentsInBus']);


});


//contest
    Route::prefix('contests')->group(function () {
        Route::get('/{schoolId}', [ContestController::class, 'index']);
        Route::post('/store', [ContestController::class, 'store']);
        Route::get('/getFinishedContestsByTeacher/{teacherId}', [ContestController::class, 'getFinishedContestsByTeacher']);
        });

        //questions
        Route::prefix('questions')->group(function () {
            Route::post('/addQuestionsToContest/{contestId}', [ContestController::class, 'addQuestionsToContest']);
            Route::get('/showQuestionsAndOption/{contestId}', [ContestController::class, 'showQuestionsAndOptions']);
        });


//ContestsStudent
        Route::get('/upcoming-contests/{teacherId}', [ContestsStudentController::class, 'upcomingContestsByTeacher']);
        Route::get('/finished-contests/{student_id}', [ContestsStudentController::class, 'finishedContestsByStudent']);
        Route::get('/orderContestParticipants/{contest_id}', [ContestsStudentController::class, 'orderContestParticipants']);



//ReslustContest
        Route::get('/orderContestParticipants/{contestId}', [ContestsStudentController::class, 'orderContestParticipants']);

//homework//
        Route::get('/teacher/{teacherId}/homeworks', [HomeworkController::class, 'index']);//
        Route::post('/homeworks', [HomeworkController::class,'store']);
        Route::get('/homework/getHomeWorkWForDivision/{divisionId}', [HomeworkController::class, 'getHomeWorkWForDivision']);



//Comment
    Route::get('/Comments', [CommentController::class, 'index']);
    Route::post('/Comments/store', [CommentController::class, 'store']); //



//bus
Route::prefix('buses')->group(function () {
    Route::get('/{schoolId}', [BusDriverController::class, 'getBusDriversBySchool']);
    Route::get('/show/{driverId}', [BusDriverController::class, 'getBusDriverBySchoolAndId']);
    Route::post('/store', [BusDriverController::class, 'store']);

    });


//SchoolsClassesDivision
Route::prefix('schools-classes-division')->group(function () {

    Route::get('/{schoolClassId}/{divisionId}', [SchoolsClassesDivisionController::class, 'getSchoolDivisionsDetails']);
    //Route::post('/store', [SchoolsClassesDivisionController::class, 'store']);
    Route::post('/searchStudentByNameInDivision/{divisionId}', [SchoolsClassesDivisionController::class, 'searchStudentByNameInDivision']);


});


//AcademicYearController
Route::prefix('academic-year')->group(function () {
    Route::get('/index', [AcademicYearController::class, 'index']);
    Route::post('/store', [AcademicYearController::class, 'store']);

});


//Season
Route::prefix('season')->group(function () {
    Route::get('/index', [SessionController::class, 'index']);
    Route::post('/store', [SessionController::class, 'store']);
    });


//StudentsSubject
Route::prefix('StudentsSubject')->group(function () {
    Route::get('/index', [StudentsSubjectController::class, 'index']);
    Route::post('/store', [StudentsSubjectController::class, 'store']);
    Route::post('/update/{studentsSubject}', [StudentsSubjectController::class, 'update']);
    Route::get('/show/{studentId}', [StudentsSubjectController::class, 'show']);
    Route::get('/showFinallyResult/{studentId}', [StudentsSubjectController::class, 'showFinallyResult']);
    Route::get('/avareg/{id}', [StudentsSubjectController::class, 'showStudentsSubjectAvareg']);
    Route::get('/ShowFeaturedStudents/{id}', [StudentsSubjectController::class, 'ShowFeaturedStudents']);



});


//subject
Route::prefix('Subjects')->group(function () {
    Route::get('/index', [SubjectController::class, 'index']);
    Route::post('/store', [SubjectController::class, 'store']);
    });


//Posts
Route::prefix('posts')->group(function () {
    Route::get('/index', [PostController::class, 'index']);
    Route::post('/store', [PostController::class, 'store']);
});


//SchoolsClassesDivisionPost
Route::prefix('schools-classes-division-post')->group(function () {
    Route::get('/index', [SchoolsClassesDivisionPostController::class, 'index']);
    Route::post('/store', [SchoolsClassesDivisionPostController::class, 'store']);
    Route::get('/show/{id}', [SchoolsClassesDivisionPostController::class, 'show']);

    });


//Activity
Route::prefix('activity')->group(function () {
    Route::get('/index', [ActivityController::class, 'index']);
    Route::get('/show/{id}', [ActivityController::class, 'show']);
    Route::get('/getActiveActivities', [ActivityController::class, 'getActiveActivities']);
    Route::post('/store', [ActivityController::class, 'store']); //time/start/
    Route::get('/destroy/{id}', [ActivityController::class, 'destroy']);
    Route::post('/searchActivityByName', [ActivityController::class, 'searchActivityByName']);
});

//ActivitiesStudent
Route::prefix('activities-student')->group(function () {
    Route::get('/index', [ActivitiesStudentController::class, 'index']);
    Route::post('/store', [ActivitiesStudentController::class, 'store']);
    Route::get('/{id}', [ActivitiesStudentController::class, 'show']);
    Route::get('/showStudentsForActivity/{activityId}', [ActivitiesStudentController::class, 'showStudentsForActivity']);
    Route::get('getSuspendedRecords/{id}', [ActivitiesStudentController::class, 'getSuspendedRecords']);
    Route::get('acceptOrRejectRequest/{recordId}/{action}', [ActivitiesStudentController::class, 'acceptOrRejectRequest']);
   // Route::get('getActiveRecords/{id}', [ActivitiesStudentController::class, 'getActiveRecords']);



});



//SchoolsClassesDivisionActivity
Route::prefix('schools-classes-division-activity')->group(function () {
    Route::get('/index', [SchoolsClassesDivisionActivityController::class, 'index']);
    Route::post('/store', [SchoolsClassesDivisionActivityController::class, 'store']);
    Route::get('/show/{id}', [SchoolsClassesDivisionActivityController::class, 'show']);
});


//SchoolsClass
Route::prefix('schools-class')->group(function () {
    Route::get('/index/{id}', [SchoolsClassController::class, 'index']);
});


//Conversation
Route::prefix('conversation')->group(function () {
    Route::post('/createConversation', [ConversationController::class, 'createConversation']);
    Route::post('/sendMessage', [ConversationController::class, 'sendMessage']);
    Route::post('/message/load', [ConversationController::class, 'loadMessages']);
    Route::post('/updateUserLastSeen', [ConversationController::class, 'updateUserLastSeen']);


});
