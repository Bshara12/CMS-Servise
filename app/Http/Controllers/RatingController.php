<?php

namespace App\Http\Controllers;

use App\Domains\CMS\DTOs\Rate\GetRatingsDTO;
use App\Domains\CMS\DTOs\Rate\RateDTO;
use App\Domains\CMS\Requests\GetRatingsRequest;
use App\Domains\CMS\Requests\RateRequest;
use App\Domains\CMS\Services\Rate\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
  public function __construct(
    private RatingService $service
  ) {}

  public function store(RateRequest $request)
  {
    $this->service->rate(
      RateDTO::fromRequest($request)
    );

    return response()->json([
      'message' => 'Rated successfully'
    ]);
  }

  public function index(GetRatingsRequest $request)
  {
    $ratings = $this->service->getRatings(
      GetRatingsDTO::fromRequest($request)
    );

    return response()->json($ratings);
  }
}
