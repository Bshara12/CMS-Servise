<?php

namespace App\Domains\CMS\Read\Services;

use App\Domains\CMS\Read\Actions\GetEntriesBySameTypeAction;
use App\Domains\CMS\Read\Actions\GetEntryDetailAction;
use App\Domains\CMS\Read\Actions\GetEntryWithRelationsAction;

class EntryReadService
{
  public function __construct(
    private GetEntryDetailAction $getEntryDetailAction,
    private GetEntryWithRelationsAction $getEntryWithRelationsAction,
    private GetEntriesBySameTypeAction $getEntriesBySameTypeAction

  ) {}

  public function getDetail(int $entryId, ?string $lang)
  {
    return $this->getEntryDetailAction->execute($entryId, $lang);
  }
  public function getWithRelations(int $entryId, ?string $lang)
  {
    return $this->getEntryWithRelationsAction->execute($entryId, $lang);
  }
  // public function getSameType(int $entryId, ?string $lang)
  // {
  //   return $this->getEntriesBySameTypeAction->execute($entryId, $lang);
  // }
  public function getSameType(
    int $entryId,
    ?string $lang,
    int $page = 1,
    int $perPage = 20,
    bool $all = false
  ) {
    return $this->getEntriesBySameTypeAction
      ->execute($entryId, $lang, $page, $perPage, $all);
  }
}
