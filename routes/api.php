<?php

use App\Domains\Auth\Service\AuthServiceClient;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\DataEntryPublishController;
use App\Http\Controllers\DataTypeController;
use App\Http\Controllers\EntryDetailController;
use App\Http\Controllers\EntryVersionController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Default Laravel Route
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');


/*
|--------------------------------------------------------------------------
| Project Creation (بدون resolve.project)
|--------------------------------------------------------------------------
*/

// Route::prefix('projects')->group(function () {

//   Route::post('/', [ProjectController::class, 'store']);
// })->middleware('auth.user');

Route::post('/projects', [ProjectController::class, 'store'])->middleware('auth.user');

/*
|--------------------------------------------------------------------------
| Test Routes
|--------------------------------------------------------------------------
*/

Route::middleware('resolve.project')->get('/tenant-test', function () {

  return response()->json([
    'project_id' => app('currentProject')->id,
    'project_name' => app('currentProject')->name,
  ]);
});

Route::get('/test-auth', function (AuthServiceClient $auth) {

  $token = request()->bearerToken();

  $user = $auth->getUserFromToken($token);

  return response()->json($user);
});


/*
|--------------------------------------------------------------------------
| Protected Project APIs
|--------------------------------------------------------------------------
*/

Route::middleware(['resolve.project', 'auth.user'])->group(function () {

  /*
    |--------------------------------------------------------------------------
    | Projects
    |--------------------------------------------------------------------------
    */

  Route::post('/projects/{project}', [ProjectController::class, 'update']);
  Route::get('/projects/{project}', [ProjectController::class, 'show']);
  Route::get('/projects', [ProjectController::class, 'index']);
  Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);


  /*
    |--------------------------------------------------------------------------
    | Entries
    |--------------------------------------------------------------------------
    */

  Route::get('/entries/{entry:slug}', [EntryDetailController::class, 'show']);

  Route::get('/entries/{entrySlug}/versions', [
    EntryVersionController::class,
    'index'
  ]);

  Route::delete('/entries/{entry:slug}', [
    DataEntryController::class,
    'destroy'
  ]);

  Route::get(
    '/entries/{entry:slug}/with-relations',
    [EntryDetailController::class, 'showwithrelation']
  );

  Route::get(
    '/entries/{entry:slug}/same-type',
    [EntryDetailController::class, 'showwithsametype']
  );

  Route::post(
    '/entries/{entry:slug}/publish',
    DataEntryPublishController::class
  );


  /*
    |--------------------------------------------------------------------------
    | Data Entries
    |--------------------------------------------------------------------------
    */

  Route::post(
    '/data-types/{dataType:slug}/entries',
    [DataEntryController::class, 'store']
  );

  Route::put(
    '/data-types/{dataType:slug}/entries/{entry:slug}',
    [DataEntryController::class, 'update']
  );

  Route::patch(
    '/data-types/{dataType:slug}/entries/{entry:slug}',
    [DataEntryController::class, 'update']
  );

  Route::delete(
    '/data-types/{dataType:slug}/entries/{entry:slug}',
    [DataEntryController::class, 'destroyByType']
  );

  Route::post(
    '/data-entries/versions/{version}/restore',
    [DataEntryController::class, 'restore']
  );


  /*
    |--------------------------------------------------------------------------
    | CMS
    |--------------------------------------------------------------------------
    */

  Route::prefix('cms')->group(function () {

    /*
        |--------------------------------------------------------------------------
        | Data Types
        |--------------------------------------------------------------------------
        */

    Route::get('/data-types/trashed', [
      DataTypeController::class,
      'trashed'
    ]);

    Route::post('/data-types/{id}/restore', [
      DataTypeController::class,
      'restore'
    ]);

    Route::delete('/data-types/{id}/force-delete', [
      DataTypeController::class,
      'forceDelete'
    ]);

    Route::post(
      '/data-types',
      [DataTypeController::class, 'store']
    )->middleware('permission:cms.datatype.create');

    Route::get('/data-types', [
      DataTypeController::class,
      'index'
    ]);

    Route::get('/data-types/{slug}', [
      DataTypeController::class,
      'show'
    ]);

    Route::put(
      '/data-types/{dataType}',
      [DataTypeController::class, 'update']
    )->middleware('permission:cms.datatype.update');

    Route::delete(
      '/data-types/{dataType}',
      [DataTypeController::class, 'destroy']
    )->middleware('permission:cms.datatype.delete');


    /*
        |--------------------------------------------------------------------------
        | Fields
        |--------------------------------------------------------------------------
        */

    Route::get(
      '/data-types/{dataType}/fields/trashed',
      [FieldController::class, 'trashed']
    );

    Route::post('/fields/{id}/restore', [
      FieldController::class,
      'restore'
    ]);

    Route::delete('/fields/{id}/force-delete', [
      FieldController::class,
      'forceDelete'
    ]);

    Route::post(
      '/data-types/{dataType}/fields',
      [FieldController::class, 'store']
    )->middleware('permission:cms.field.create');

    Route::get(
      '/data-types/{dataType}/fields',
      [FieldController::class, 'index']
    );

    Route::put(
      '/fields/{field}',
      [FieldController::class, 'update']
    )->middleware('permission:cms.field.update');

    Route::delete(
      '/fields/{field}',
      [FieldController::class, 'destroy']
    )->middleware('permission:cms.field.delete');
  });


  /*
    |--------------------------------------------------------------------------
    | Permission Test
    |--------------------------------------------------------------------------
    */

  Route::post(
    '/datatype',
    [DataTypeController::class, 'store']
  )->middleware('permission:cms.datatype.create');
});
