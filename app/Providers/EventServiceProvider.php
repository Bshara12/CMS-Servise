<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  protected $listen = [
    \App\Events\EntryChanged::class => [
      \App\Listeners\CreateVersionListener::class,
    ],
  ];


  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
