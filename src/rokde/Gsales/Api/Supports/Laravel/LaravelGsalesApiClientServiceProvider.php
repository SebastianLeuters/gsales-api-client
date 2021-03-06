<?php namespace Rokde\Gsales\Api\Supports\Laravel;

use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Rokde\Gsales\Api\Client;

/**
 * Class LaravelGsalesApiClientServiceProvider
 *
 * The Laravel Service Provider
 *
 * @package Rokde\Gsales\Api\Supports\Laravel
 */
class LaravelGsalesApiClientServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * booting the package
	 */
	public function boot()
	{
		$this->package('rokde/gsales-api-client', 'gsales-api-client', __DIR__);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('gsales-api-client', function () {
			$wsdl = Config::get('gsales-api-client::gsales.wsdl');
			$apikey = Config::get('gsales-api-client::gsales.apikey');

			return new Client($wsdl, $apikey);
		});

		$this->registerFacade();
	}

	/**
	 * Register the Notify:: facade
	 */
	private function registerFacade()
	{
		$this->app->booting(function()
		{
			$loader = AliasLoader::getInstance();
			$loader->alias('GsalesApiClient', 'Rokde\Gsales\Api\Supports\Laravel\GsalesApiClientFacade');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('gsales-api-client');
	}
}