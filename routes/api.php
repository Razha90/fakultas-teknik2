<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Editor;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/page/save/{id}', [Editor::class, 'saveData'])->name('saveData');
    Route::get('/page/{id}', [Editor::class, 'getPage'])->name('getPage');
    Route::post('/page/compile/{id}/', [Editor::class, 'saveCompile'])->name('saveCompile');
    Route::post('/upload/image', [Editor::class, 'uploadImage'])->name('upload-image');  
    
    Route::post('/news-save/{id}', [Editor::class, 'saveNews'])->name('newsData');
    Route::get('/news-get/{id}', [Editor::class, 'getNews'])->name('getNews');
    Route::post('/news-compile/{id}/', [Editor::class, 'compileNews'])->name('newsCompile');
});
