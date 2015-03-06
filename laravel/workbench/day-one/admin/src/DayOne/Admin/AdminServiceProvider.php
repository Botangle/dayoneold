<?php namespace DayOne\Admin;

use DayOne\Admin\Config\Factory as ConfigFactory;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator as LValidator;

class AdminServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot()
	{
		$this->package('dayone/admin');
		include __DIR__.'/../../routes.php';

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//set up the shared instances
		$this->app['admin_config_factory'] = $this->app->share(function($app)
		{
			return new ConfigFactory($app->make('admin_validator'), LValidator::make(array(), array()), Config::get('administrator::administrator'));
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('admin_config_factory');
	}

}
