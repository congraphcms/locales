<?php
/*
 * This file is part of the cookbook/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Locales\Handlers\Commands\Locales;


use Cookbook\Contracts\Locales\LocaleRepositoryContract;
use Cookbook\Core\Bus\RepositoryCommandHandler;
use Cookbook\Core\Bus\RepositoryCommand;

/**
 * LocaleUpdateHandler class
 * 
 * Handling command for updating locale
 * 
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleUpdateHandler extends RepositoryCommandHandler
{

	/**
	 * Create new LocaleUpdateHandler
	 * 
	 * @param Cookbook\Contracts\Locales\LocaleRepositoryContract $repository
	 * 
	 * @return void
	 */
	public function __construct(LocaleRepositoryContract $repository)
	{
		parent::__construct($repository);
	}

	/**
	 * Handle RepositoryCommand
	 * 
	 * @param Cookbook\Core\Bus\RepositoryCommand $command
	 * 
	 * @return void
	 */
	public function handle(RepositoryCommand $command)
	{
		$locale = $this->repository->update($command->id, $command->params);

		return $locale;
	}
}