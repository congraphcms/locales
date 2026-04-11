<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales\Commands;

use Congraph\Contracts\Locales\LocaleRepositoryContract;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

use Congraph\Locales\Commands\Locales\LocaleCreateCommand;
use Congraph\Locales\Commands\Locales\LocaleUpdateCommand;
use Congraph\Locales\Commands\Locales\LocaleDeleteCommand;
use Congraph\Locales\Commands\Locales\LocaleFetchCommand;
use Congraph\Locales\Commands\Locales\LocaleGetCommand;

/**
 * CommandsServiceProvider service provider for commands
 * 
 * It will register all commands to app container
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class CommandsServiceProvider extends ServiceProvider implements DeferrableProvider {


	/**
	* Register
	* 
	* @return void
	*/
	public function register() {
		$this->registerCommands();
	}

	/**
	* Register Command Handlers
	*
	* @return void
	*/
	public function registerCommands() {
		$this->app->bind(LocaleCreateCommand::class, function($app){
			return new LocaleCreateCommand($app->make(LocaleRepositoryContract::class));
		});

		$this->app->bind(LocaleUpdateCommand::class, function($app){
			return new LocaleUpdateCommand($app->make(LocaleRepositoryContract::class));
		});

		$this->app->bind(LocaleDeleteCommand::class, function($app){
			return new LocaleDeleteCommand($app->make(LocaleRepositoryContract::class));
		});

		$this->app->bind(LocaleFetchCommand::class, function($app){
			return new LocaleFetchCommand($app->make(LocaleRepositoryContract::class));
		});

		$this->app->bind(LocaleGetCommand::class, function($app){
			return new LocaleGetCommand($app->make(LocaleRepositoryContract::class));
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
			LocaleCreateCommand::class,
			LocaleUpdateCommand::class,
			LocaleDeleteCommand::class,
			LocaleFetchCommand::class,
			LocaleGetCommand::class,
		];
	}
}
