<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User and Transaction API Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('user')->group(function(){
    Route::get('/', [UserController::class,'index']);
    Route::get('/show/{id}', [UserController::class,'show']);
    Route::post('/register', [UserController::class,'store']);
    Route::put('/update/{id}', [UserController::class,'update']);
    Route::delete('/delete/{id}', [UserController::class,'destroy']);
    /**
     * Transaction routes endpoints
     */
    Route::prefix('/transaction')->group(function () {
        Route::get('/balance', [UserController::class,'getBalance']);
        Route::post('/transfer', [TransactionController::class,'transfer']);
    });
});
