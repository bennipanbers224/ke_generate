<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Models\data_file;

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




Route::group(['namespace' => 'App\Http\Controllers'], function()
{   
    /**
     * Home Routes
     */
    Route::get('/', function () {
        return view("signature.user-home");
    })->name('home');
    Route::group(['middleware' => ['guest']], function() {
        /**
         * Register Routes
         */

        /**
         * Login Routes
         */

        Route::get('/instansi', [LoginController::class,'show'])->name('instansi');
        Route::post('/loginPost', [LoginController::class, 'login']);
        Route::get('/register', [RegisterController::class,'show']);
        Route::post('/registerPost', [RegisterController::class,'register']);
        

    });

    Route::group(['middleware' => ['auth']], function() {
        /**
         * Logout Routes
         */

        // if(Auth::check()){
        //     if(Auth::user()->status == "Instansi"){
        //         Route::get('/homeIntansi',[SignatureController::class, 'generateKey']);
        //     }
        // }
        
        
        Route::get('/homeInstansi',[SignatureController::class, 'generateKey']);
        // Route::resource('signature', SignatureController::class);
        Route::get('/toVerify', [SignatureController::class, 'verify']);
        Route::post('/verify-file',[SignatureController::class, 'getVerificationResult']);
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
        Route::post('/manual-signing', [SignatureController::class, 'manualSigning']);
    });
});



