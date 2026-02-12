<?php

namespace App\Http\Controllers;

use App\Domains\CMS\Actions\Data\PublishDataEntryAction;
use Illuminate\Http\Request;

class DataEntryPublishController extends Controller
{
  public function __invoke(
    int $entry,
    PublishDataEntryAction $action
  ) {
    return response()->json(
      $action->execute($entry, auth()->id())
    );
  }
}
