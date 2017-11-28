<?php

namespace Priblo\LaravelHasTags;

use Illuminate\Support\ServiceProvider;
use Priblo\LaravelHasTags\Models\Tag;
use Priblo\LaravelHasTags\Models\Taggable;
use Priblo\LaravelHasTags\Repositories\EloquentHasTagsRepository;
use Priblo\LaravelHasTags\Repositories\Interfaces\HasTagsRepositoryInterface;

class LaravelServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap the application events.
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__.'/../../migrations/' => database_path('migrations')
		], 'migrations');
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->singleton(HasTagsRepositoryInterface::class, function () {
            $baseRepo = new EloquentHasTagsRepository(new Tag, new Taggable);
            return $baseRepo;
        });
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Illuminate\Support\ServiceProvider::provides()
	 */
	public function provides()
	{
	}
}
