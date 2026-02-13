<?php

use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\DataEntryPublishController;
use App\Http\Controllers\DataTypeController;
use App\Http\Controllers\FieldController;
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


Route::middleware('resolve.project')->prefix('cms')->group(function () {

  // -------------------------
  // Data Types
  // -------------------------
  // static
  Route::get('/data-types/trashed', [DataTypeController::class, 'trashed']);
  Route::post('/data-types/{id}/restore', [DataTypeController::class, 'restore']);
  Route::delete('/data-types/{id}/force-delete', [DataTypeController::class, 'forceDelete']);

  // CRUD
  Route::post('/data-types', [DataTypeController::class, 'store']);
  Route::get('/data-types', [DataTypeController::class, 'index']);
  Route::get('/data-types/{slug}', [DataTypeController::class, 'show']);
  Route::put('/data-types/{dataType}', [DataTypeController::class, 'update']);
  Route::delete('/data-types/{dataType}', [DataTypeController::class, 'destroy']);


  // -------------------------
  // Fields
  // -------------------------
  // static
  Route::get('/data-types/{dataType}/fields/trashed', [FieldController::class, 'trashed']);
  Route::post('/fields/{id}/restore', [FieldController::class, 'restore']);
  Route::delete('/fields/{id}/force-delete', [FieldController::class, 'forceDelete']);

  // CRUD
  Route::post('/data-types/{dataType}/fields', [FieldController::class, 'store']);
  Route::get('/data-types/{dataType}/fields', [FieldController::class, 'index']);
  Route::put('/fields/{field}', [FieldController::class, 'update']);
  Route::delete('/fields/{field}', [FieldController::class, 'destroy']);
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


Route::post(
  '/data-entries/versions/{version}/restore',
  [DataEntryController::class, 'restore']
);
