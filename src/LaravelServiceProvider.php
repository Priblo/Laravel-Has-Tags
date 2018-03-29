<?php

namespace Priblo\LaravelHasTags;

use Illuminate\Support\ServiceProvider;
use Priblo\LaravelHasTags\Models\Tag;
use Priblo\LaravelHasTags\Models\Taggable;
use Priblo\LaravelHasTags\Repositories\Decorators\CachingHasTagsRepository;
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
			__DIR__.'/../migrations/' => database_path('migrations')
		], 'migrations');
        $this->publishes([
            __DIR__ . '/../config/has-tags.php' => config_path('has-tags.php')
        ], 'config');
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $configPath = __DIR__ . '/../config/has-tags.php';
        $this->mergeConfigFrom($configPath, 'has-tags');

        $this->app->singleton(HasTagsRepositoryInterface::class, function () {
            $baseRepo = new EloquentHasTagsRepository(new Tag, new Taggable);
            if(config('has-tags.caching_enabled') === false) {
                return $baseRepo;
            }
            return new CachingHasTagsRepository($baseRepo, $this->app['cache.store']);
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
