<?php

namespace App\Providers;

use App\Domains\CMS\Read\Repositories\EntryReadRepository;
use App\Domains\CMS\Read\Repositories\EntryReadRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\DataEntryVersionRepository;
use App\Domains\CMS\Repositories\Interface\DataEntryRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Domains\CMS\Repositories\Eloquent\EloquentDataEntryRepository;
use App\Domains\CMS\Repositories\Eloquent\EloquentDataEntryValueRepository;
use App\Domains\CMS\Repositories\Eloquent\EloquentDataEntryVersionRepository;
use App\Domains\CMS\Repositories\Eloquent\EloquentProjectRepository;
use App\Domains\CMS\Repositories\Eloquent\EloquentSeoEntryRepository;
use App\Domains\CMS\Repositories\Interface\DataEntryValueRepository;
use App\Domains\CMS\Repositories\Eloquent\DataTypeRepositoryEloquent;
use App\Domains\CMS\Repositories\Eloquent\EloquentDataEntryRelationRepository;
use App\Domains\CMS\Repositories\Eloquent\FieldRepositoryEloquent;
use App\Domains\CMS\Repositories\Interface\DataEntryRelationRepository;
use App\Domains\CMS\Repositories\Interface\FieldRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\SeoEntryRepository;
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
    $this->app->bind(
      ProjectRepositoryInterface::class,
      EloquentProjectRepository::class
    );
    $this->app->bind(
      DataEntryRepositoryInterface::class,
      EloquentDataEntryRepository::class
    );

    $this->app->bind(
      DataEntryValueRepository::class,
      EloquentDataEntryValueRepository::class
    );

    $this->app->bind(
      SeoEntryRepository::class,
      EloquentSeoEntryRepository::class
    );
    $this->app->bind(
      DataEntryRepositoryInterface::class,
      EloquentDataEntryRepository::class
    );

    $this->app->bind(
      DataEntryVersionRepository::class,
      EloquentDataEntryVersionRepository::class
    );
    $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
    $this->app->bind(
      DataEntryRelationRepository::class,
      EloquentDataEntryRelationRepository::class
    );
      $this->app->bind(
      EntryReadRepositoryInterface::class,
      EntryReadRepository::class
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
