<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;

/*
| API REST (Sanctum) — base pour une application mobile ou des intégrations (SI, BI…).
| Authentification : POST /api/v1/token puis header "Authorization: Bearer <token>".
*/
Route::prefix('v1')->group(function () {
    Route::post('/token', [ApiController::class, 'token'])->middleware('throttle:10,1');
    Route::get('/courses', [ApiController::class, 'courses']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [ApiController::class, 'me']);
        Route::get('/me/courses', [ApiController::class, 'myCourses']);
        Route::get('/courses/{course:slug}', [ApiController::class, 'course']);
        Route::get('/courses/{course:slug}/grades', [ApiController::class, 'grades']);
        Route::delete('/token', [ApiController::class, 'revoke']);
    });
});
