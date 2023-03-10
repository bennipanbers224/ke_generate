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

Route::get('/', function () {
    return view('signature.signature');
});


Route::resource('signature', SignatureController::class);

Route::get('generate-pdf', [SignatureController::class, 'generatePDF']);

Route::get('/toGenerate', [SignatureController::class, 'generateKey']);
Route::get('/toVerify', [SignatureController::class, 'verify']);
Route::post('/verify-file',[SignatureController::class, 'getVerificationResult']);

