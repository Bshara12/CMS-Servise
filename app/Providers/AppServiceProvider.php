<?php

namespace App\Providers;

use App\Domains\CMS\Repositories\DataEntryRepositoryInterface;
use App\Domains\CMS\Repositories\DataTypeRepositoryInterface;
use App\Domains\CMS\Repositories\Eloquent\EloquentDataEntryRepository;
use App\Domains\CMS\Repositories\Eloquent\EloquentDataTypeRepository;
use App\Domains\CMS\Repositories\Eloquent\EloquentProjectRepository;
use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
    $this->app->bind(DataTypeRepositoryInterface::class, EloquentDataTypeRepository::class);
    $this->app->bind(DataEntryRepositoryInterface::class, EloquentDataEntryRepository::class);
    $this->app->bind(
      ProjectRepositoryInterface::class,
      EloquentProjectRepository::class
    );
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}
