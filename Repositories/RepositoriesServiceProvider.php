<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales\Repositories;

use Illuminate\Support\ServiceProvider;

/**
 * RepositoriesServiceProvider service provider for repositories
 * 
 * It will register all repositories to app container
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class RepositoriesServiceProvider extends ServiceProvider {

	/**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
	protected $defer = true;

	/**
	 * Boot
	 * @return void
	 */
	public function boot()
	{
		$this->mapObjectResolvers();
	}
	
	/**
	 * Register
	 * 
	 * @return void
	 */
	public function register()
	{
		$this->registerRepositories();
	}

	/**
	 * Register repositories to Container
	 *
	 * @return void
	 */
	protected function registerRepositories()
	{
		$this->app->singleton('Congraph\Locales\Repositories\LocaleRepository', function($app) {
			return new LocaleRepository(
				$app['db']->connection()
			);
		});

		$this->app->alias(
			'Congraph\Locales\Repositories\LocaleRepository', 'Congraph\Contracts\Locales\LocaleRepositoryContract'
		);
	}

	/**
	 * Map repositories to object resolver
	 *
	 * @return void
	 */
	protected function mapObjectResolvers()
	{
		$mappings = [
			'locale' => 'Congraph\Locales\Repositories\LocaleRepository'
		];

		$this->app->make('Congraph\Contracts\Core\ObjectResolverContract')->maps($mappings);
	}
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'Congraph\Locales\Repositories\LocaleRepository',
			'Congraph\Contracts\Locales\LocaleRepositoryContract'
		];
	}


}