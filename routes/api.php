<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/Busdriver/login',[\App\Http\Controllers\Auth\AuthBusdriverController::class,'login']);
Route::post('/Teacher/login',[\App\Http\Controllers\Auth\AuthTeacherController::class,'login']);
Route::get('/test',function(){
    return "hello from the website  :)";
});
Route::middleware('auth:sanctum')->group(function(){
    Route::get('Busdriver/logout',[\App\Http\Controllers\Auth\AuthBusdriverController::class,'logout']);
});
Route::middleware('auth:sanctum')->group(function(){
    Route::get('Teacher/logout',[\App\Http\Controllers\Auth\AuthTeacherController::class,'logout']);
});
