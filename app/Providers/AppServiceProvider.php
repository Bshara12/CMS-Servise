<?php

namespace App\Providers;

use App\Domains\CMS\Repositories\DataEntryRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Domains\CMS\Repositories\Eloquent\DataTypeRepositoryEloquent;
use App\Domains\CMS\Repositories\Eloquent\EloquentDataEntryRepository;
use App\Domains\CMS\Repositories\Eloquent\EloquentProjectRepository;
use App\Domains\CMS\Repositories\Eloquent\FieldRepositoryEloquent;
use App\Domains\CMS\Repositories\Interface\FieldRepositoryInterface;
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
    $this->app->bind(DataTypeRepositoryInterface::class, DataTypeRepositoryEloquent::class);
    $this->app->bind(FieldRepositoryInterface::class, FieldRepositoryEloquent::class);
    $this->app->bind(DataEntryRepositoryInterface::class, EloquentDataEntryRepository::class);
    $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}
