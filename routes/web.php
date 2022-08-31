<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\FindFreindsController;

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





Auth::routes();

Route::get('/', [PostController::class, 'index'])->name('post.index');

Route::resource('/post', PostController::class);

Route::get('/find-freinds', [FindFreindsController::class,'index'])->name('find-freinds');

Route::get('/chats/{id?}', [ChatController::class, 'index'])->name('chats');



Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');
Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');


Route::post('/follow/{user}', [FollowsController::class, 'store'])->name('follow.store');
