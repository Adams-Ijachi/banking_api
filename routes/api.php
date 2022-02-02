<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers

use App\Http\Controllers\User\{
    UserAuthController
};
use App\Http\Controllers\Account\{
    AccountController
};

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'v1','middleware'=>['cors','json.response','apiKey_exist']], function() {

    // User Auth
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);


    // Authenticated Routes
    Route::group(['middleware'=>['auth:user']], function(){
        Route::post('logout', [UserAuthController::class, 'logout']);

        // Account Routes
        Route::post('createAccount', [AccountController::class, 'createAccount']);

    });
});
