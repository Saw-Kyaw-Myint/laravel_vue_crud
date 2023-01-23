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

use App\Http\Controllers\PostController;

Route::get('post', [PostController::class, 'index'])->name('post');
Route::post('post', [PostController::class, 'createPost']);
Route::put('post/update/{id}', [PostController::class, 'update']);
Route::delete('post/{id}', [PostController::class, 'deletePost']);

Route::get('/', function () {
    return view('welcome');
});
