<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NeoStatsController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [NeoStatsController::class, 'index']);
Route::post('/fetch-neo-stats', [NeoStatsController::class, 'fetchNeoStats']);
Route::post('/clear-filters', [NeoStatsController::class, 'clearFilters']);
