<?php

namespace App\Http\Controllers;

use App\Domains\CMS\Read\Services\EntryReadService;
use Illuminate\Http\Request;

class EntryDetailController extends Controller
{
  //
  public function __construct(
    private EntryReadService $service,
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
    $dateFrom = $request->query('date_from');
    $dateTo = $request->query('date_to');
    $fieldId = $request->query('field_id');
    $search = $request->query('search');

    $result = $this->service->getSameTypeFiltered(
      entryId: $id,
      lang: $lang,
      dateFrom: $dateFrom,
      dateTo: $dateTo,
      fieldId: $fieldId !== null ? (int) $fieldId : null,
      search: $search,
      all: $all,
      page: $page,
      perPage: $perPage
    );

    if (!$result) {
      return response()->json([
        'message' => 'Entry not found'
      ], 404);
    }

    return response()->json($result);
  }
    public function sameType(Request $request, $entryId)
  {
    return $this->service->getSameTypeFiltered(
      entryId: $entryId,
      lang: $request->input('lang'),
      dateFrom: $request->input('date_from'),
      dateTo: $request->input('date_to'),
      fieldId: $request->input('field_id'),
      search: $request->input('search'),
      page: $request->input('page', 1),
      perPage: $request->input('per_page', 20)
    );
  }
}
