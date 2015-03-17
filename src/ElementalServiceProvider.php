<?php namespace Regulus\Elemental;

use Illuminate\Support\ServiceProvider;

class ElementalServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__.'/config/html.php' => config_path('html.php'),
		]);

		$this->loadViewsFrom(__DIR__.'/views', 'elemental');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('Regulus\Elemental\Elemental', function($app)
		{
			return new Elemental($app['url']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['Regulus\Elemental\Elemental'];
	}

}