<?php namespace Fbf\LaravelSolarium;

use Illuminate\Support\ServiceProvider;

class LaravelSolariumServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{

    $this->publishes([
        __DIR__.'/../../config/config.php' => config_path('laravel-solarium.php')
    ]);

    $this->loadViewsFrom(__DIR__."/../../views", 'laravel-5-solarium');

    $this->publishes([
      __DIR__.'/../../views' => base_path('resources/views/vendor/laravel-5-solarium')]);

        if (\Config::get('laravel-5-solarium.use_package_routes', true))
		{
		    include __DIR__.'/../../routes.php';
        }

		$models = \Config::get('laravel-5-solarium.models');

        if ( empty($models) || ! is_array($models) )
        {
            $models = array();
        }

        $indexer = new LaravelSolariumIndexer;

        foreach ( $models as $namespace_model => $config )
        {
            if ( class_exists($namespace_model) )
            {
                $namespace_model::observe( new LaravelSolariumModelObserver($indexer) );
            }
        }
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}