<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales\Handlers\Commands\Locales;


use Congraph\Contracts\Locales\LocaleRepositoryContract;
use Congraph\Core\Bus\RepositoryCommandHandler;
use Congraph\Core\Bus\RepositoryCommand;

/**
 * LocaleCreateHandler class
 * 
 * Handling command for creating locale
 * 
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleCreateHandler extends RepositoryCommandHandler
{

	/**
	 * Create new LocaleCreateHandler
	 * 
	 * @param Congraph\Contracts\Locales\LocaleRepositoryContract $repository
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
	 * @param Congraph\Core\Bus\RepositoryCommand $command
	 * 
	 * @return void
	 */
	public function handle(RepositoryCommand $command)
	{
		$locale = $this->repository->create($command->params);

		return $locale;
	}
}