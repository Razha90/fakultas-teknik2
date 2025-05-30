<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Editor;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/save/{id}', [Editor::class, 'saveData'])->name('saveData');
    Route::get('/page/{id}', [Editor::class, 'getPage'])->name('getPage');
});
