<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{Controller,AdminController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/index',[AdminController::class,'index_template'])->name('index');
Route::get('/incident/{id}',[AdminController::class,'display_incident'])->name('display_incident');
Route::get('/generate/incident',[AdminController::class,'add_incident'])->name('genarate_incident');
Route::get('/signle/predict',[AdminController::class,'index_single_prediction'])->name('single_predict');
Route::post('/import-incidents', [AdminController::class, 'import_incidents'])->name('incidents.import');
Route::post('/predict-ticket', [AdminController::class, 'predict_category'])->name('predict_category');
Route::post('/incidents/generate-all', [AdminController::class, 'generate_bulk_prediction'])->name('incidents.generateAll');
Route::post('/incidents/search', [AdminController::class, 'deep_research'])->name('incidents.research');
Route::put('/incidents/{id}', [AdminController::class, 'incident_update'])->name('incidents.update');
Route::post('/incidents/export', [AdminController::class, 'generateAll'])->name('incidents.generateAll');

