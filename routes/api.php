<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ConnectionRequestController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\FollowingController;
use App\Http\Controllers\NetworkingController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProductsCategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->group(function () {
    //organization
    Route::group(
        ['prefix' => '/organization'],
        function () {
            Route::patch('/details', [OrganizationController::class, 'update'])->name('organization.update_details');

            Route::group(
                ['prefix' => '/lists'],
                function () {
                    Route::get('/', [OrganizationController::class, 'getLists'])->name('organization.get_lists');
                    Route::post('/upload', [OrganizationController::class, 'uploadNewList'])->name('organization.upload_file');
                    Route::delete('/{uuid}', [OrganizationController::class, 'deletList'])->name('organization.delete_file');
                }
            );
        }
    );

    //search
    Route::group(
        ['prefix' => '/search'],
        function () {
            Route::get('/all', [SearchController::class, 'searchAll'])->name('search.all');
            Route::get('/companies', [SearchController::class, 'searchByCompanies'])->name('search.companies');
        }
    );

    //connections
    Route::group(
        ['prefix' => '/connections'],
        function () {
            Route::group(
                ['prefix' => '/requests'],
                function () {
                    Route::post('/{receiverId}/create', [ConnectionRequestController::class, 'create'])->name('connections.request.create');
                    Route::post('/{connectionRequestid}/accept', [ConnectionRequestController::class, 'acceptRequest'])->name('connections.request.accept');
                }
            );
        }
    );

    Route::post('/{organizationId}/follow', [FollowerController::class, 'follow'])->name('follow.create');
    Route::get('/networking/status/{organizationId}', [NetworkingController::class, 'getStatusByOrganizationId'])->name('networking_status.by_organization_id');

    Route::get('/user_data', [UserController::class, 'getAutUserData'])->name('auth_user.data');
});

Route::get('/products_categories', [ProductsCategoryController::class, 'get'])->name('products_categories.get');

Route::post('/register', [AuthenticationController::class, 'register'])
     ->middleware('guest')
     ->name('register');

Route::post('/login', [AuthenticationController::class, 'login'])
     ->middleware('guest')
     ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
     ->middleware('guest')
     ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
     ->middleware('guest')
     ->name('password.update');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
     ->middleware(['auth', 'signed', 'throttle:6,1'])
     ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
     ->middleware(['auth', 'throttle:6,1'])
     ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
     ->middleware('auth')
     ->name('logout');
