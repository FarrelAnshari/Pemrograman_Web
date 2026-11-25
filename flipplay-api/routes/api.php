<?php

use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\UserCollectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('media', MediaController::class)->parameters(['media' => 'media']);

Route::prefix('users/{user}/collection')->group(function () {
    Route::get('/', [UserCollectionController::class, 'index']);
    Route::post('/', [UserCollectionController::class, 'store']);
    Route::patch('/{media}', [UserCollectionController::class, 'update']);
    Route::delete('/{media}', [UserCollectionController::class, 'destroy']);
});
