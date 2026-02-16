<?php

namespace App\Domains\CMS\Actions\data;

use App\Domains\CMS\Repositories\Interface\SeoEntryRepository;
use App\Domains\CMS\Services\SeoGeneratorService;

class HandleSeoAction
{
  public function __construct(
    private SeoEntryRepository $seo,
    private SeoGeneratorService $seoGenerator
  ) {}

  public function execute(int $entryId, ?array $seo, array $values): void
  {
    // حذف القديم أولاً
    $this->seo->deleteForEntry($entryId);

    // إدخال الجديد
    if ($seo) {
      $this->seo->insertForEntry($entryId, $seo);
    } else {
      $generated = $this->seoGenerator->generate($values);
      $this->seo->insertForEntry($entryId, $generated);
    }
  }
}
