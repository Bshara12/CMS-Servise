<?php

namespace App\Http\Controllers;

use App\Domains\CMS\Read\Services\EntryReadService;
use Illuminate\Http\Request;

class EntryDetailController extends Controller
{
  //
  public function __construct(
    private EntryReadService $service
  ) {}

  public function show(Request $request, int $id)
  {
    $lang = $request->query('lang');

    $entry = $this->service->getDetail($id, $lang);

    if (!$entry) {
      return response()->json([
        'message' => 'Entry not found'
      ], 404);
    }

    return response()->json($entry);
  }

  public function showwithrelation(Request $request, int $id)
  {
    $lang = $request->query('lang');

    $entry = $this->service->getWithRelations($id, $lang);

    if (!$entry) {
      return response()->json([
        'message' => 'Entry not found'
      ], 404);
    }

    return response()->json($entry);
  }

  //   public function showwithsametype(Request $request, int $id)
  // {
  //     $lang = $request->query('lang');

  //     $result = $this->service->getSameType($id, $lang);

  //     if (!$result) {
  //         return response()->json([
  //             'message' => 'Entry not found'
  //         ], 404);
  //     }

  //     return response()->json($result);
  // }
  public function showwithsametype(Request $request, int $id)
  {
    $lang = $request->query('lang');
    $page = (int) $request->query('page', 1);
    $perPage = (int) $request->query('per_page', 20);
    $all = $request->boolean('all', false);

    $result = $this->service->getSameType(
      $id,
      $lang,
      $page,
      $perPage,
      $all
    );

    if (!$result) {
      return response()->json([
        'message' => 'Entry not found'
      ], 404);
    }

    return response()->json($result);
  }
}
