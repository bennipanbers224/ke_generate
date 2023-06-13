<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

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
        return view('auth.login');
    });

    Route::group(['middleware' => ['guest']], function() {
        /**
         * Register Routes
         */


        Route::get('/register', [RegisterController::class,'show']);
        Route::post('/registerPost', [RegisterController::class,'register']);

        /**
         * Login Routes
         */

        Route::get('/login', [LoginController::class,'show'])->name('login');
        Route::post('/loginPost', [LoginController::class, 'login']);

    });

    Route::group(['middleware' => ['auth']], function() {
        /**
         * Logout Routes
         */
        
         Route::get('/', function () {
            return view('signature.signature');
        });
        
        // Route::resource('signature', SignatureController::class);
        
        Route::get('generate-pdf', [SignatureController::class, 'generatePDF']);
        Route::post('/file-post', [SignatureController::class, 'store']);
        
        Route::get('/toGenerate', [SignatureController::class, 'generateKey']);
        Route::get('/toVerify', [SignatureController::class, 'verify']);
        Route::post('/verify-file',[SignatureController::class, 'getVerificationResult']);
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
    });
});

