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

        // if(Auth::check()){
        //     if(Auth::user()->status == "Admin"){
        //         Route::get('/',[SignatureController::class, 'admin']);
        //     }else{
        //         Route::get('/',[SignatureController::class, 'user']);
        //     }
        // }
        
        Route::get('/', function () {
            // return view('signature.signature');
            if(Auth::user()->status == "Admin"){
                $data = data_file::select('data_files.id', 'data_files.file_name', 'data_files.status as status_file', 'users.name', 'users.status')
                ->join("users", "data_files.user_id", "=", "users.id")->get();

                return view("signature.signature")->with(compact("data"));
            }else{

                $data = data_file::where("user_id","=", Auth::user()->id)->get();

                return view("signature.user-home")->with(compact("data"));
            }
        });
        
        // Route::resource('signature', SignatureController::class);
        
        Route::get('generate-pdf', [SignatureController::class, 'generatePDF']);
        Route::post('/file-post', [SignatureController::class, 'store']);
        
        Route::get('/toGenerate', [SignatureController::class, 'generateKey']);
        Route::get('/toVerify', [SignatureController::class, 'verify']);
        Route::post('/verify-file',[SignatureController::class, 'getVerificationResult']);
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
        Route::get('/detail/{id}', [SignatureController::class, 'detail']);
        Route::post('/signing',[SignatureController::class, 'signing']);
        Route::get('/profile',[UserController::class, 'profile']);
    });
});

