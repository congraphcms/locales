<?php
/*
 * This file is part of the cookbook/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Locales\Handlers;

use Illuminate\Support\ServiceProvider;

use Cookbook\Locales\Handlers\Commands\Locales\LocaleCreateHandler;
use Cookbook\Locales\Handlers\Commands\Locales\LocaleUpdateHandler;
use Cookbook\Locales\Handlers\Commands\Locales\LocaleDeleteHandler;
use Cookbook\Locales\Handlers\Commands\Locales\LocaleFetchHandler;
use Cookbook\Locales\Handlers\Commands\Locales\LocaleGetHandler;

/**
 * HandlersServiceProvider service provider for handlers
 * 
 * It will register all handlers to app container
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/locales
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
			'Cookbook\Locales\Commands\Locales\LocaleCreateCommand' => 
				'Cookbook\Locales\Handlers\Commands\Locales\LocaleCreateHandler@handle',
			'Cookbook\Locales\Commands\Locales\LocaleUpdateCommand' => 
				'Cookbook\Locales\Handlers\Commands\Locales\LocaleUpdateHandler@handle',
			'Cookbook\Locales\Commands\Locales\LocaleDeleteCommand' => 
				'Cookbook\Locales\Handlers\Commands\Locales\LocaleDeleteHandler@handle',
			'Cookbook\Locales\Commands\Locales\LocaleFetchCommand' => 
				'Cookbook\Locales\Handlers\Commands\Locales\LocaleFetchHandler@handle',
			'Cookbook\Locales\Commands\Locales\LocaleGetCommand' => 
				'Cookbook\Locales\Handlers\Commands\Locales\LocaleGetHandler@handle',

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
		
		$this->app->bind('Cookbook\Locales\Handlers\Commands\Locales\LocaleCreateHandler', function($app){
			return new LocaleCreateHandler($app->make('Cookbook\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind('Cookbook\Locales\Handlers\Commands\Locales\LocaleUpdateHandler', function($app){
			return new LocaleUpdateHandler($app->make('Cookbook\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind('Cookbook\Locales\Handlers\Commands\Locales\LocaleDeleteHandler', function($app){
			return new LocaleDeleteHandler($app->make('Cookbook\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind('Cookbook\Locales\Handlers\Commands\Locales\LocaleFetchHandler', function($app){
			return new LocaleFetchHandler($app->make('Cookbook\Contracts\Locales\LocaleRepositoryContract'));
		});

		$this->app->bind('Cookbook\Locales\Handlers\Commands\Locales\LocaleGetHandler', function($app){
			return new LocaleGetHandler($app->make('Cookbook\Contracts\Locales\LocaleRepositoryContract'));
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
			'Cookbook\Locales\Handlers\Commands\Locales\LocaleCreateHandler',
			'Cookbook\Locales\Handlers\Commands\Locales\LocaleUpdateHandler',
			'Cookbook\Locales\Handlers\Commands\Locales\LocaleDeleteHandler',
			'Cookbook\Locales\Handlers\Commands\Locales\LocaleFetchHandler',
			'Cookbook\Locales\Handlers\Commands\Locales\LocaleGetHandler'
		];
	}
}