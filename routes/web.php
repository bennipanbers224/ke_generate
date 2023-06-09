<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignatureController;

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
    Route::get('/', 'HomeController@index')->name('home.index');

    Route::group(['middleware' => ['guest']], function() {
        /**
         * Register Routes
         */
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');

        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');

    });

    Route::group(['middleware' => ['auth']], function() {
        /**
         * Logout Routes
         */
        Route::get('/', function () {
            return view('signature.signature');
        });
        
        
        Route::resource('signature', SignatureController::class);
        
        Route::get('generate-pdf', [SignatureController::class, 'generatePDF']);
        
        Route::get('/toGenerate', [SignatureController::class, 'generateKey']);
        Route::get('/toVerify', [SignatureController::class, 'verify']);
        Route::post('/verify-file',[SignatureController::class, 'getVerificationResult']);
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
    });
});

