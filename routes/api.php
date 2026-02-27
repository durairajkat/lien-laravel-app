<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RemedyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\V1\AiFileReaderController;
use App\Http\Controllers\Api\V1\DocumentApiController;
use App\Http\Controllers\Api\V1\master\ContactRoleController;
use App\Http\Controllers\Api\V1\master\CountryApiController;
use App\Http\Controllers\Api\V1\master\CountyApiCountroller;
use App\Http\Controllers\Api\V1\master\ProjectRoleApiController;
use App\Http\Controllers\Api\V1\master\ProjectTypeApiController;
use App\Http\Controllers\Api\V1\master\StateApiController;
use App\Http\Controllers\Api\V1\master\TaskActionController;
use App\Http\Controllers\Api\V1\Profile\ProfileController;
use App\Http\Controllers\Api\V1\Project\CustomerContactController;
use App\Http\Controllers\Api\V1\Project\DeadlineApiController;
use App\Http\Controllers\Api\V1\Project\ProjectApiController;
use App\Http\Controllers\Api\V1\Project\ProjectContactController;
use App\Http\Controllers\Api\V1\SubUserController;
use App\Http\Controllers\Api\V1\Tasks\TaskController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ExcelUploadController;

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

Route::post('registration', [UserController::class, 'postRegistrationAPI'])->name('post.register');
Route::get('remedy', [RemedyController::class, 'getRemedies']);
Route::get('slide-chart/{state}/{project_type_id}', [ProjectController::class, 'getSlideChart']);

Route::post('login', [AuthController::class, 'login'])->name('api.login');
Route::post('signup', [RegisterController::class, 'signup'])->name('api.signup');
Route::post('/forgot-password', [PasswordController::class, 'forgot']);
Route::post('/reset-password', [PasswordController::class, 'reset']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    //profile routes
    Route::post('update/user-profile', [ProfileController::class, 'updateProfile'])->name('api.update.profile');
    Route::get('user-profile', [ProfileController::class, 'getProfile'])->name('api.get.profile');
    Route::post('update-profile-image', [ProfileController::class, 'updateProfileImage']);
    Route::post('profile/update-password', [ProfileController::class, 'updatePassword']);
    //sub user routes
    Route::get('sub-users/datatable', [SubUserController::class, 'datatable']);
    Route::get('sub-users/{sub_user}', [SubUserController::class, 'view']);
    Route::apiResource('sub-users', SubUserController::class)
        ->only(['store', 'update', 'destroy']);
    Route::get('sub-users-all', [SubUserController::class, 'getAllSubUser']);

    Route::post('ai/parse-document', [AiFileReaderController::class, 'readDocument']);
    Route::get('ai/document-result/{fileId}', [AiFileReaderController::class, 'getResult']);

    Route::get('/countries', [CountryApiController::class, 'getCountries']);
    Route::post('/states', [StateApiController::class, 'getStates']);
    Route::post('/counties', [CountyApiCountroller::class, 'getCounties']);
    Route::get('/project-types', [ProjectTypeApiController::class, 'index']);
    Route::get('/project-roles', [ProjectRoleApiController::class, 'index']);
    Route::get('get-contact-roles', [ContactRoleController::class, 'index']);
    Route::get('task-actions',[TaskActionController::class, 'index'] );

    Route::get('/customer-contacts/{project_id}', [CustomerContactController::class, 'index']);
    Route::post('/check-project-roles-customers', [ProjectController::class, 'checkProjectRole']);
    Route::post('/remedy-dates', [DeadlineApiController::class, 'getRemedyDates']);
    Route::post('/deadline-info', [DeadlineApiController::class, 'getDeadlineInfo']);
    /** Contact */
    Route::get('/project-contacts-all', [ProjectContactController::class, 'index']);
    Route::get('/fetch-companies', [ContactController::class, 'fetchCompanies']);
    Route::post('/save-customer-contact', [ProjectContactController::class, 'saveCustomerContact']);
    Route::post('/save-project-contact', [ProjectContactController::class, 'saveProjectContact']);
    Route::post('/upload-customer-contact-excel', [ExcelUploadController::class, 'uploadCustomerContact']);

    Route::post('/save-project', [ProjectApiController::class, 'saveProject']);
    /** Projects */
    Route::get('/total-project-count', [ProjectApiController::class, 'totalCount']);
    Route::get('/get-used-states', [ProjectApiController::class, 'getUsedStates']);
    Route::get('/projects', [ProjectApiController::class, 'index']);
    Route::get("/project", [ProjectApiController::class, 'view']);
    /** Tasks */
    Route::get('/tasks/count', [TaskController::class, 'count']);
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'view']);
    /** Contact */
    Route::get('/contacts/total-count', [ContactController::class, 'totalCount']);
    Route::get('/contacts', [ContactController::class, 'index']);
    /** Documents */
    Route::get('/documents', [DocumentApiController::class, 'index']);
    Route::post('/document/delete', [DocumentApiController::class, 'deleteDocument']);
    Route::post('/documents/upload', [DocumentApiController::class, 'addDocument']);

    Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');
});
