<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminNavigationController;
use App\Http\Controllers\Recruiter\RecruiterNavigationController;
use App\Http\Controllers\Candidate\CandidateNavigationController;

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
    return view('auth.login');
});

Auth::routes();

Route::get('admin-logout', function(){
	\Auth::logout();

	return view('auth.login');
})->name('admin-logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group([ 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'CheckUserRole']], function() {
    Route::get('/', [App\Http\Controllers\Admin\AdminNavigationController::class, 'dashboard'])->name('dashboard');
	Route::get('verify-recruiter/{user_id}', [App\Http\Controllers\Admin\AdminNavigationController::class,'verify_recruiter'])->name('verify_recruiter');	
	Route::get('delete-user/{user_id}', [App\Http\Controllers\Admin\AdminNavigationController::class,'delete_user'])->name('delete_user');	
	Route::get('unverified-recruiters', [App\Http\Controllers\Admin\AdminNavigationController::class,'unverified_recruiters'])->name('unverified_recruiters');
	Route::get('verified-recruiters', [App\Http\Controllers\Admin\AdminNavigationController::class,'verified_recruiters'])->name('verified_recruiters');
	Route::get('candidates', [App\Http\Controllers\Admin\AdminNavigationController::class,'candidates'])->name('candidates');
	Route::get('event', [App\Http\Controllers\Admin\AdminNavigationController::class,'event'])->name('event');
});

Route::group([ 'prefix' => 'candidate', 'as' => 'user.', 'middleware' => ['auth', 'CheckUserRole']], function() {
    Route::get('/', [App\Http\Controllers\Candidate\CandidateNavigationController::class, 'dashboard'])->name('dashboard');
});

Route::group([ 'prefix' => 'recruiter', 'as' => 'recruiter.', 'middleware' => ['auth', 'CheckUserRole']], function() {
    Route::get('/', [App\Http\Controllers\Recruiter\RecruiterNavigationController::class, 'dashboard'])->name('dashboard');
});

Route::prefix('dev')->group(function(){
	Route::get('config-clear', function(){
		try{
			\Artisan::call('config:clear');
			echo "Configuration cache cleared!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('route-clear', function(){
		try{
			\Artisan::call('route:clear');
			echo "Route cache cleared!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('view-clear', function(){
		try{
			\Artisan::call('view:clear');
			echo "View cache cleared!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('config-cache', function(){
		try{
			\Artisan::call('config:cache');
			echo "Configuration cache cleared!";
			echo "Configuration cached successfully!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('route-cache', function(){
		try{
			\Artisan::call('route:cache');
			echo "Route cache cleared!";
			echo "Route cached successfully!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('view-cache', function(){
		try{
			\Artisan::call('view:cache');
			echo "View cache cleared!";
			echo "View cached successfully!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

