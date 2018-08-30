<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales\Handlers;

use Illuminate\Support\ServiceProvider;

use Congraph\Locales\Handlers\Commands\Locales\LocaleCreateHandler;
use Congraph\Locales\Handlers\Commands\Locales\LocaleUpdateHandler;
use Congraph\Locales\Handlers\Commands\Locales\LocaleDeleteHandler;
use Congraph\Locales\Handlers\Commands\Locales\LocaleFetchHandler;
use Congraph\Locales\Handlers\Commands\Locales\LocaleGetHandler;

/**
 * HandlersServiceProvider service provider for handlers
 * 
 * It will register all handlers to app container
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class HandlersServiceProvider extends ServiceProvider {

	/**
	 * The event listener mappings for package.
	 *
	 * @var array
	 */
	protected $listen = [
	];


	/**
	 * Boot
	 * 
	 * @return void
	 */
	public function boot() {
		$this->mapCommandHandlers();
	}


	/**
	 * Register
	 * 
	 * @return void
	 */
	public function register() {
		$this->registerCommandHandlers();
	}

	/**
	 * Maps Command Handlers
	 *
	 * @return void
	 */
	public function mapCommandHandlers() {
		
		$mappings = [
			// Locales
			'Congraph\Locales\Commands\Locales\LocaleCreateCommand' => 
				'Congraph\Locales\Handlers\Commands\Locales\LocaleCreateHandler@handle',
			'Congraph\Locales\Commands\Locales\LocaleUpdateCommand' => 
				'Congraph\Locales\Handlers\Commands\Locales\LocaleUpdateHandler@handle',
			'Congraph\Locales\Commands\Locales\LocaleDeleteCommand' => 
				'Congraph\Locales\Handlers\Commands\Locales\LocaleDeleteHandler@handle',
			'Congraph\Locales\Commands\Locales\LocaleFetchCommand' => 
				'Congraph\Locales\Handlers\Commands\Locales\LocaleFetchHandler@handle',
			'Congraph\Locales\Commands\Locales\LocaleGetCommand' => 
				'Congraph\Locales\Handlers\Commands\Locales\LocaleGetHandler@handle',

		];

		$this->app->make('Illuminate\Contracts\Bus\Dispatcher')->maps($mappings);
	}

	/**
	 * Registers Command Handlers
	 *
	 * @return void
	 */
	public function registerCommandHandlers() {
		
		// Locales
		
		$this->app->bind('Congraph\Locales\Handlers\Commands\Locales\LocaleCreateHandler', function($app){
			return new LocaleCreateHandler($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind('Congraph\Locales\Handlers\Commands\Locales\LocaleUpdateHandler', function($app){
			return new LocaleUpdateHandler($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind('Congraph\Locales\Handlers\Commands\Locales\LocaleDeleteHandler', function($app){
			return new LocaleDeleteHandler($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind('Congraph\Locales\Handlers\Commands\Locales\LocaleFetchHandler', function($app){
			return new LocaleFetchHandler($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind('Congraph\Locales\Handlers\Commands\Locales\LocaleGetHandler', function($app){
			return new LocaleGetHandler($app->make('Congraph\Contracts\Locales\LocaleRepositoryContract'));
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
			'Congraph\Locales\Handlers\Commands\Locales\LocaleCreateHandler',
			'Congraph\Locales\Handlers\Commands\Locales\LocaleUpdateHandler',
			'Congraph\Locales\Handlers\Commands\Locales\LocaleDeleteHandler',
			'Congraph\Locales\Handlers\Commands\Locales\LocaleFetchHandler',
			'Congraph\Locales\Handlers\Commands\Locales\LocaleGetHandler'
		];
	}
}