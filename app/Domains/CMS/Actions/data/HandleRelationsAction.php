<?php

namespace App\Domains\CMS\Actions\data;

use App\Domains\CMS\Repositories\Interface\DataEntryRelationRepository;

class HandleRelationsAction
{
  public function __construct(
    private DataEntryRelationRepository $relations
  ) {}

  public function execute(int $entryId, int $dataTypeId, int $projectId, ?array $relations): void
  {
    if (!$relations) {
      return;
    }

    // حذف العلاقات القديمة
    $this->relations->deleteForEntry($entryId);

    // إدخال العلاقات الجديدة
    $this->relations->insertForEntry(
      $entryId,
      $dataTypeId,
      $projectId,
      $relations
    );
  }
}
