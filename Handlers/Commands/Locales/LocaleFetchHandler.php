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
 * LocaleFetchHandler class
 * 
 * Handling command for fetching locale
 * 
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleFetchHandler extends RepositoryCommandHandler
{

	/**
	 * Create new LocaleFetchHandler
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
		$locale = $this->repository->fetch(
			$command->id,
			(!empty($command->params['include']))?$command->params['include']:[]
		);

		return $locale;
	}
}