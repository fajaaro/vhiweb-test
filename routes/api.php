<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });    

    Route::group(['prefix' => 'photos'], function() {
        Route::get('/', [PhotoController::class, 'index'])->name('api.photos.index');
        Route::post('/', [PhotoController::class, 'store'])->name('api.photos.store');
        Route::get('/{id}', [PhotoController::class, 'show'])->name('api.photos.show');
        Route::put('/{id}', [PhotoController::class, 'update'])->name('api.photos.update');
        Route::delete('/{id}', [PhotoController::class, 'destroy'])->name('api.photos.destroy');
        Route::post('/{id}/like', [PhotoController::class, 'like'])->name('api.photos.like');
        Route::post('/{id}/unlike', [PhotoController::class, 'unlike'])->name('api.photos.unlike');
    });
});
