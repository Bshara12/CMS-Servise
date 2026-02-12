<?php

use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\DataEntryPublishController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');


Route::prefix('projects')->group(function () {
  Route::post('/', [ProjectController::class, 'store']);
});


// test
Route::middleware('resolve.project')->get('/tenant-test', function () {
  return response()->json([
    'project_id' => app('currentProject')->id,
    'project_name' => app('currentProject')->name,
  ]);
});


Route::middleware('resolve.project')->group(function () {
  // CMS routes لاحقًا
  Route::post('/projects/{project}', [ProjectController::class, 'update']);
  Route::get('/projects/{project}', [ProjectController::class, 'show']);
  Route::get('/projects', [ProjectController::class, 'index']);
  Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
});
Route::post(
  '/projects/{project}/data-types/{dataType}/entries',
  [DataEntryController::class, 'store']
);

Route::post(
  '/entries/{entry}/publish',
  DataEntryPublishController::class
);

Route::middleware('auth:sanctum')->group(function () {});
Route::post('/data-entries/{id}', [DataEntryController::class, 'update']);
