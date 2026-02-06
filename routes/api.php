<?php

use App\Http\Controllers\CMS\DataTypeController;
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
});

Route::middleware('resolve.project')->prefix('cms')->group(function () {
  Route::get('/data-types', [DataTypeController::class, 'index']);
  Route::post('/data-types', [DataTypeController::class, 'store']);
  Route::get('/data-types/{slug}', [DataTypeController::class, 'show']);

  
  // CMS routes لاحقًا
  Route::post('/projects/{project}', [ProjectController::class, 'update']);
  Route::get('/projects/{project}', [ProjectController::class, 'show']);
  Route::get('/projects', [ProjectController::class, 'index']);
  Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
});
