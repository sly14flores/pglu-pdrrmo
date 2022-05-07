<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\UserSignUpController;
use App\Http\Controllers\api\VerificationApiController;
use App\Http\Controllers\api\ResetPasswordController;
use App\Http\Controllers\api\ChangePasswordController;
use App\Http\Controllers\api\SelectionsController;
use App\Http\Controllers\api\AddressesController;

use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\GroupController;
use App\Http\Controllers\api\VehicleController;
use App\Http\Controllers\api\ResponseTypeController;
use App\Http\Controllers\api\CommunicationModeController;
use App\Http\Controllers\api\AgencyController;
use App\Http\Controllers\api\FacilityController;

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

    Route::prefix('address')->group(function() {

        Route::get('regions', [AddressesController::class, 'regions']);
        Route::get('provinces/{code}', [AddressesController::class, 'provinces']);
        Route::get('cities/{code}', [AddressesController::class, 'cities']);
        Route::get('barangays/{code}', [AddressesController::class, 'barangays']);

    });

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
        Route::put('change', [ChangePasswordController::class, 'update']);
    });

    /**
     * Users
     */
    Route::apiResources([
        'users' => UserController::class,
    ],[
        'only' => ['index']
    ]);
    Route::apiResources([
        'user' => UserController::class,
    ],[
        'except' => ['index']
    ]);
    Route::delete('users', [UserController::class, 'batchDelete']);

    /**
     * Groups
     */
    Route::apiResources([
        'groups' => GroupController::class,
    ],[
        'only' => ['index']
    ]);
    Route::apiResources([
        'group' => GroupController::class,
    ],[
        'except' => ['index']
    ]);
    Route::delete('groups', [GroupController::class, 'batchDelete']);

    /**
     * Vehicles
     */
    Route::apiResources([
        'vehicles' => VehicleController::class,
    ],[
        'only' => ['index']
    ]);
    Route::apiResources([
        'vehicle' => VehicleController::class,
    ],[
        'except' => ['index']
    ]);
    Route::delete('vehicles', [VehicleController::class, 'batchDelete']);


    Route::prefix('maintenance')->group(function() {

        /**
         * Response Types
         */
        Route::apiResources([
            'responsetypes' => ResponseTypeController::class,
        ],[
            'only' => ['index']
        ]);
        Route::apiResources([
            'responsetype' => ResponseTypeController::class,
        ],[
            'except' => ['index']
        ]);
        Route::delete('responsetypes', [ResponseTypeController::class, 'batchDelete']);

        /**
         * Communication Modes
         */
        Route::apiResources([
            'communicationmodes' => CommunicationModeController::class,
        ],[
            'only' => ['index']
        ]);
        Route::apiResources([
            'communicationmode' => CommunicationModeController::class,
        ],[
            'except' => ['index']
        ]);
        Route::delete('communicationmodes', [CommunicationModeController::class, 'batchDelete']);

        /**
         * Agencies
         */
        Route::apiResources([
            'agencies' => AgencyController::class,
        ],[
            'only' => ['index']
        ]);
        Route::apiResources([
            'agency' => AgencyController::class,
        ],[
            'except' => ['index']
        ]);
        Route::delete('agencies', [AgencyController::class, 'batchDelete']);

        /**
         * Facilities
         */
        Route::apiResources([
            'facilities' => FacilityController::class,
        ],[
            'only' => ['index']
        ]);
        Route::apiResources([
            'facility' => FacilityController::class,
        ],[
            'except' => ['index']
        ]);
        Route::delete('facilities', [FacilityController::class, 'batchDelete']);

    });

    /**
     * Selections
     */
    Route::prefix('selections')->group(function() {

        Route::get('communication-modes', [SelectionsController::class, 'communicationModes']);
        Route::get('response-types', [SelectionsController::class, 'responseTypes']);
        Route::get('groups', [SelectionsController::class, 'groups']);

    });

});