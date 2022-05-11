<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\Api\ChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth Routes
Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);
Route::get('recover-password/{email}', [AuthController::class, 'recover_password']);
Route::post('reset-password', [AuthController::class, 'reset_password']);
Route::post('edit-profile', [AuthController::class, 'edit_profile']);
Route::get('change-online-status/{user_id}/{status}', [AuthController::class, 'change_online_status']);
Route::get('logout/{user_id}', [AuthController::class, 'logout']);
Route::get('delete-account/{user_id}', [AuthController::class, 'delete_account']);
Route::post('update-location', [AuthController::class, 'update_location']);

//App Routes
Route::get('home_screen', [MainController::class, 'home_screen']);

//Chat Routes
Route::get('get_messanger/{user_id}', [ChatController::class, 'get_messanger']);
Route::get('get_messages/{self_id}/{user_id}', [ChatController::class, 'get_messages']);
Route::post('send_message', [ChatController::class, 'send_message']);
Route::get('delete_message/{message_id}', [ChatController::class, 'delete_message']);
Route::post('edit_message', [ChatController::class, 'edit_message']);
Route::get('delete_messenger/{self_id}/{user_id}', [ChatController::class, 'delete_messenger']);
Route::get('read_messages/{self_id}/{user_id}', [ChatController::class, 'read_messages']);

