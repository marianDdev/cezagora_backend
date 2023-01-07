<?php

use App\Events\MessageEvent;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConnectionRequestController;
use App\Http\Controllers\CosmeticsEventController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\FollowingController;
use App\Http\Controllers\ListsController;
use App\Http\Controllers\NetworkingController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductsCategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
use Illuminate\Http\Request;
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
            Route::get('/organization_type/{organizationId}', [OrganizationController::class, 'getDataByOrganizationId'])->name('get.other_organization_data');
            Route::patch('/details', [OrganizationController::class, 'update'])->name('organization.update_details');
            Route::post('/avatar/upload', [OrganizationController::class, 'uploadProfilePicture'])->name('organization.upload_avatar');
            Route::get('/avatar', [OrganizationController::class, 'getProfilePictureUrl'])->name('organization.get_avatar');
            Route::get('/avatar/{organizationId}', [OrganizationController::class, 'getOtherProfilePictureUrl'])->name('organization.get_avatar');
            Route::post('/background/upload', [OrganizationController::class, 'uploadBackgroundPicture'])->name('organization.upload_background');
            Route::get('/background', [OrganizationController::class, 'getBackgroundPictureUrl'])->name('organization.get_background');
            Route::get('/background/{organizationId}', [OrganizationController::class, 'getOtherBackgroundPictureUrl'])->name('organization.get_other_background');

            Route::group(
                ['prefix' => '/lists'],
                function () {
                    Route::get('/', [ListsController::class, 'listAll'])->name('organization.get_lists');
                    Route::post('/upload', [ListsController::class, 'upload'])->name('organization.upload_file');
                    Route::delete('/{uuid}', [ListsController::class, 'delete'])->name('organization.delete_file');
                }
            );
        }
    );

    //search
    Route::group(
        ['prefix' => '/search'],
        function () {
            Route::get('/all', [SearchController::class, 'searchAll'])->name('search.all');
            Route::get('/companies', [SearchController::class, 'searchByType'])->name('search.companies');
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

    //auth user data
    Route::get('/user_data', [UserController::class, 'getAutUserData'])->name('auth_user.data');

    //chat
    Route::get('/my_threads', [ChatController::class, 'getMyThreads'])->name('chat.list_messages');
    Route::post('/messages', [ChatController::class, 'sendMessage'])->name('chat.post_message');
    Route::get('/thread_messages/{otherOrganizationId}', [ChatController::class, 'getMessagesByOtherOrganizationId'])->name('chat.thread_messages');

    //posts
    Route::group(
        ['prefix' => '/posts'],
        function () {
            Route::get('/{id}', [PostController::class, 'getOne'])->name('posts.get_one');
            Route::get('/', [PostController::class, 'list'])->name('posts.list');
            Route::get('/my/posts', [PostController::class, 'myPosts'])->name('posts.my_posts_list');
            Route::get('/otherCompany/{organizationId}', [PostController::class, 'postsByOrgId'])->name('posts.other_company');
            Route::post('/', [PostController::class, 'create'])->name('post.create');
            Route::patch('/{id}', [PostController::class, 'update'])->name('post.update');
            Route::delete('/{id}', [PostController::class, 'delete'])->name('post.delete');
        }
    );

    Route::group(
        ['prefix' => '/comments'],
        function () {
            Route::get('/{id}', [CommentController::class, 'getOne'])->name('comments.get_one');
            Route::get('/{postId}/list', [CommentController::class, 'list'])->name('comments.list');
            Route::post('/', [CommentController::class, 'create'])->name('comments.create');
            Route::patch('/{id}', [CommentController::class, 'update'])->name('comments.update');
            Route::delete('/{id}', [CommentController::class, 'delete'])->name('comments.delete');
        }
    );

    //events
    Route::group(['prefix' => '/events'],
        function () {
            Route::group(['middleware' => Admin::class],
                function () {
                    Route::post('/', [CosmeticsEventController::class, 'create'])->name('events.create');
                    Route::patch('/{id}', [CosmeticsEventController::class, 'update'])->name('events.update');
                    Route::delete('/{id}', [CosmeticsEventController::class, 'delete'])->name('events.delete');
                }
            );
            Route::get('/{id}', [CosmeticsEventController::class, 'getOne'])->name('events.get_one');
            Route::get('/', [CosmeticsEventController::class, 'list'])->name('events.list');
        }
    );
});

Route::get('/products_categories', [ProductsCategoryController::class, 'get'])->name('products_categories.get');

Route::post('/register', [AuthenticationController::class, 'register'])
     ->middleware('guest')
     ->name('register');

Route::post('/login', [AuthenticationController::class, 'login'])
     ->middleware('guest')
     ->name('login');

Route::post('/admin_login', [AuthenticationController::class, 'adminLogin'])
     ->middleware('guest')
     ->name('admin_login');

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
