<?php

namespace App\Services\Ecommerce;

use Illuminate\Support\Facades\Http;
use App\Domains\Core\Traits\HasProjectHeaders;

class EcommerceApiClient
{
  use HasProjectHeaders;

  protected string $baseUrl;

  public function __construct()
  {
    $this->baseUrl = rtrim(config('services.e_commerce.url'), '/');
  }

  public function deleteProject(int $projectId)
  {
    $response = Http::withHeaders(
      $this->projectHeaders()
    )->delete("{$this->baseUrl}/api/ecommerce/", $projectId);

  }
}
