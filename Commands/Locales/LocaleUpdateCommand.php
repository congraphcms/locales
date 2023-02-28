<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales\Commands\Locales;

use Congraph\Contracts\Locales\LocaleRepositoryContract;
use Congraph\Core\Bus\RepositoryCommand;

/**
 * LocaleUpdateCommand class
 * 
 * Command for updating locale
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleUpdateCommand extends RepositoryCommand
{
    /**
	 * Create new LocaleUpdateCommand
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
	 * @return Congraph/Core/Repositories/Model
	 */
	public function handle()
	{
		$locale = $this->repository->update($this->id, $this->params);

		return $locale;
	}
}
