<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales\Validators;

use Illuminate\Support\ServiceProvider;

use Congraph\Locales\Validators\Locales\LocaleCreateValidator;
use Congraph\Locales\Validators\Locales\LocaleUpdateValidator;
use Congraph\Locales\Validators\Locales\LocaleDeleteValidator;
use Congraph\Locales\Validators\Locales\LocaleFetchValidator;
use Congraph\Locales\Validators\Locales\LocaleGetValidator;

/**
 * ValidatorsServiceProvider service provider for validators
 * 
 * It will register all validators to app container
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class ValidatorsServiceProvider extends ServiceProvider {

	/**
	 * Boot
	 * 
	 * @return void
	 */
	public function boot() {
		$this->mapValidators();
	}


	/**
	 * Register
	 * 
	 * @return void
	 */
	public function register() {
		$this->registerValidators();
	}

	/**
	 * Maps Validators
	 *
	 * @return void
	 */
	public function mapValidators() {
		
		$mappings = [
			// Locales
			'Congraph\Locales\Commands\Locales\LocaleCreateCommand' => 
				'Congraph\Locales\Validators\Locales\LocaleCreateValidator@validate',
			'Congraph\Locales\Commands\Locales\LocaleUpdateCommand' => 
				'Congraph\Locales\Validators\Locales\LocaleUpdateValidator@validate',
			'Congraph\Locales\Commands\Locales\LocaleDeleteCommand' => 
				'Congraph\Locales\Validators\Locales\LocaleDeleteValidator@validate',
			'Congraph\Locales\Commands\Locales\LocaleFetchCommand' => 
				'Congraph\Locales\Validators\Locales\LocaleFetchValidator@validate',
			'Congraph\Locales\Commands\Locales\LocaleGetCommand' => 
				'Congraph\Locales\Validators\Locales\LocaleGetValidator@validate',
		];

		$this->app->make('Illuminate\Contracts\Bus\Dispatcher')->mapValidators($mappings);
	}

	/**
	 * Registers Command Handlers
	 *
	 * @return void
	 */
	public function registerValidators() {

		// Locales
		$this->app->bind('Congraph\Locales\Validators\Locales\LocaleCreateValidator', function($app){
			return new LocaleCreateValidator();
		});

		$this->app->bind('Congraph\Locales\Validators\Locales\LocaleUpdateValidator', function($app){
			return new LocaleUpdateValidator($app['Congraph\Contracts\Locales\LocaleRepositoryContract']);
		});

		$this->app->bind('Congraph\Locales\Validators\Locales\LocaleDeleteValidator', function($app){
			return new LocaleDeleteValidator($app['Congraph\Contracts\Locales\LocaleRepositoryContract']);
		});

		$this->app->bind('Congraph\Locales\Validators\Locales\LocaleFetchValidator', function($app){
			return new LocaleFetchValidator($app['Congraph\Contracts\Locales\LocaleRepositoryContract']);
		});

		$this->app->bind('Congraph\Locales\Validators\Locales\LocaleGetValidator', function($app){
			return new LocaleGetValidator();
		});
	}


	/**
     * Get the services provided by the provider.
     *
     * @return array
     */
	public function provides()
	{
		return [
			// Locales
			'Congraph\Locales\Validators\Locales\LocaleCreateValidator',
			'Congraph\Locales\Validators\Locales\LocaleUpdateValidator',
			'Congraph\Locales\Validators\Locales\LocaleDeleteValidator',
			'Congraph\Locales\Validators\Locales\LocaleFetchValidator',
			'Congraph\Locales\Validators\Locales\LocaleGetValidator'
		];
	}
}