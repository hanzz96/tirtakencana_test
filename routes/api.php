<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TableAController;
use App\Http\Controllers\Api\TableBController;
use App\Http\Controllers\Api\TableCController;
use App\Http\Controllers\Api\TableDController;

Route::prefix('table-a')->group(function () {
    Route::post('/upload-excel', [TableAController::class, 'uploadExcel']);
    Route::get('/export-excel', [TableAController::class, 'exportExcel']);
    Route::get('/export-pdf', [TableAController::class, 'exportPdf']);
    Route::get('/', [TableAController::class, 'index']);
    Route::get('/{id}', [TableAController::class, 'show']);
    Route::post('/', [TableAController::class, 'store']);
    Route::put('/{id}', [TableAController::class, 'update']);
    Route::delete('/{id}', [TableAController::class, 'destroy']);
});

Route::prefix('table-b')->group(function () {
    Route::post('/upload-excel', [TableBController::class, 'uploadExcel']);
    Route::get('/export-excel', [TableBController::class, 'exportExcel']);
    Route::get('/export-pdf', [TableBController::class, 'exportPdf']);
    Route::get('/', [TableBController::class, 'index']);
    Route::get('/{id}', [TableBController::class, 'show']);
    Route::post('/', [TableBController::class, 'store']);
    Route::put('/{id}', [TableBController::class, 'update']);
    Route::delete('/{id}', [TableBController::class, 'destroy']);
});

Route::prefix('table-c')->group(function () {
    Route::post('/upload-excel', [TableCController::class, 'uploadExcel']);
    Route::get('/export-excel', [TableCController::class, 'exportExcel']);
    Route::get('/export-pdf', [TableCController::class, 'exportPdf']);
    Route::get('/', [TableCController::class, 'index']);
    Route::get('/{id}', [TableCController::class, 'show']);
    Route::post('/', [TableCController::class, 'store']);
    Route::put('/{id}', [TableCController::class, 'update']);
    Route::delete('/{id}', [TableCController::class, 'destroy']);
});

Route::prefix('table-d')->group(function () {
    Route::post('/upload-excel', [TableDController::class, 'uploadExcel']);
    Route::get('/export-excel', [TableDController::class, 'exportExcel']);
    Route::get('/export-pdf', [TableDController::class, 'exportPdf']);
    Route::get('/', [TableDController::class, 'index']);
    Route::get('/{id}', [TableDController::class, 'show']);
    Route::post('/', [TableDController::class, 'store']);
    Route::put('/{id}', [TableDController::class, 'update']);
    Route::delete('/{id}', [TableDController::class, 'destroy']);
});
