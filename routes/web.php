<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post('api/login',[\App\Http\Controllers\UserController::class,'login']);
Route::get('api/index',[\App\Http\Controllers\IndexController::class,'index']);
Route::get('api/article',[\App\Http\Controllers\ArticleController::class,'index']);
Route::get('api/experiment',[\App\Http\Controllers\ArticleController::class,'experiment']);
Route::get('api/article/content',[\App\Http\Controllers\ArticleController::class,'content']);
Route::get('api/article/incLook',[\App\Http\Controllers\ArticleController::class,'incLook']);
Route::post('api/article/publish',[\App\Http\Controllers\ArticleController::class,'publish']);
Route::any('api/uploadImg',[\App\Http\Controllers\IndexController::class,'uploadImg']);
