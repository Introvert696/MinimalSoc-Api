<?php

use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\Group_postController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\Messages_groupController;
use App\Http\Controllers\Api\Subscribe_to_groupController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Models\Group;
use App\Models\Group_post;
use App\Models\Message;
use App\Models\Messages_group;
use App\Models\Subscribe_to_group;
use Database\Factories\FriendFactory;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/userprofile', [AuthController::class, 'userProfile']);
    Route::get('/image/{image}', [ImageController::class, 'show']);
    Route::get('/getuser/{prompt}', [UserController::class, 'getByNameLastname']);
    Route::get('/getgroup/{prompt}', [GroupController::class, 'search']);
});
Route::group([
    'middleware' => 'auth:api'
], function ($router) {
    Route::apiResources([
        'posts' => PostController::class,
        'documents' => DocumentController::class,
        'users' => UserController::class,
        'friends' => FriendController::class,
        'group_posts' => Group_postController::class,
        'groups' => GroupController::class,
        'messages' => MessageController::class,
        'messages_groups' => Messages_groupController::class,
        'subscribe_to_groups' => Subscribe_to_groupController::class
    ]);
});
