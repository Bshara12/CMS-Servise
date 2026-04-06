<?php

namespace App\Domains\CMS\Actions\Rate;

use App\Domains\CMS\DTOs\Rate\RateDTO;
use App\Domains\CMS\Repositories\Interface\DataEntryRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\RatingRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RateAction
{
  public function __construct(
    private RatingRepositoryInterface $ratings,
    private ProjectRepositoryInterface $projects,
    private DataEntryRepositoryInterface $dataEntries
  ) {}

  public function execute(RateDTO $dto)
  {
    DB::beginTransaction();

    try {
      $existing = $this->ratings->findUserRating(
        $dto->userId,
        $dto->rateableType,
        $dto->rateableId
      );

      if ($existing) {
        $this->ratings->update($existing, $dto);
      } else {
        $this->ratings->create($dto);
      }

      $this->updateStats($dto);

      DB::commit();

      return true;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  private function updateStats(RateDTO $dto)
  {
    $stats = $this->ratings->getStats(
      $dto->rateableType,
      $dto->rateableId
    );

    $data = [
      'ratings_count' => $stats->count,
      'ratings_avg' => round($stats->avg, 2)
    ];

    // match ($dto->rateableType) {
    //   'project' => $this->projects->update($dto->rateableId, $data),
    //   'data' => $this->dataEntries->update($dto->rateableId, $data),
    // };
    match ($dto->rateableType) {
      'project' => $this->projects->update(
        $this->projects->findById($dto->rateableId),
        $data
      ),

      'data' => $this->dataEntries->updateRatingStats(
        $dto->rateableId,
        $data
      ),
    };
  }
}
