<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\UserSignUpController;
use App\Http\Controllers\api\VerificationApiController;
use App\Http\Controllers\api\ResetPasswordController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function() {

    Route::prefix('auth')->group(function() {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('logout', [LoginController::class, 'logout']);
    });

    Route::post('registration', UserSignUpController::class);

    /**
     * Email confirmation routes
     */
    Route::get('email/verify/{id}', [VerificationApiController::class, 'verify'])->name('verificationapi.verify');
    Route::get('email/resend/{id}', [VerificationApiController::class, 'resend'])->name('verificationapi.resend');

    Route::prefix('password')->group(function() {
        Route::post('link', [ResetPasswordController::class, 'sendResetLinkEmail']);
        Route::post('reset', [ResetPasswordController::class, 'resetPassword']);
    });

});