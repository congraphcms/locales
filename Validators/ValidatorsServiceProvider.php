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

use Congraph\Locales\Commands\Locales\LocaleCreateCommand;
use Congraph\Locales\Commands\Locales\LocaleDeleteCommand;
use Congraph\Locales\Commands\Locales\LocaleFetchCommand;
use Congraph\Locales\Commands\Locales\LocaleGetCommand;
use Congraph\Locales\Commands\Locales\LocaleUpdateCommand;
use Illuminate\Support\ServiceProvider;

use Congraph\Locales\Validators\Locales\LocaleCreateValidator;
use Congraph\Locales\Validators\Locales\LocaleUpdateValidator;
use Congraph\Locales\Validators\Locales\LocaleDeleteValidator;
use Congraph\Locales\Validators\Locales\LocaleFetchValidator;
use Congraph\Locales\Validators\Locales\LocaleGetValidator;
use Illuminate\Contracts\Bus\Dispatcher;
use Congraph\Contracts\Locales\LocaleRepositoryContract;

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
			LocaleCreateCommand::class => LocaleCreateValidator::class . '@validate',
			LocaleUpdateCommand::class => LocaleUpdateValidator::class . '@validate',
			LocaleDeleteCommand::class => LocaleDeleteValidator::class . '@validate',
			LocaleFetchCommand::class => LocaleFetchValidator::class . '@validate',
			LocaleGetCommand::class => LocaleGetValidator::class . '@validate',
		];

		$this->app->make(Dispatcher::class)->mapValidators($mappings);
	}

	/**
	 * Registers Command Handlers
	 *
	 * @return void
	 */
	public function registerValidators() {

		// Locales
		$this->app->bind(LocaleCreateValidator::class, function($app){
			return new LocaleCreateValidator();
		});

		$this->app->bind(LocaleUpdateValidator::class, function($app){
			return new LocaleUpdateValidator($app[LocaleRepositoryContract::class]);
		});

		$this->app->bind(LocaleDeleteValidator::class, function($app){
			return new LocaleDeleteValidator($app[LocaleRepositoryContract::class]);
		});

		$this->app->bind(LocaleFetchValidator::class, function($app){
			return new LocaleFetchValidator($app[LocaleRepositoryContract::class]);
		});

		$this->app->bind(LocaleGetValidator::class, function($app){
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
			LocaleCreateValidator::class,
			LocaleUpdateValidator::class,
			LocaleDeleteValidator::class,
			LocaleFetchValidator::class,
			LocaleGetValidator::class,
		];
	}
}
