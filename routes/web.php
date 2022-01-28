<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseUserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => 'PHP_VERSION',
        'name' => '御坂美琴',
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');


// 通常ユーザーの管理ページを表示します。
Route::get('/mydashbord', [CourseUserController::class,'dashbord'])->middleware('auth');
Route::get('/course/{category}',[CourseController::class, 'index'])->middleware('auth')->name('course.category');
Route::get('/course/detail/{id}',[CourseController::class, 'detail'])->middleware('auth')->name('course.detail');
Route::post('/course/done', [CourseController::class, 'done'])->middleware('auth')->name('course.done');
Route::post('/course/all/done', [CourseUserController::class,'allDone'])->middleware('auth')->name('course.all.done');