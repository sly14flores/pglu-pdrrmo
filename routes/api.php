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
use App\Http\Controllers\api\IncidentTypeController;
use App\Http\Controllers\api\AgencyController;
use App\Http\Controllers\api\FacilityController;
use App\Http\Controllers\api\IncidentController;
use App\Http\Controllers\api\AssistanceTypeController;
use App\Http\Controllers\api\InterventionController;
use App\Http\Controllers\api\ComplaintController;

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

    /**
     * Incidents
     */
    Route::apiResources([
        'incidents' => IncidentController::class,
    ],[
        'only' => ['index']
    ]);
    Route::apiResources([
        'incident' => IncidentController::class,
    ],[
        'except' => ['index']
    ]);
    Route::delete('incidents', [IncidentController::class, 'batchDelete']);


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
         * Incident Types
         */
        Route::apiResources([
            'incidenttypes' => IncidentTypeController::class,
        ],[
            'only' => ['index']
        ]);
        Route::apiResources([
            'incidenttype' => IncidentTypeController::class,
        ],[
            'except' => ['index']
        ]);
        Route::delete('incidenttypes', [IncidentTypeController::class, 'batchDelete']);

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

        /**
         * Assistance Types
         */
        Route::apiResources([
            'assistancetypes' => AssistanceTypeController::class,
        ],[
            'only' => ['index']
        ]);
        Route::apiResources([
            'assistancetype' => AssistanceTypeController::class,
        ],[
            'except' => ['index']
        ]);
        Route::delete('assistancetypes', [AssistanceTypeController::class, 'batchDelete']);

        /**
         * Interventions
         */
        Route::apiResources([
            'interventions' => InterventionController::class,
        ],[
            'only' => ['index']
        ]);
        Route::apiResources([
            'intervention' => InterventionController::class,
        ],[
            'except' => ['index']
        ]);
        Route::delete('interventions', [InterventionController::class, 'batchDelete']);

        /**
         * Complaints
         */
        Route::apiResources([
            'complaints' => ComplaintController::class,
        ],[
            'only' => ['index']
        ]);
        Route::apiResources([
            'complaint' => ComplaintController::class,
        ],[
            'except' => ['index']
        ]);
        Route::delete('complaints', [ComplaintController::class, 'batchDelete']);

    });

    /**
     * Selections
     */
    Route::prefix('selections')->group(function() {

        Route::get('communication-modes', [SelectionsController::class, 'communicationModes']);
        Route::get('response-types', [SelectionsController::class, 'responseTypes']);
        Route::get('incident-types', [SelectionsController::class, 'incidentTypes']);
        Route::get('groups', [SelectionsController::class, 'groups']);
        Route::get('users', [SelectionsController::class, 'users']);
        Route::get('agencies', [SelectionsController::class, 'agencies']);
        Route::get('facilities', [SelectionsController::class, 'facilities']);
        Route::get('vehicles', [SelectionsController::class, 'vehicles']);
        Route::get('interventions', [SelectionsController::class, 'interventions']);
        Route::get('complaints', [SelectionsController::class, 'complaints']);

    });

});