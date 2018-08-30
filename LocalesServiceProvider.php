<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales;

use Illuminate\Support\ServiceProvider;

/**
 * LocalesServiceProvider service provider for Locales package
 * 
 * It will register all dependecies to app container
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocalesServiceProvider extends ServiceProvider {

	/**
	* Register
	* 
	* @return void
	*/
	public function register() {
		// $this->mergeConfigFrom(realpath(__DIR__ . '/config/congraph.php'), 'congraph');
		$this->registerServiceProviders();
	}

	/**
	 * Boot
	 * 
	 * @return void
	 */
	public function boot() {
		$this->publishes([
			__DIR__.'/database/migrations' => database_path('/migrations'),
		]);
	}

	/**
	 * Register Service Providers for this package
	 * 
	 * @return void
	 */
	protected function registerServiceProviders(){

		// Repositories
		// -----------------------------------------------------------------------------
		$this->app->register('Congraph\Locales\Repositories\RepositoriesServiceProvider');
		
		// Handlers
		// -----------------------------------------------------------------------------
		$this->app->register('Congraph\Locales\Handlers\HandlersServiceProvider');

		// Validators
		// -----------------------------------------------------------------------------
		$this->app->register('Congraph\Locales\Validators\ValidatorsServiceProvider');

		// Commands
		// -----------------------------------------------------------------------------
		$this->app->register('Congraph\Locales\Commands\CommandsServiceProvider');

	}

}