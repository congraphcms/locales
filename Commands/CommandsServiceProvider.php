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
class CommandsServiceProvider extends ServiceProvider {

	/**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
	protected $defer = true;


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
			return new LocaleCreateCommand($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind(LocaleUpdateCommand::class, function($app){
			return new LocaleUpdateCommand($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind(LocaleDeleteCommand::class, function($app){
			return new LocaleDeleteCommand($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind(LocaleFetchCommand::class, function($app){
			return new LocaleFetchCommand($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind(LocaleGetCommand::class, function($app){
			return new LocaleGetCommand($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
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
			'Congraph\Locales\Commands\Locales\LocaleCreateCommand',
			'Congraph\Locales\Commands\Locales\LocaleUpdateCommand',
			'Congraph\Locales\Commands\Locales\LocaleDeleteCommand',
			'Congraph\Locales\Commands\Locales\LocaleFetchCommand',
			'Congraph\Locales\Commands\Locales\LocaleGetCommand'
		];
	}
}